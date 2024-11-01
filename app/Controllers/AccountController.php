<?php

namespace LicenseKeys\Controllers;

use Exception;
use WPMVC\Log;
use WPMVC\Request;
use WPMVC\MVC\Controller;
use LicenseKeys\Models\LicenseKey;
use LicenseKeys\Core\ValidationException;
/**
 * WooCommerce "My Account" related hooks.
 * Handles all account related business logic.
 *
 * @see https://gist.github.com/claudiosanches/a79f4e3992ae96cb821d3b357834a005
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.0
 */
class AccountController extends Controller
{
    /**
     * Account's license keys endpoint key.
     * @since 1.0.0
     *
     * @var string
     */
    const ENDPOINT = 'license-keys';
    /**
     * Account's view license key endpoint key.
     * @since 1.0.0
     *
     * @var string
     */
    const VIEW_ENDPOINT = 'view-license-key';
    /**
     * Flushes rewrite rules.
     * Plugin Activation.
     * @since 1.0.0
     */
    public function flush()
    {
        flush_rewrite_rules();
    }
    /**
     * Adds endpoint rewrite rule.
     * @since 1.0.0
     * 
     * @hook init
     *
     * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
     */
    public function add_endpoint()
    {
        add_rewrite_endpoint( self::ENDPOINT, EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( self::VIEW_ENDPOINT, EP_ROOT | EP_PAGES );
    }
    /**
     * Returns query vars with endpoint considered.
     * @since 1.0.0
     * 
     * @hook query_vars
     *
     * @param array $vars
     * 
     * @return array
     */
    public function query_vars( $vars = [] )
    {
        $vars[] = self::ENDPOINT;
        $vars[] = self::VIEW_ENDPOINT;
        return $vars;
    }
    /**
     * Retuns account menu items with "License Keys" option added.
     * @since 1.0.0
     * 
     * @hook woocommerce_account_menu_items
     *
     * @param array $menu Menu items.
     *
     * @return array
     */
    public function menu_items( $menu = [] )
    {
        $items = [];
        foreach ( $menu as $key => $value ) {
            $items[$key] = $value;
            // Add license keys menu option
            if ( $key === 'orders' ) {
                $items[self::ENDPOINT] = __( 'License Keys', 'woo-license-keys' );
            }
        }
        return $items;
    }
    /**
     * Displays endpoint.
     * @since 1.0.0
     * 
     * @hook woocommerce_account_[...]_endpoint
     *
     * @see https://developer.wordpress.org/reference/functions/get_current_user_id/
     * @see https://docs.woocommerce.com/wc-apidocs/function-wc_get_order_item_meta.html
     */
    public function endpoint()
    {
        $license_keys = [];
        // Prepare data
        try {
            $license_keys = LicenseKey::from_user( get_current_user_id() );
        } catch ( Exception $e ) {
            Log::error( $e );
            do_action( 'woocommerce_license_key_browse_endpoint_exception', $e, $license_key );
        }
        do_action( 'woocommerce_license_key_before_browse_endpoint', $license_keys );
        wp_enqueue_style( 'dashicons' );
        do_action( 'woocommerce_license_key_enqueue' );
        do_action( 'woocommerce_license_keys_page_enqueue' );
        // Display template
        wc_get_template(
            'myaccount/license-keys.php',
            ['license_keys' => apply_filters( 'woocommerce_account_license_keys', $license_keys )],
            null,
            __DIR__.'/../../templates/'
        );
    }
    /**
     * Displays view endpoint.
     * @since 1.0.0
     * 
     * @hook woocommerce_account_[...]_endpoint
     */
    public function view_endpoint()
    {
        $license_key = null;
        // Prepare data
        try {
            $license_key = wc_find_license_key( isset( $_GET['key'] )
                ? ['code' => Request::input( 'key' )]
                : [
                    'user_id'   => get_current_user_id(),
                    'item'      => Request::input( 'item' ),
                    'index'     => Request::input( 'index', 0 ),
                ]
            );
            // Deactivations?
            if ( isset( $_GET['deactivate'] )
                && $license_key
                && $license_key->deactivate( Request::input( 'deactivate' ) )
            ) {
                do_action( 'woocommerce_license_key_activation_deactivated', $license_key, Request::input( 'deactivate' ) );
                wc_get_template(
                    'notices/success.php',
                    ['messages' => [
                        sprintf( __( 'Activation ID:%d has been deactivated.', 'woo-license-keys' ), Request::input( 'deactivate' ) )
                    ]]
                );
            }
            // Display
            $order = wc_get_order( $license_key->order_id );
            do_action( 'woocommerce_license_key_before_view_endpoint', $license_key, $order );
            if ( $license_key && $order->get_customer_id() === get_current_user_id() ) {
                // Apply hook actions
                $hook = Request::input( 'hook_action', false );
                if ( ! empty( $hook ) )
                    do_action( 'woocommerce_license_key_hook_' . sanitize_text_field( $hook ), $license_key );
                $hook = Request::input( 'hook_filter', false );
                if ( ! empty( $hook ) )
                    $license_key = apply_filters( 'woocommerce_license_key_hook_' . sanitize_text_field( $hook ), $license_key );
                wp_enqueue_style( 'dashicons' );
                do_action( 'woocommerce_license_key_enqueue' );
                do_action( 'woocommerce_license_key_page_enqueue' );
                // Display template
                wc_get_template(
                    'myaccount/view-license-key.php',
                    [
                        'license_key'   => apply_filters( 'woocommerce_account_license_key', $license_key ),
                        'is_desktop'    => get_post_meta( $license_key->product->get_id(), '_desktop', true ) === 'yes',
                    ],
                    null,
                    __DIR__.'/../../templates/'
                );
                return;
            }
        } catch ( ValidationException $e ) {
            do_action( 'woocommerce_license_key_view_endpoint_exception', $e, $license_key );
        } catch ( Exception $e ) {
            Log::error( $e );
            do_action( 'woocommerce_license_key_view_endpoint_exception', $e, $license_key );
        }
        // 404 Error
        ?><script type="text/javascript">window.location = '<?= home_url( '/license-not-found' ) ?>';</script><?php
    }
    /**
     * Enqueues assets for view_endpoint.
     * @since 1.2.1
     * 
     * @hook woocommerce_license_key_page_enqueue
     * 
     * @global LicenseKeys\Main $licensekeys
     */
    public function view_enqueue()
    {
        wp_enqueue_script( 'woo-license-keys-my-account' );
    }
    /**
     * Returns query vars for custom endpoint.
     * @since 1.5.0
     *
     * @hook woocommerce_get_query_vars
     *
     * @param array $vars
     * 
     * @return array
     */
    public function wc_query_vars( $vars )
    {
        $vars[self::ENDPOINT] = self::ENDPOINT;
        $vars[self::VIEW_ENDPOINT] = self::VIEW_ENDPOINT;
        return $vars;
    }
    /**
     * Returns endpoint title.
     * @since 1.5.0
     * 
     * @hook woocommerce_endpoint_{self::ENDPOINT}_title
     *
     * @param string $title
     * 
     * @return string
     */
    public function endpoint_title()
    {
        return __( 'License Keys', 'woo-license-keys' );
    }
    /**
     * Returns view endpoint title.
     * @since 1.5.0
     * 
     * @hook woocommerce_endpoint_{self::VIEW_ENDPOINT}_title
     *
     * @param string $title
     * 
     * @return string
     */
    public function view_endpoint_title()
    {
        return __( 'License Key', 'woo-license-keys' );
    }
}