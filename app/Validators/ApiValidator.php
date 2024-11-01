<?php

namespace LicenseKeys\Validators;

use WPMVC\Response;
use LicenseKeys\Core\ValidationException;
use LicenseKeys\Interfaces\Validatable;
use LicenseKeys\Models\LicenseKey;
/**
 * Default API validator.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.5
 */
class ApiValidator implements Validatable
{
    /**
     * Function triggered only for the activation API endpoint.
     * Called after common validation has ran.
     * @since 1.5.0
     *
     * @param array           &$request
     * @param \WPMVC\Response &$response
     * @param array           $args
     */
    public function activate( &$request, Response &$response, $args )
    {
        // Breakable validations
        if ( ! $this->is_valid( 'empty_store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_license_key', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_sku', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'license_key_format', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->parse_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->get_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'sku', $request, $response, $args ) )
            throw new ValidationException();
        // Non-breakable validations
        $this->is_valid( 'license_key_expire', $request, $response, $args );
        $this->is_valid( 'license_key_limit', $request, $response, $args );
        // Customization support
        $request = apply_filters( 'woocommerce_license_keys_activate_request_preval', $request, $response );
        $response = apply_filters( 'woocommerce_license_keys_activate_response', $response, $request, $args );
    }
    /**
     * Function triggered only for the validation API endpoint.
     * Called after common validation has ran.
     * @since 1.5.0
     *
     * @param array           &$request
     * @param \WPMVC\Response &$response
     * @param array           $args
     */
    public function validate( &$request, Response &$response, $args )
    {
        // Breakable validations
        if ( ! $this->is_valid( 'empty_store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_license_key', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_sku', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_activation_id', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'license_key_format', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->parse_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->get_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'sku', $request, $response, $args ) )
            throw new ValidationException();
        // Validations
        $this->is_valid( 'license_key_expire', $request, $response, $args );
        $this->is_valid( 'activation_id', $request, $response, $args );
        // Customization support
        $request = apply_filters( 'woocommerce_license_keys_validate_request_preval', $request, $response );
        $response = apply_filters( 'woocommerce_license_keys_validate_response', $response, $request, $args );
    }
    /**
     * Function triggered only for the deactivation API endpoint.
     * Called after common validation has ran.
     * @since 1.5.0
     *
     * @param array           &$request
     * @param \WPMVC\Response &$response
     * @param array           $args
     */
    public function deactivate( &$request, Response &$response, $args )
    {
        // Breakable validations
        if ( ! $this->is_valid( 'empty_store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_license_key', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'store_code', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_sku', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'empty_activation_id', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'license_key_format', $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->parse_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->get_license_key( $request, $response, $args ) )
            throw new ValidationException();
        if ( ! $this->is_valid( 'sku', $request, $response, $args ) )
            throw new ValidationException();
        // Validations
        $this->is_valid( 'license_key_expire', $request, $response, $args );
        $this->is_valid( 'activation_id', $request, $response, $args );
        // Customization support
        $request = apply_filters( 'woocommerce_license_keys_deactivate_request_preval', $request, $response );
        $response = apply_filters( 'woocommerce_license_keys_deactivate_response', $response, $request, $args );
    }
    /**
     * Returns flag indicating if validation was successfull.
     * @since 1.5.0
     *
     * @param string $validation Validation to make.
     * @param array  &$request   Request data.
     * @param object &$response  Response.
     * @param array  &$args      Additional arguments.
     *
     * @return bool
     */
    public function is_valid( $validation, &$request, &$response, $args = [] )
    {
        $is_code = $this->error_is_code( $args );
        switch ( $validation ) {
            case 'store_code':
                if ( get_option( 'woocommerce_store_code', false ) !== $request['store_code'] ) {
                    $response->error( ( $is_code ? 1 : 'store_code' ), __( 'Invalid code.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'license_key_format':
                if ( apply_filters( 'woocommerce_license_keys_enable_format_validation', true )
                    && ( ! preg_match( '/[A-Za-z0-9]+\-[0-9]+/', $request['key_code'], $matches )
                        || $matches[0] !== $request['key_code']
                    )
                ) {
                    $response->error( ( $is_code ? 2 : 'license_key' ), __( 'Invalid license key.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'sku':
                if ( apply_filters( 'woocommerce_license_keys_enable_sku_validation', true )
                    && $request['license_key']->product->get_sku() !== $request['sku']
                ) {
                    $response->error( ( $is_code ? 3 : 'license_key' ), __( 'Invalid license key.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'empty_sku':
                if ( apply_filters( 'woocommerce_license_keys_enable_sku_validation', true )
                    && empty( $request['sku'] )
                ) {
                    $response->error( ( $is_code ? 100 : 'sku' ), __( 'Required.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'empty_license_key':
                if ( empty( $request['key_code'] ) ) {
                    $response->error( ( $is_code ? 101 : 'license_key' ), __( 'Required.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'empty_store_code':
                if ( empty( $request['store_code'] ) ) {
                    $response->error( ( $is_code ? 102 : 'store_code' ), __( 'Required.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'empty_activation_id':
                if ( empty( $request['activation_id'] ) ) {
                    $response->error( ( $is_code ? 103 : 'activation_id' ), __( 'Required.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'license_key_expire':
                if ( $request['license_key']->expire !== null
                    && time() > $request['license_key']->expire
                ) {
                    $response->error( ( $is_code ? 200 : 'license_key' ), __( 'License key has expired.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'domain':
                if ( apply_filters( 'woocommerce_license_keys_enable_domain_validation', true )
                    && get_post_meta( $request['license_key']->product->get_id(), '_desktop', true ) !== 'yes'
                    && empty( $request['domain'] )
                ) {
                    $response->error( ( $is_code ? 104 : 'domain' ), __( 'Required.', 'woo-license-keys' ) );
                    return false;
                }
                break;
            case 'license_key_limit':
                $is_desktop = get_post_meta( $request['license_key']->product->get_id(), '_desktop', true ) === 'yes';
                if ( apply_filters( 'woocommerce_license_keys_has_extended', false )
                    && $request['license_key']->limit !== null
                    && ( $is_desktop
                        || ( ! preg_match( '/localhost/', $request['domain'] )
                            || $request['license_key']->limit_dev
                        )
                    )
                    && $request['license_key']->limit_type !== null
                    && $request['license_key']->limit_reach !== null
                    && $request['license_key']->limit_count >= $request['license_key']->limit_reach
                    && ( $is_desktop
                        || ( $request['license_key']->limit_type !== 'domain'
                            || ! $request['license_key']->has_domain( $request['domain'] )
                        )
                    )
                ) {
                    switch ( $request['license_key']->limit_type ) {
                        case 'count':
                            $response->error( ( $is_code ? 201 : 'license_key' ), __( 'License key activation limit reached. Deactivate one of the registered activations to proceed.', 'woo-license-keys' ) );
                            break;
                        case 'domain':
                            $response->error( ( $is_code ? 202 : 'license_key' ), __( 'License key domain activation limit reached. Deactivate one or more of the registered activations to proceed.', 'woo-license-keys' ) );
                            break;
                    }
                    return false;
                }
                break;
            case 'activation_id':
                $is_desktop = get_post_meta( $request['license_key']->product->get_id(), '_desktop', true ) === 'yes';
                if ( apply_filters( 'woocommerce_license_keys_has_extended', false )
                    && $request['license_key']->limit !== null
                    && preg_match( '/localhost/' , $request['domain'] )
                    && !$request['license_key']->limit_dev
                    && $request['activation_id'] === 404
                ) {
                    return true;
                }
                foreach ( $request['license_key']->uses as $activation ) {
                    if ( $activation['date'] === $request['activation_id']
                        && ( ! apply_filters( 'woocommerce_license_keys_enable_domain_validation', true )
                            || $is_desktop
                            || $activation['domain'] === $request['domain']
                        )
                    ) {
                        return true;
                    }
                }
                $response->error( ( $is_code ? 203 : 'activation_id' ), __( 'Invalid activation.', 'woo-license-keys' ) );
                if ( ! $is_code )
                    $response->error( 'license_key', __( 'Invalid license key.', 'woo-license-keys' ) );
                return false;
                break;
        }
        return true;
    }
    /**
     * Returns flag indicating key parsing was successfull.
     * Parses license key into code and order_item_id.
     * @since 1.5.0
     *
     * @param arrat  &$request   Request data.
     * @param object &$response  Response.
     * @param array  &$args      Additional arguments.
     *
     * @return bool
     */
    public function parse_license_key( &$request, &$response, $args = [] )
    {
        $is_code = $this->error_is_code( $args );
        $key = apply_filters( 'woocommerce_license_keys_enable_parse_validation', true )
            ? explode( '-', $request['key_code'] )
            : [];
        if ( apply_filters( 'woocommerce_license_keys_enable_parse_validation', true )
            && count( $key ) !== 2
        ) {
            $response->error( ( $is_code ? 4 : 'license_key' ), __( 'Invalid license key.', 'woo-license-keys' ) );
            return false;
        }
        if ( count( $key ) === 2 ) {
            $request['code'] = $key[0];
            $request['order_item_id'] = intval( $key[1] );
        }
        return true;
    }
    /**
     * Returns flag indicating if license key was found.
     * Stores license key model in request.
     * @since 1.5.0
     *
     * @param arrat  &$request   Request data.
     * @param object &$response  Response.
     * @param array  &$args      Additional arguments.
     *
     * @return bool
     */
    public function get_license_key( &$request, &$response, $args = [] )
    {
        $is_code = $this->error_is_code( $args );
        $request['license_key'] = wc_find_license_key( apply_filters( 'wc_find_license_key_api_validator_request', $request ) );
        if ( $request['license_key'] === null ) {
            $response->error( ( $is_code ? 5 : 'license_key' ), __( 'Invalid license key.', 'woo-license-keys' ) );
            return false;
        }
        return true;
    }
    /**
     * Returns flag indicating if error format is code or field key.
     * @since 1.5.1
     *
     * @param array &$args Validation arguments.
     *
     * @return bool
     */
    protected function error_is_code( &$args )
    {
        return isset( $args['error_format'] ) && $args['error_format'] === 'code';
    }
}