<?php

namespace LicenseKeys\Controllers;

use WPMVC\Request;
use WPMVC\Response;
use WPMVC\MVC\Controller;

/**
 * Handles and process the API using Wordpress AJAX.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.3.9
 */
class WPAjaxController extends Controller
{
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @hook wp_ajax_license_key_activate
     * @hook wp_ajax_nopriv_license_key_activate
     */
    public function activate()
    {
        $response = WC_LK()->{'_c_return_ValidatorController@activate'}( [
            'store_code'    => Request::input( 'store_code' ),
            'sku'           => Request::input( 'sku' ),
            'key_code'      => Request::input( 'license_key' ),
            'domain'        => esc_url( Request::input(
                                'domain',
                                apply_filters( 'woocommerce_license_key_default_domain', 'localhost' )
                            ) ),
        ] );
        do_action( 'woocommerce_license_key_api_headers' );
        $response->json();
    }
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @hook wp_ajax_license_key_validate
     * @hook wp_ajax_nopriv_license_key_validate
     */
    public function validate()
    {
        $response = WC_LK()->{'_c_return_ValidatorController@validate'}( [
            'activation_id' => Request::input( 'activation_id', 0 ),
            'store_code'    => Request::input( 'store_code' ),
            'sku'           => Request::input( 'sku' ),
            'key_code'      => Request::input( 'license_key' ),
            'domain'        => esc_url( Request::input(
                                'domain',
                                apply_filters( 'woocommerce_license_key_default_domain', 'localhost' )
                            ) ),
        ] );
        do_action( 'woocommerce_license_key_api_headers' );
        $response->json();
    }
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @hook wp_ajax_license_key_deactivate
     * @hook wp_ajax_nopriv_license_key_deactivate
     */
    public function deactivate()
    {
        $response = WC_LK()->{'_c_return_ValidatorController@deactivate'}( [
            'activation_id' => Request::input( 'activation_id', 0 ),
            'store_code'    => Request::input( 'store_code' ),
            'sku'           => Request::input( 'sku' ),
            'key_code'      => Request::input( 'license_key' ),
            'domain'        => esc_url( Request::input(
                                'domain',
                                apply_filters( 'woocommerce_license_key_default_domain', 'localhost' )
                            ) ),
        ] );
        do_action( 'woocommerce_license_key_api_headers' );
        $response->json();
    }
}