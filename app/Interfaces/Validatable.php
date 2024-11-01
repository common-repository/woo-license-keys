<?php

namespace LicenseKeys\Interfaces;

use WPMVC\Response;
/**
 * Interface used to implement API request validators.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.0
 */
interface Validatable
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
    public function activate( &$request, Response &$response, $args );
    /**
     * Function triggered only for the validation API endpoint.
     * Called after common validation has ran.
     * @since 1.5.0
     *
     * @param array           &$request
     * @param \WPMVC\Response &$response
     * @param array           $args
     */
    public function validate( &$request, Response &$response, $args );
    /**
     * Function triggered only for the deactivation API endpoint.
     * Called after common validation has ran.
     * @since 1.5.0
     *
     * @param array           &$request
     * @param \WPMVC\Response &$response
     * @param array           $args
     */
    public function deactivate( &$request, Response &$response, $args );
}