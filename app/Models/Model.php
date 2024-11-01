<?php

namespace LicenseKeys\Models;

use Exception;
use WPMVC\MVC\Traits\AliasTrait;
use WPMVC\MVC\Traits\GetterTrait;
use WPMVC\MVC\Traits\SetterTrait;
use WPMVC\MVC\Traits\ArrayCastTrait;
use WPMVC\MVC\Traits\CastTrait;

/**
 * Base plugin model.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.1.0
 */
abstract class Model
{
    use AliasTrait, GetterTrait, SetterTrait, ArrayCastTrait, CastTrait;
    /**
     * Attributes in model.
     * @since 1.0.0
     *
     * @var array
     */
    protected $attributes = [];
    /**
     * Meta to support traits.
     * @since 1.0.0
     *
     * @var array
     */
    protected $meta = [];
    /**
     * Constructs model based the array stored in an order item.
     * @since 1.0.0
     *
     * @param array $data License data.
     *
     * @throws Exception for when array parameter is not an array
     */
    public function __construct( $data )
    {
        if ( ! is_array( $data ) )
            throw new Exception( 'Model data is invalid.' );
        $this->attributes = $data;
    }
    /**
     * Returns protected attributes.
     * @since 1.1.0
     * 
     * @return array
     */
    public function get_data()
    {
        return $this->attributes;
    }
}