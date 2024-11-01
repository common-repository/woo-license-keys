<?php

namespace LicenseKeys\Controllers;

use WC_Product_License_Key;
use Keygen\Keygen;
use WPMVC\Log;
use WPMVC\Request;
use WPMVC\MVC\Controller;
use LicenseKeys\Models\LicenseKey;

/**
 * WooCommerceController controller.
 * Handles all WooCommerce related business logic and hooks.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.2
 */
class WooCommerceController extends Controller
{
    /**
     * Retuns list of product types available.
     * @since 1.0.0
     * 
     * @hook product_type_selector
     *
     * @link http://hookr.io/filters/product_type_selector/
     *
     * @param array $types Product types.
     *
     * @return array
     */
    public function product_type_selector( $types = [] )
    {
        $supported_types = apply_filters( 'woocommerce_license_key_types', [] );
        wp_enqueue_script( 'woo-license-keys-admin-product' );
        wp_add_inline_script(
            'woo-license-keys-admin-product',
            $this->view->get( 'admin.inline-script-data', [ 'data' => apply_filters( 'woocommerce_license_key_product_js_data', [
                'types' => array_map( function( $type ) {
                    return [
                        'type' => $type,
                        'show_if' => 'show_if_' . $type,
                    ];
                }, $supported_types ),
                'simple_types' => array_map(
                    function( $type ) {
                        return [
                            'type' => $type,
                            'show_if' => 'show_if_' . $type,
                        ];
                    },
                    array_filter( $supported_types, function( $type ) {
                        return strpos( 'variable', $type ) === false;
                    } )
                ),
            ] ) ] ),
            'before'
        );
        do_action( 'woocommerce_license_key_admin_enqueue' );
        $types[WC_Product_License_Key::TYPE] = __( 'License Key', 'woo-license-keys' );
        return $types;
    }
    /**
     * Returns list of product tabs.
     * @since 1.0.0
     * 
     * @hook woocommerce_product_data_tabs
     *
     * @link http://hookr.io/filters/woocommerce_product_data_tabs/
     *
     * @param array $tabs Product tabs.
     *
     * @return array
     */
    public function product_data_tabs( $tabs = [] )
    {
        $types = apply_filters( 'woocommerce_license_key_types', [] );
        // Hide "attribute"
        $classes = apply_filters( 'woocommerce_license_key_admin_attribute_tab_class', array_map(
            function( $type ) {
                return 'hide_if_' . $type;
            },
            $types
        ) );
        foreach ( $classes as $class ) {
            $tabs['attribute']['class'][] = $class;
        }
        // Show
        $classes = apply_filters( 'woocommerce_license_key_admin_general_tab_class', array_map(
            function( $type ) {
                return 'show_if_' . $type;
            },
            array_merge( $types, ['simple', 'external'] )
        ) );
        foreach ( $classes as $class ) {
            $tabs['general']['class'][] = $class;
        }
        // Add license key tab
        $tabs[WC_Product_License_Key::TYPE] = [
            'label'     => __( 'License Key', 'woo-license-keys' ),
            'target'    => 'license_key_product_data',
            'class'     => apply_filters( 'woocommerce_license_key_admin_license_key_tab_class', array_map(
                            function( $type ) {
                                return 'show_if_' . $type;
                            },
                            $types
                        ) ),
        ];
        return $tabs;
    }
    /**
     * Displays data panels.
     * @since 1.0.0
     * 
     * @hook woocommerce_product_data_panels
     *
     * @link http://hookr.io/actions/woocommerce_product_data_panels/
     *
     * @global Bridge $licensekeys Main class.
     *
     * @param array $panels Product panels.
     */
    public function product_data_panels( $panels = [] )
    {
        global $licensekeys;
        $this->view->show( 'admin.woocommerce.product-panel', [
            'main'      => &$licensekeys,
            'types'     => apply_filters( 'woocommerce_license_key_types', [] ),
        ] );
    }
    /**
     * Saves product meta.
     * @since 1.0.0
     * 
     * @hook woocommerce_update_product
     *
     * @param int $product_id Product ID.
     */
    public function save_product_meta( $product_id )
    {
        // Get request
        $request = [
            'variable_post_id'              => Request::input( 'variable_post_id', false ),
            '_has_license_key'              => Request::input( '_has_license_key', 'no' ),
            '_expire'                       => Request::input( '_expire', 'no' ),
            '_expire_interval'              => Request::input( '_expire_interval', '' ),
            '_expire_value'                 => Request::input( '_expire_value', '' ),
            '_desktop'                      => Request::input( '_desktop', 'no' ),
            '_sold_individually_override'   => Request::input( '_sold_individually_override', 'no' ),
        ];
        // Prepare product
        if ( ( $request['_has_license_key'] === 'no' && ! wc_is_license_key( $product_id ) )
            || ( $request['variable_post_id'] && count( $request['variable_post_id'] ) > 0 )
        ) return;
        // Update key tag
        update_post_meta( $product_id, '_10q_lk', WC_Product_License_Key::TYPE );
        // Expire
        update_post_meta( $product_id, '_expire', $request['_expire'] );
        if ( $request['_expire'] === 'yes' ) {
            update_post_meta( $product_id, '_expire_interval', $request['_expire_interval'] );
            update_post_meta( $product_id, '_expire_value', intval( $request['_expire_value'] ) );
        }
        // Desktop
        update_post_meta( $product_id, '_desktop', $request['_desktop'] );
        // Override sold individually
        update_post_meta( $product_id, '_sold_individually', $request['_sold_individually_override'] );
    }
    /**
     * Displays template for "license_key" product type.
     * @since 1.0.0
     * 
     * @hook woocommerce_license_key_add_to_cart
     */
    public function add_to_cart_template()
    {
        wc_get_template( 'single-product/add-to-cart/simple.php' );
    }
    /**
     * Generates license keys.
     * @since 1.0.0
     * 
     * @hook woocommerce_order_status_completed
     *
     * @link https://docs.woocommerce.com/wc-apidocs/class-WC_Abstract_Order.html#_get_items
     * @link https://docs.woocommerce.com/wc-apidocs/class-WC_Order_Item.html
     *
     * @param int $order_id Order ID.
     */
    public function order_completed( $order_id )
    {
        try {
            // Get order
            $order = wc_get_order( $order_id );
            // Loop items
            foreach ( $order->get_items() as $item ) {
                // Check if key must be generated or not
                $generate = wc_get_order_item_meta( $item->get_id(), '_license_key_generate', true );
                $generate = apply_filters( 'woocommerce_license_keys_generate_for_order', $generate, $order, $item );
                if ( $generate === null || $generate === '' ) {
                    $product_id = apply_filters(
                        'woocommerce_license_key_order_completed_product_id',
                        $item->get_product_id(),
                        $item
                    );
                    $product = wc_get_product( $product_id );
                    if ( wc_is_license_key( $product ) ) {
                        // Prepare expire date, limit and offline
                        $expire = null;
                        $limit = null;
                        $offline = null;
                        if ( get_post_meta( $product_id, '_expire', true ) === 'yes' ) {
                            $value = intval( get_post_meta( $product_id, '_expire_value', true ) );
                            $interval = get_post_meta( $product_id, '_expire_interval', true );
                            $expire = apply_filters(
                                'woocommerce_license_key_expire_time',
                                strtotime( '+'.$value.' '.$interval ),
                                $interval,
                                $value
                            );
                        }
                        $limit = apply_filters( 'woocommerce_license_key_creation_limit', $limit, $item );
                        $offline = apply_filters( 'woocommerce_license_key_creation_offline', $offline, $item );
                        // Check if keys weren't created before
                        $license_keys = LicenseKey::from_order_item( $order_id, $item->get_id() );
                        // Generate license keys
                        for ( $i = count( $license_keys ); $i < $item->get_quantity(); ++$i ) {
                            wc_add_order_item_meta( $item->get_id(), '_license_key', apply_filters(
                                'woocommerce_license_key_meta_value',
                                [
                                    'code'      => Keygen::alphanum( apply_filters( 'woocommerce_license_key_length', 26 ) )->generate(),
                                    'expire'    => $expire,
                                    'uses'      => [],
                                    'limit'     => $limit,
                                    'offline'   => $offline,
                                ],
                                $item,
                                $product,
                                $order
                            ) );
                            $i = apply_filters(
                                'woocommerce_license_key_generate_loop_index',
                                $i,
                                $item,
                                $product,
                                $order
                            );
                        }
                        // Turn generate flag off
                        wc_add_order_item_meta(
                            $item->get_id(),
                            '_license_key_generate',
                            apply_filters( 'woocommerce_license_keys_generate_flag', false, $item )
                        );
                        do_action( 'woocommerce_license_keys_generated', $order_id, $item );
                    }
                }
                // Is there an action to call from other plugins ?
                $action = wc_get_order_item_meta( $item->get_id(), '_license_key_action', true );
                if ( $action !== null )
                    do_action( 'woocommerce_license_keys_completed_action_' . $action, $item, $order );
            }
        } catch ( Exception $e ) {
            Log::error( $e );
        }
    }
    /**
     * Expires generated license keys.
     * @since 1.1.0
     * 
     * @hook woocommerce_order_status_cancelled
     * @hook woocommerce_order_status_refunded
     * @hook woocommerce_order_status_failed
     *
     * @param int $order_id Order ID.
     */
    public function order_cancelled( $order_id )
    {
        try {
            $license_keys = [];
            foreach ( apply_filters( 'woocommerce_license_keys_to_cancel', LicenseKey::from_order( $order_id ), $order_id ) as $license_key ) {
                // Force expiration
                $license_key = LicenseKey::find_by_code( $license_key->order_item_id, $license_key->code );
                $license_key->expire = strtotime( '-1 day' );
                $license_key = apply_filters( 'woocommerce_license_key_before_save', $license_key );
                $license_key->save();
                add_action( 'woocommerce_license_key_saved', $license_key );
                do_action( 'woocommerce_license_key_cancelled', $license_key, $order_id );
                $license_keys[] = $license_key;
            }
            // Add remove note
            if ( !empty( $license_keys ) ) {
                $order = wc_get_order(  $order_id );
                $order->add_order_note( apply_filters(
                    'woocommerce_license_key_remove_note',
                    sprintf(
                        __( 'License key(s) %s have been set as expired.', 'woo-license-keys' ),
                        implode( ', ', array_map( function( $license_key ) { return($license_key->the_key); }, $license_keys ) )
                    ),
                    $license_keys
                ), 0 );
                $order->save();
            }
        } catch ( Exception $e ) {
            Log::error( $e );
        }
    }
    /**
     * Returns list of product tabs (PRODUCT PAGE).
     * @since 1.0.0
     * 
     * @hook woocommerce_product_tabs
     *
     * @see https://docs.woocommerce.com/document/editing-product-data-tabs/
     *
     * @global object $product Current product being displayed.
     *
     * @param array $tabs Product tabs.
     *
     * @return array
     */
    public function product_tabs( $tabs = [] )
    {
        global $product;
        if ( isset( $product ) && wc_is_license_key( $product ) ) {
            $tabs['license_key_details'] = [
                'title'     => apply_filters( 'woocommerce_license_key_details_heading', __( 'License Details', 'woo-license-keys' ) ),
                'priority'  => apply_filters( 'woocommerce_license_key_tab_details_priority', 15 ),
                'callback'  => [&$this, 'tab_details'],
            ];
        }
        return $tabs;
    }
    /**
     * Displays license key details tab.
     * @since 1.0.0
     *
     * @see https://docs.woocommerce.com/document/editing-product-data-tabs/
     *
     * @global object $product Current product being displayed.
     *
     * @param string $tab Tab key
     */
    public function tab_details( $tab )
    {
        global $product;
        do_action(
            'woocommerce_license_key_details_table',
            $this->get_default_license_key( $product ),
            $tab,
            esc_html( apply_filters( 'woocommerce_license_key_details_heading', __( 'License Details', 'woo-license-keys' ) ) )
        );
    }
    /**
     * Displays License Key details table.
     * Normally used to be displayed inside a product tab.
     * @since 1.2.11
     * 
     * @hook woocommerce_license_key_details_table
     * 
     * @param \LicenseKeys\Models\LicenseKey $license_key License Key data to display
     * @param string                         $tab
     * @param string                         $heading
     */
    public function details_table( $license_key, $tab, $heading )
    {
        wc_get_template(
            'single-product/tabs/license-key-details.php',
            [
                'tab'           => &$tab,
                'heading'       => &$heading,
                'license_key'   => &$license_key,
            ],
            null,
            __DIR__.'/../../templates/'
        );
    }
    /**
     * Returns default attributes of a license key, based on the attributes found in a product.
     * @since 1.0.0
     *
     * @param int $prodcut Product.
     *
     * @return LicenseKey
     */
    public function get_default_license_key( $product )
    {
        $data = [
            'product'       => $product,
            'is_desktop'    => get_post_meta( $product->get_id(), '_desktop', true ) === 'yes',
            'expire'        => get_post_meta( $product->get_id(), '_expire', true ) === 'yes',
            'limit'         => ! empty( get_post_meta( $product->get_id(), '_limit', true ) ),
        ];
        // Add extra details
        if ( $data['expire'] ) {
            $data['expire_interval'] = get_post_meta( $product->get_id(), '_expire_interval', true );
            $data['expire_value'] = get_post_meta( $product->get_id(), '_expire_value', true );
        }
        if ( $data['limit'] ) {
            $data['limit'] = [
                get_post_meta( $product->get_id(), '_limit', true ) => get_post_meta( $product->get_id(), '_limit_value', true )
            ];
        }
        return apply_filters( 'woocommerce_default_license_key', new LicenseKey( $data ) );
    }
    /**
     * Returns api section settings.
     * @since 1.0.0
     * 
     * @hook woocommerce_get_sections_advanced
     *
     * @see https://docs.woocommerce.com/document/adding-a-section-to-a-settings-tab/
     *
     * @param array $sections Settings sections.
     *
     * @return array
     */
    public function sections_api( $sections = [] )
    {
        $sections['license_keys'] = __( 'License Keys API', 'woo-license-keys' );
        return $sections;   
    }
    /**
     * Returns License Key settings section data.
     * @since 1.0.0
     * 
     * @hook woocommerce_get_settings_advanced
     *
     * @param array  $settings        Settings.
     * @param string $current_section Current section key.
     *
     * @return array
     */
    public function settings_api( $settings = [], $current_section )
    {
        if ( $current_section === 'license_keys' ) {
            return apply_filters( 'woocommerce_license_key_settings', [
                [
                    'name'  => __( 'Store Code', 'woo-license-keys' ),
                    'type'  => 'title',
                    'desc'  => $this->view->get( 'admin.woocommerce.settings-store-code' ),
                    'id'    => 'license_keys_store_code',
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_store_code',
                ],
                [
                    'name'  => __( 'Configuration', 'woo-license-keys' ),
                    'type'  => 'title',
                    'id'    => 'license_keys_api_config',
                ],
                [
                    'name'  => __( 'API Handler', 'woo-license-keys' ),
                    'id'    => 'license_keys_api_handler',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_api_handlers',
                            [
                                'wp_ajax'   => __( 'WP Ajax', 'woo-license-keys' ),
                                'wp_rest'   => __( 'Wordpress REST API', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Identifies who will handle and process your API calls.', 'woo-license-keys' ),
                    'default' => 'wp_ajax',
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_api_config',
                ],
                [
                    'name'  => __( 'HTTP Headers', 'woo-license-keys' ),
                    'type'  => 'title',
                    'id'    => 'license_keys_headers',
                ],
                [
                    'name'  => __( 'Enable override', 'woo-license-keys' ),
                    'id'    => 'license_keys_override_headers',
                    'type'  => 'checkbox',
                    'desc'  => __( 'Check this option to override Wordpress default headers with the ones below', 'woo-license-keys' ),
                    'desc_tip' => sprintf(
                                __( 'Only effective during API calls (non REST handler); this option is useful if you want to have control over %s.', 'woo-license-keys' ),
                                '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS" target="_blank">CORS</a>'
                            ),
                ],
                [
                    'name'  => __( 'Allow Origin', 'woo-license-keys' ),
                    'id'    => 'license_keys_header_allow_origin',
                    'type'  => 'text',
                    'desc_tip' => sprintf( __( 'Value for "%s".', 'woo-license-keys' ), 'Access-Control-Allow-Origin' ),
                    'default' => '*',
                ],
                [
                    'name'  => __( 'Allow Methods', 'woo-license-keys' ),
                    'id'    => 'license_keys_header_allow_methods',
                    'type'  => 'text',
                    'desc_tip' => sprintf( __( 'Value for "%s".', 'woo-license-keys' ), 'Access-Control-Allow-Methods' ),
                    'default' => 'POST, GET',
                ],
                [
                    'name'  => __( 'Allow Credentials', 'woo-license-keys' ),
                    'id'    => 'license_keys_header_allow_credentials',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_allow_credentials',
                            [
                                ''      => __( '-None-', 'woo-license-keys' ),
                                'true'  => __( 'True', 'woo-license-keys' ),
                                'false' => __( 'False', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => sprintf( __( 'Value for "%s".', 'woo-license-keys' ), 'Access-Control-Allow-Credentials' ),
                    'default' => 'true',
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_headers',
                ],
                [
                    'name'  => __( 'API Validations', 'woo-license-keys' ),
                    'type'  => 'title',
                    'id'    => 'license_keys_validations',
                ],
                [
                    'name'  => __( 'Enable SKU validation', 'woo-license-keys' ),
                    'id'    => 'license_keys_enable_sku_val',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_validation_settings_options',
                            [
                                1 => __( 'Yes', 'woo-license-keys' ),
                                0 => __( 'No', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Enable SKU validations (SKU required parameter and SKU cross-check) during API calls.', 'woo-license-keys' ),
                    'default' => 1,
                ],
                [
                    'name'  => __( 'Enable domain validation', 'woo-license-keys' ),
                    'id'    => 'license_keys_enable_domain_val',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_validation_settings_options',
                            [
                                1 => __( 'Yes', 'woo-license-keys' ),
                                0 => __( 'No', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Enable domain validations during API calls (only affects non-desktop products).', 'woo-license-keys' ),
                    'default' => 1,
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_validations',
                ],
                [
                    'name'  => __( 'API Response', 'woo-license-keys' ),
                    'type'  => 'title',
                    'id'    => 'license_keys_response',
                ],
                [
                    'name'  => __( 'Errors output', 'woo-license-keys' ),
                    'id'    => 'license_keys_response_errors_format',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_response_error_formats',
                            [
                                'property'  => __( 'Grouped by property name.', 'woo-license-keys' ),
                                'code'      => __( 'Associated to an error code.', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Indicates how should errors be returned in API responses.', 'woo-license-keys' ),
                    'default' => 'property',
                ],
                [
                    'name'  => __( 'Include user email', 'woo-license-keys' ),
                    'id'    => 'license_keys_include_user_email',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_response_settings_options',
                            [
                                1 => __( 'Yes', 'woo-license-keys' ),
                                0 => __( 'No', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Includes the user\'s email in the API response.', 'woo-license-keys' ),
                    'default' => 0,
                ],
                [
                    'name'  => __( 'Include product name', 'woo-license-keys' ),
                    'id'    => 'license_keys_include_product_name',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_response_settings_options',
                            [
                                1 => __( 'Yes', 'woo-license-keys' ),
                                0 => __( 'No', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Includes the product\'s name in the API response.', 'woo-license-keys' ),
                    'default' => 0,
                ],
                [
                    'name'  => __( 'Include product SKU', 'woo-license-keys' ),
                    'id'    => 'license_keys_include_product_sku',
                    'type'  => 'select',
                    'options' => apply_filters( 'woocommerce_license_key_response_settings_options',
                            [
                                1 => __( 'Yes', 'woo-license-keys' ),
                                0 => __( 'No', 'woo-license-keys' ),
                            ] ),
                    'desc_tip' => __( 'Includes the product\'s SKU in the API response.', 'woo-license-keys' ),
                    'default' => 0,
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_response',
                ],
                [
                    'name'  => __( 'DOCUMENTATION', 'woo-license-keys' ),
                    'type'  => 'title',
                    'desc'  => $this->view->get( 'admin.woocommerce.settings-license-key' ),
                    'id'    => 'license_keys_documentation',
                ],
                [
                    'type'  => 'sectionend',
                    'id'    => 'license_keys_documentation',
                ],
            ] );
        }
        return $settings;
    }
    /**
     * Displays license keys on order complete email.
     * @since 1.0.0
     * 
     * @hook woocommerce_email_after_order_table
     *
     * @see http://hookr.io/actions/woocommerce_email_after_order_table/
     *
     * @param WC_Order $order         Order data object.
     * @param bool     $sent_to_admin Flag that indicates if this is being sent to admin.
     * @param bool     $plain_text    Flag that indicates if this is a plain text email.
     * @param objecy   $email         Email object.
     */
    public function email( $order, $sent_to_admin, $plain_text, $email )
    {
        if ( $email->id === 'customer_completed_order' ) {
            $license_keys = LicenseKey::from_order( $order->get_id() );
            $license_keys = apply_filters( 'woocommerce_license_keys_in_email', $license_keys, $order->get_id() );
            if ( count( $license_keys ) > 0 ) {
                // Display template
                wc_get_template(
                    $plain_text === true ? 'emails/plain/email-license-keys.php' : 'emails/email-license-keys.php',
                    [
                        'license_keys'  => $license_keys,
                        'text_align'    => is_rtl() ? 'right' : 'left',
                    ],
                    null,
                    __DIR__.'/../../templates/'
                );
            }
        }
    }
    /**
     * Displays license keys on order received / completed.
     * @since 1.0.2
     * 
     * @hook woocommerce_thankyou
     *
     * @param int $order_id Order ID.
     */
    public function thankyou( $order_id )
    {
        $license_keys = LicenseKey::from_order( $order_id );
        $license_keys = apply_filters( 'woocommerce_license_keys_in_thankyou', $license_keys, $order_id );
        if ( count( $license_keys ) > 0 ) {
            // Display template
            wc_get_template(
                'order/order-license-keys.php',
                [
                    'license_keys'  => $license_keys,
                ],
                null,
                __DIR__.'/../../templates/'
            );
        }
    }
    /**
     * Returns list of license key based product types.
     * @since 1.2.9
     * 
     * @hook woocommerce_license_key_types
     * 
     * @return array
     */
    public function types()
    {
        return [WC_Product_License_Key::TYPE];
    }
    /**
     * Returns the options checkboxes available per product.
     * @since 1.2.9
     * 
     * @hook product_type_options
     * 
     * @param array $options
     * 
     * @return array
     */
    public function product_type_options( $options )
    {
        $types = apply_filters( 'woocommerce_license_key_types', [] );
        $options['virtual']['wrapper_class'] .= ' '
            . wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_virtual', $types ) );
        $options['downloadable']['wrapper_class'] .= ' '
            . wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_downloadable', $types ) );
        return $options;
    }
}