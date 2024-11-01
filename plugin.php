<?php
/*
Plugin Name: License Keys for WooCommerce
Plugin URI: https://www.10quality.com/product/woocommerce-license-keys/
Description: Enable and handle "License Keys" with WooCommerce.
Version: 1.5.6
Author: 10 Quality
Author URI: https://www.10quality.com/
Text Domain: woo-license-keys
Domain Path: /assets/languages/
Requires PHP: 5.6

WC requires at least: 3
WC tested up to: 4.5

See "LICENSE" file.
*/
//------------------------------------------------------------
//
// LOAD FRAMEWORK
//
//------------------------------------------------------------
require_once( __DIR__ . '/app/Boot/bootstrap.php' );

//------------------------------------------------------------
//
// ACTIVATION HOOK
//
//------------------------------------------------------------
register_activation_hook( __FILE__, [ &$licensekeys, '_c_void_AccountController@flush' ] );

//------------------------------------------------------------
//
// SPECIAL LOAD
//
//------------------------------------------------------------
if ( ! function_exists( 'register_type_license_key' ) ) {
    /**
     * Registers custom WooCommerce product type.
     * @since 1.0.0
     * @since 1.0.9 Checks woocommerce existance.
     *
     * @global object $woocommerce WooCommerce global object.
     */
    function register_type_license_key()
    {
        global $woocommerce;
        if ( isset( $woocommerce ) ) {
            require_once __DIR__ . '/app/Global/WC_Product_License_Key.php';
        } else {
            // Add admin notice.
            add_action( 'admin_notices', function() {
                global $licensekeys;
                $licensekeys->view( 'admin.notices.require-woocommerce' );
            } );
        }
    }
    add_action( 'init', 'register_type_license_key' );
}