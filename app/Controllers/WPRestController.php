<?php

namespace LicenseKeys\Controllers;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WPMVC\Response;
use WPMVC\MVC\Controller;

/**
 * Handles and process the API using Wordpress REST.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.3
 */
class WPRestController extends Controller
{
    /**
     * Inits endpoints.
     * @since 1.3.0
     * 
     * @hook rest_api_init
     */
    public function init()
    {
        register_rest_route( 'woo-license-keys/v1', '/activate', apply_filters( 'woocommerce_license_keys_wp_rest_activate_endpoint', [
            'methods'   => $this->get_rest_methods(),
            'callback'  => [&$this, 'activate'],
            'args'      => $this->parse_args( apply_filters( 'woocommerce_license_key_wp_rest_activate_args', [
                            'store_code' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'sku' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'license_key' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'domain' => [
                                'required'          => false,
                                'default'           => apply_filters( 'woocommerce_license_key_default_domain', 'localhost' ),
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                        ] ) ),
            'permission_callback' => '__return_true',
        ] ) );
        register_rest_route( 'woo-license-keys/v1', '/validate', apply_filters( 'woocommerce_license_keys_wp_rest_validate_endpoint', [
            'methods'   => $this->get_rest_methods(),
            'callback'  => [&$this, 'validate'],
            'args'      => $this->parse_args( apply_filters( 'woocommerce_license_key_wp_rest_validate_args', [
                            'activation_id' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_numeric'],
                                'sanitize_callback' => 'absint',
                            ],
                            'store_code' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'sku' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'license_key' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'domain' => [
                                'required'          => false,
                                'default'           => apply_filters( 'woocommerce_license_key_default_domain', 'localhost' ),
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                        ] ) ),
            'permission_callback' => '__return_true',
        ] ) );
        register_rest_route( 'woo-license-keys/v1', '/deactivate', apply_filters( 'woocommerce_license_keys_wp_rest_deactivate_endpoint', [
            'methods'   => $this->get_rest_methods(),
            'callback'  => [&$this, 'deactivate'],
            'args'      => $this->parse_args( apply_filters( 'woocommerce_license_key_wp_rest_deactivate_args', [
                            'activation_id' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_numeric'],
                                'sanitize_callback' => 'absint',
                            ],
                            'store_code' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'sku' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'license_key' => [
                                'required'          => true,
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                            'domain' => [
                                'required'          => false,
                                'default'           => apply_filters( 'woocommerce_license_key_default_domain', 'localhost' ),
                                'validate_callback' => [&$this, 'validate_is_string'],
                                'sanitize_callback' => 'sanitize_text_field',
                            ],
                        ] ) ),
            'permission_callback' => '__return_true',
        ] ) );
    }
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @param \WP_REST_Request $request
     * 
     * @return \WP_REST_Response|\WP_Error
     */
    public function activate( WP_REST_Request $request )
    {
        return $this->get_response( WC_LK()->{'_c_return_ValidatorController@activate'}( [
            'store_code'    => $request->get_param( 'store_code' ),
            'sku'           => $request->get_param( 'sku' ),
            'key_code'      => $request->get_param( 'license_key' ),
            'domain'        => esc_url( $request->get_param( 'domain' ) ),
        ] ) );
    }
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @param \WP_REST_Request $request
     * 
     * @return \WP_REST_Response|\WP_Error
     */
    public function validate( WP_REST_Request $request )
    {
        return $this->get_response( WC_LK()->{'_c_return_ValidatorController@validate'}( [
            'activation_id' => $request->get_param( 'activation_id' ),
            'store_code'    => $request->get_param( 'store_code' ),
            'sku'           => $request->get_param( 'sku' ),
            'key_code'      => $request->get_param( 'license_key' ),
            'domain'        => esc_url( $request->get_param( 'domain' ) ),
        ] ) );
    }
    /**
     * Handles validate endpoint.
     * @since 1.3.0
     * 
     * @param \WP_REST_Request $request
     * 
     * @return \WP_REST_Response|\WP_Error
     */
    public function deactivate( WP_REST_Request $request )
    {
        return $this->get_response( WC_LK()->{'_c_return_ValidatorController@deactivate'}( [
            'activation_id' => $request->get_param( 'activation_id' ),
            'store_code'    => $request->get_param( 'store_code' ),
            'sku'           => $request->get_param( 'sku' ),
            'key_code'      => $request->get_param( 'license_key' ),
            'domain'        => esc_url( $request->get_param( 'domain' ) ),
        ] ) );
    }
    /**
     * Returns flag indicating if a value is numeric or not.
     * @since 1.3.0
     * 
     * @param mixed  $value
     * @param array  $request
     * @param string $key
     * 
     * @return bool
     */
    public function validate_is_numeric( $value, $request, $key )
    {
        return is_numeric( $value );
    }
    /**
     * Returns flag indicating if a value is string or not.
     * @since 1.3.0
     * 
     * @param mixed  $value
     * @param array  $request
     * @param string $key
     * 
     * @return bool
     */
    public function validate_is_string( $value, $request, $key )
    {
        return is_string( $value );
    }
    /**
     * Returns available rest methods.
     * @since 1.3.0
     * 
     * @return string
     */
    private function get_rest_methods()
    {
        return apply_filters( 'woocommerce_license_key_rest_methods', 'POST' );
    }
    /**
     * Returns endpoint response based on a validator response.
     * @since 1.3.0
     * 
     * @param \WPMVC\Response $validator
     * 
     * @return \WP_REST_Response|\WP_Error
     */
    private function get_response( Response $validator )
    {
        // Validate
        if ( $validator === null )
            return new WP_Error(
                'WPRestController::get_response',
                __( 'Invalid validator response.', 'woo-license-keys' )
            );
        // Return valid response
        $response = new WP_REST_Response( $validator->to_array() ); 
        $response->set_status( $validator->status );
        return $response;
    }
    /**
     * Parses endpoint arguments / parameters and checks if some validations have been turned off.
     * @since 1.3.1
     * 
     * @param array $args
     * 
     * @return array
     */
    private function parse_args( $args )
    {
        if ( apply_filters( 'woocommerce_license_keys_enable_sku_validation', true ) === false )
            unset( $args['sku'] );
        return $args;
    }
}