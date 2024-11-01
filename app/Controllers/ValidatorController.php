<?php

namespace LicenseKeys\Controllers;

use Exception;
use WPMVC\Log;
use WPMVC\Response;
use WPMVC\MVC\Controller;
use LicenseKeys\Interfaces\Validatable;
use LicenseKeys\Core\ApiFatalException;
use LicenseKeys\Core\ValidationException;
use LicenseKeys\Validators\ApiValidator;
use LicenseKeys\Models\LicenseKey;
/**
 * API Validator controller.
 * Handles all validation service endpoints.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.6
 */
class ValidatorController extends Controller
{
    /**
     * API validator.
     * @since 1.5.0
     *
     * @var \LicenseKeys\Interfaces\Validatable
     */
    protected $validator;
    /**
     * Activation service.
     * @since 1.0.0
     * 
     * @param array $request
     * 
     * @return \WPMVC\Response
     */
    public function activate( $request )
    {
        $response = new Response();
        try {
            $this->set_validator();
            // Prepare request
            if ( ! is_array( $request ) )
                throw new Exception( 'Invalid request parameter type. Array expected.' );
            $request['ip'] = $this->get_client_ip();
            $request = apply_filters( 'woocommerce_license_keys_activate_request', $request );
            // Prepare validations
            $validation_args = apply_filters( 'woocommerce_license_keys_validation_args', [
                'error_format' => get_option( 'license_keys_response_errors_format', 'property' ),
            ], $request );
            // Validator
            $this->validator->activate( $request, $response, $validation_args );
            // Activate
            if ( $response->passes ) {
                // Activate
                if ( $request['license_key']->limit === null
                    || get_post_meta( $request['license_key']->product->get_id(), '_desktop', true ) === 'yes'
                    || ! preg_match( '/localhost/', $request['domain'] )
                    || $request['license_key']->limit_dev
                ) {
                    // Add activation
                    $uses = $request['license_key']->uses;
                    $request['license_key']->activation_id = time();
                    $uses[] = apply_filters(
                        'woocommerce_license_key_activation_meta',
                        [
                            'domain'    => $request['domain'],
                            'ip'        => $request['ip'],
                            'date'      => $request['license_key']->activation_id,
                        ],
                        $request
                    );
                    $request['license_key']->uses = $uses;
                    // Save
                    $request['license_key'] = apply_filters( 'woocommerce_license_key_before_save', $request['license_key'] );
                    $request['license_key']->save();
                    add_action( 'woocommerce_license_key_saved', $request['license_key'] );
                } else if ( preg_match( '/localhost/', $request['domain'] ) ) {
                    $request['license_key']->activation_id = 404;
                }
                do_action(
                    'woocommerce_license_key_activation_activated',
                    $request['license_key'],
                    $request['license_key']->activation_id
                );
                // Prepare response
                $response->data = $request['license_key']->to_array();
                $response->success = true;
                $response->message = __( 'License Key activated successfully.', 'woo-license-keys' );
                $response = apply_filters( 'woocommerce_license_keys_activate_success_response', $response, $request );
            }
        } catch ( ValidationException $e ) {
            $response->success = false;
            $response = apply_filters( 'woocommerce_license_keys_activate_exception_response', $response, $request, $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'activate',
                $response,
                isset( $request ) ? $request : []
            );
        } catch ( ApiFatalException $e ) {
            Log::error( $e );
            $response->message = $e->getMessage();
            $response = apply_filters( 'woocommerce_license_keys_activate_fatal_response', $response, $request, $e );
        } catch ( Exception $e ) {
            Log::error( $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'activate',
                $response,
                isset( $request ) ? $request : []
            );
        }
        return $response;
    }
    /**
     * Validation service.
     * @since 1.0.0
     * 
     * @param array $request
     * 
     * @return \WPMVC\Response
     */
    public function validate( $request )
    {
        $response = new Response();
        try {
            $this->set_validator();
            // Prepare request
            if ( ! is_array( $request ) )
                throw new Exception( 'Invalid request parameter type. Array expected.' );
            $request['ip'] = $this->get_client_ip();
            $request = apply_filters( 'woocommerce_license_keys_validate_request', $request );
            // Prepare validations
            $validation_args = apply_filters( 'woocommerce_license_keys_validation_args', [
                'error_format' => get_option( 'license_keys_response_errors_format', 'property' ),
            ], $request );
            // Validator
            $this->validator->validate( $request, $response, $validation_args );
            // Validate
            if ( $response->passes ) {
                do_action(
                    'woocommerce_license_key_activation_validated',
                    $request['license_key'],
                    $request['license_key']->activation_id
                );
                // Prepare response
                $request['license_key']->activation_id = $request['activation_id'];
                $response->data = $request['license_key']->to_array();
                $response->success = true;
                $response->message = __( 'License Key is valid.', 'woo-license-keys' );
                $response = apply_filters( 'woocommerce_license_keys_validate_success_response', $response, $request );
            }
        } catch ( ValidationException $e ) {
            $response->success = false;
            $response = apply_filters( 'woocommerce_license_keys_validate_exception_response', $response, $request, $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'validate',
                $response,
                isset( $request ) ? $request : []
            );
        } catch ( ApiFatalException $e ) {
            Log::error( $e );
            $response->message = $e->getMessage();
            $response = apply_filters( 'woocommerce_license_keys_validate_fatal_response', $response, $request, $e );
        } catch ( Exception $e ) {
            Log::error( $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'validate',
                $response,
                isset( $request ) ? $request : []
            );
        }
        return $response;
    }
    /**
     * Deactivation service.
     * @since 1.0.0
     * 
     * @param array $request
     * 
     * @return \WPMVC\Response
     */
    public function deactivate( $request )
    {
        $response = new Response();
        try {
            $this->set_validator();
            // Prepare request
            if ( ! is_array( $request ) )
                throw new Exception( 'Invalid request parameter type. Array expected.' );
            $request['ip'] = $this->get_client_ip();
            $request = apply_filters( 'woocommerce_license_keys_deactivate_request', $request );
            // Prepare validations
            $validation_args = apply_filters( 'woocommerce_license_keys_validation_args', [
                'error_format' => get_option( 'license_keys_response_errors_format', 'property' ),
            ], $request );
            // Validator
            $this->validator->deactivate( $request, $response, $validation_args );
            $request['license_key'] = apply_filters( 'woocommerce_license_key_before_save', $request['license_key'] );
            // Deactivate
            if ( $response->passes
                && ( $request['activation_id'] === 404
                    || $request['license_key']->deactivate( $request['activation_id'] )
                )
            ) {
                do_action(
                    'woocommerce_license_key_activation_deactivated',
                    $request['license_key'],
                    $request['license_key']->activation_id
                );
                add_action( 'woocommerce_license_key_saved', $request['license_key'] );
                // Prepare response
                $response->success = true;
                $response->message = __( 'Activation has been deactivated.', 'woo-license-keys' );
                $response = apply_filters( 'woocommerce_license_keys_deactivate_success_response', $response, $request );
            }
        } catch ( ValidationException $e ) {
            $response->success = false;
            $response = apply_filters( 'woocommerce_license_keys_deactivate_exception_response', $response, $request, $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'deactivate',
                $response,
                isset( $request ) ? $request : []
            );
        } catch ( ApiFatalException $e ) {
            Log::error( $e );
            $response->message = $e->getMessage();
            $response = apply_filters( 'woocommerce_license_keys_deactivate_fatal_response', $response, $request, $e );
        } catch ( Exception $e ) {
            Log::error( $e );
            do_action(
                'woocommerce_license_key_api_exception',
                $e,
                'deactivate',
                $response,
                isset( $request ) ? $request : []
            );
        }
        return $response;
    }
    /**
     * Returns flag indicating if SKU validation should be enabled or not.
     * @since 1.3.1
     * 
     * @hook woocommerce_license_keys_enable_sku_validation
     * 
     * @param bool $flag
     * 
     * @return bool
     */
    public function enable_sku_validation( $flag )
    {
        return get_option( 'license_keys_enable_sku_val', $flag ) ? true : false;
    }
    /**
     * Returns flag indicating if domain validation should be enabled or not.
     * @since 1.3.5
     * 
     * @hook woocommerce_license_keys_enable_domain_validation
     * 
     * @param bool $flag
     * 
     * @return bool
     */
    public function enable_domain_validation( $flag )
    {
        return get_option( 'license_keys_enable_domain_val', $flag ) ? true : false;
    }
    /**
     * Returns client IP retreived from request.
     * @since 1.1.2
     * 
     * @return string
     */
    private function get_client_ip()
    {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            return $_SERVER['REMOTE_ADDR'];
        } else if ( isset( $_SERVER['REMOTE_HOST'] ) ) {
            return $_SERVER['REMOTE_HOST'];
        }
        return 'UNKNOWN';
    }
    /**
     * Sets response headers based on API settings.
     * @since 1.3.0
     */
    public function set_headers()
    {
        if ( get_option( 'license_keys_override_headers' ) === 'yes' ) {
            if ( get_option( 'license_keys_header_allow_origin' ) )
                header( 'Access-Control-Allow-Origin: ' . get_option( 'license_keys_header_allow_origin' ), true);
            if ( get_option( 'license_keys_header_allow_methods' ) )
                header( 'Access-Control-Allow-Methods: ' . get_option( 'license_keys_header_allow_methods' ), true);
            if ( get_option( 'license_keys_header_allow_credentials' ) )
                header( 'Access-Control-Allow-Credentials: ' . get_option( 'license_keys_header_allow_credentials' ), true);
            do_action( 'woocommerce_license_keys_set_headers' );
        }
    }
    /**
     * Returns API successful responses with additional data
     * added based on settings.
     * @since 1.5.0
     *
     * @hook woocommerce_license_keys_activate_success_response
     * @hook woocommerce_license_keys_validate_success_response
     *
     * @param \WPMVC\Response $response
     * @param array           $request
     *
     * @return \WPMVC\Response
     */
    public function success_response( $response, $request )
    {
        if ( get_option( 'license_keys_include_user_email', false ) ) {
            $order = wc_get_order( $request['license_key']->order_id );
            $user = get_user_by( 'id', $order->get_customer_id() );
            $response->data['email'] = $user->user_email;
        }
        if ( get_option( 'license_keys_include_product_name', false ) ) {
            $response->data['name'] = $request['license_key']->product->get_name();
        }
        if ( get_option( 'license_keys_include_product_sku', false )
            && $request['license_key']->product->get_sku()
        ) {
            $response->data['sku'] = $request['license_key']->product->get_sku();
        }
        return $response;
    }
    /**
     * Returns arguments passed by through validator functions,
     * (wc_license_key_activate, wc_license_key_validate, wc_license_key_deactivate).
     * @since 1.5.1
     *
     * @hook woocommerce_license_keys_validator_via_function_args
     *
     * @throws \Exception
     *
     * @param array|\LicenseKeys\Models\LicenseKey $args          Validation arguments.
     * @param int                                  $activation_id Expected if a model is passed as argument.
     *
     * @return array
     */
    public function via_function_args( $args, $activation_id = null, $requires_activation = false )
    {
        if ( is_string( $args ) )
            $args = wc_find_license_key( $args );
        // Validations
        if ( is_object( $args ) && !$args instanceof LicenseKey )
            throw new Exception( 'Parameter must be a valid License Key.' );
        if ( is_object( $args )
            && $args instanceof LicenseKey
            && $requires_activation
            && empty( $activation_id )
        )
            throw new Exception( 'Activation ID is expected as the second parameter.' );
        // Build arguments
        if ( is_object( $args ) ) {
            $args = ['key_code' => $args->the_key ];
            if ( $requires_activation )
                $args['activation_id'] = $activation_id;
        }
        if ( array_key_exists( 'license_key', $args ) ) {
            $args['key_code'] = $args['license_key'];
            unset( $args['license_key'] );
        }
        if ( array_key_exists( 'code', $args ) ) {
            $args['key_code'] = $args['code'];
            unset( $args['code'] );
        }
        $args['store_code'] = get_option( 'woocommerce_store_code' );
        $args['_via'] = 'functions';
        return $args;
    }
    /**
     * Returns validation arguments.
     * Checks if request was generated via functions to force "property" error format.
     * @since 1.5.1
     *
     * @hook woocommerce_license_keys_validation_args
     *
     * @param array $args
     * @param array $request
     *
     * @return array
     */
    public function validation_args( $args, $request )
    {
        if ( array_key_exists( '_via', $request ) && $request['_via'] === 'functions' )
            $args['error_format'] = 'property';
        return $args;
    }
    /**
     * Sets API validator to use.
     * @since 1.5.0
     */
    private function set_validator()
    {
        $validator_class = apply_filters( 'woocommerce_license_keys_api_validator_class', 'LicenseKeys\Validators\ApiValidator' );
        if ( empty( $validator_class ) )
            throw new ApiFatalException( 'Empty License Keys API validator class.' );
        if ( !class_exists( $validator_class ) )
            throw new ApiFatalException( sprintf( 'License Keys API validator "%s" does not exist.', $validator_class ) );
        $this->validator = new $validator_class();
        if ( !$this->validator instanceof Validatable )
            throw new ApiFatalException( 'License Keys API validator must implement "LicenseKeys\Interfaces\Validatable" interface.' );
    }
    /**
     * Filters the API request that will be used as find arguments for the global function.
     * Whitelists which parameters are eligable for request.
     * @since 1.5.5
     *
     * @hook wc_find_license_key_api_validator_request
     *
     * @param array $request
     *
     * @return array
     */
    public function find_request( $request )
    {
        $whitelisted_keys = apply_filters( 'wc_find_license_key_whitelisted_keys', [
            'code',
            'order_item_id',
            'key_code'
        ] );
        return array_filter( $request, function( $key ) use( &$whitelisted_keys ) {
            return in_array( $key, $whitelisted_keys );
        }, ARRAY_FILTER_USE_KEY );
    }
}