<?php

use LicenseKeys\Models\LicenseKey;

/**
 * Global functions
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.6
 */

if ( ! function_exists( 'wc_is_license_key' ) ) {
    /**
     * Returns flag indicating if a product is type of "License Key".
     * @since 1.2.9
     * 
     * @param int|string|WC_Product|mixed $product_or_type The product (id or object) or a type string to check.
     * 
     * @return bool
     */
    function wc_is_license_key( $product_or_type = null )
    {
        if ( $product_or_type === null ) {
            global $product;
            $product_or_type = $product;
        }
        if ( is_numeric( $product_or_type ) ) {
            $product_or_type = wc_get_product( $product_or_type );
        }
        $type = $product_or_type && is_object( $product_or_type ) && method_exists( $product_or_type , 'get_type' )
            ? $product_or_type->get_type()
            : $product_or_type;
        return apply_filters(
            'wc_is_license_key',
            is_string( $type )
                ? in_array( $type , apply_filters( 'woocommerce_license_key_types', [] ) )
                : false,
            $product_or_type,
            $type
        );
    }
}

if ( ! function_exists( 'wc_lk_show_if' ) ) {
    /**
     * Returns product types with show_if prefixed as a only string, to be used on admin product data panels.
     * @since 1.2.9
     * 
     * @param array $types License key types | Product types.
     * 
     * @return string
     */
    function wc_lk_show_if( $types )
    {
        return implode( ' ', array_map(
            function( $type ) {
                return 'show_if_' . $type;
            },
            $types
        ) );
    }
}

if ( ! function_exists( 'wc_get_license_key' ) ) {
    /**
     * Returns a License Key.
     * @since 1.2.10
     * 
     * @param string $code
     * 
     * @return \LicenseKeys\Models\LicenseKey
     */
    function wc_get_license_key( $code )
    {
        _deprecated_function( __FUNCTION__, '1.5.0', 'wc_find_license_key()' );
        return wc_find_license_key( $code );
    }
}

if ( ! function_exists( 'wc_find_license_key' ) ) {
    /**
     * Finds and returns a License Key based on searched arguments.
     * @since 1.3.0
     * 
     * @param string|array $args
     * 
     * @return \LicenseKeys\Models\LicenseKey
     */
    function wc_find_license_key( $args = [] )
    {
        $license_key = null;
        if ( is_string( $args ) )
            $args = ['code' => $args];
        // Find
        if ( array_key_exists( 'order_item_id', $args )
            && array_key_exists( 'code', $args )
            && ! empty( $args['order_item_id'] )
            && ! empty( $args['code'] )
        ) {
            $license_key = LicenseKey::find_by_code( $args['order_item_id'], $args['code'] );
        } else if ( apply_filters( 'woocommerce_license_keys_enable_format_validation', true )
            && array_key_exists( 'code', $args )
            && ! empty( $args['code'] )
        ) {
            $license_key = LicenseKey::find( $args['code'] );
        } else if ( array_key_exists( 'user_id', $args )
            && array_key_exists( 'item', $args )
            && array_key_exists( 'index', $args )
            && ! empty( $args['user_id'] )
            && ! empty( $args['item'] )
            && ! empty( $args['index'] )
        ) {
            $license_key = LicenseKey::find_by_user_index( $args['user_id'], $args['item'], $args['index'] );
        }
        $license_key = apply_filters( 'woocommerce_license_key_find', $license_key, $args );
        // Return
        return $license_key ? apply_filters( 'woocommerce_license_key', $license_key ) : null;
    }
}

if ( ! function_exists( 'WC_LK' ) ) {
    /**
     * Returns plugin's bridge class.
     * @since 1.3.0
     * 
     * @return \LicenseKeys\Main
     */
    function WC_LK()
    {
        return get_bridge( 'LicenseKeys' );
    }
}

if ( ! function_exists( 'wc_license_key_array_filter' ) ) {
    /**
     * Returns flag indicating if license key is valid for usage.
     * Function to be used as callable inside `array_filter()` function.
     * @since 1.4.0
     * 
     * @param \LicenseKeys\Models\LicenseKey $license_key
     * 
     * @return bool
     */
    function wc_license_key_array_filter( $license_key )
    {
        return $license_key && $license_key->is_valid;
    }
}

if ( ! function_exists( 'wc_license_key_builder_mapping' ) ) {
    /**
     * Returns builder resulting row (OBJECT) as a license key model.
     * Function to be used in query builder.
     * @since 1.4.0
     * 
     * @param objecy $row
     * 
     * @return \LicenseKeys\Models\LicenseKey
     */
    function wc_license_key_builder_mapping( $row )
    {
        // Get meta
        $key = maybe_unserialize( $row->meta_value );
        $key['order_id'] = $row->order_id;
        $key['order_item_id'] = $row->order_item_id;
        $key['meta_id'] = $row->meta_id;
        $key['product'] = wc_get_product( apply_filters(
            'woocommerce_license_key_traits_product_id',
            $row->product_id,
            $row
        ) );
        $key['order'] = wc_get_order( $row->order_id );
        $key = apply_filters( 'woocommerce_license_keys_query_results_row', $key, $row );
        return apply_filters( 'woocommerce_license_key', new LicenseKey( $key ) );
    }
}

if ( !function_exists( 'wc_license_key_activate' ) ) {
    /**
     * Activates (adds an activation) to a license key.
     * @since 1.5.1
     * 
     * @param array|string|\LicenseKeys\Models\LicenseKey $args Activation arguments.
     *
     * @return \WPMVC\Response Response from validator.
     */
    function wc_license_key_activate( $args )
    {
        return WC_LK()->{'_c_return_ValidatorController@activate'}(
            apply_filters( 'woocommerce_license_keys_validator_via_function_args', $args )
        );
    }
}

if ( !function_exists( 'wc_license_key_validate' ) ) {
    /**
     * Validates a license key activation.
     * @since 1.5.1
     * 
     * @param array|string|\LicenseKeys\Models\LicenseKey $args          Validation arguments.
     * @param int                                         $activation_id Expected if a model is passed as argument.
     *
     * @return \WPMVC\Response Response from validator.
     */
    function wc_license_key_validate( $args, $activation_id = null )
    {
        return WC_LK()->{'_c_return_ValidatorController@validate'}(
            apply_filters( 'woocommerce_license_keys_validator_via_function_args', $args, $activation_id, true )
        );
    }
}

if ( !function_exists( 'wc_license_key_deactivate' ) ) {
    /**
     * Deactivates a license key activation.
     * @since 1.5.1
     * 
     * @param array|string|\LicenseKeys\Models\LicenseKey $args          Validation arguments.
     * @param int                                         $activation_id Expected if a model is passed as argument.
     *
     * @return \WPMVC\Response Response from validator.
     */
    function wc_license_key_deactivate( $args, $activation_id = null )
    {
        return WC_LK()->{'_c_return_ValidatorController@deactivate'}(
            apply_filters( 'woocommerce_license_keys_validator_via_function_args', $args, $activation_id, true )
        );
    }
}