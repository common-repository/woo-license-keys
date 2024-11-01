<?php

/**
 * "license_key" WooCommerce product type class.
 *
 * @link https://www.tychesoftwares.com/how-to-add-a-new-custom-product-type-in-woocommerce/
 * @link http://jeroensormani.com/adding-a-custom-woocommerce-product-type/
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.0.0
 */
class WC_Product_License_Key extends WC_Product_Simple
{
    /**
     * Product constat TYPE.
     */
    const TYPE = 'license_key';
    /**
     * Default constructor.
     * @since 1.0.0
     *
     * @param mixed $product
     */
    public function __construct( $product )
    {
        $this->product_type = self::TYPE;
        parent::__construct( $product );
    }
    /**
     * Returns product type.
     * @since 1.0.0
     *
     * @return string
     */
    public function get_type()
    {
        return $this->product_type;
    }
}