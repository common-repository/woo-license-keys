<?php

namespace LicenseKeys\Controllers;

use WC_Product_License_Key;
use WPMVC\MVC\Controller;

/**
 * CartController controller.
 * Handles all cart related business logic.
 * 
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.2.4
 */
class CartController extends Controller
{
    /**
     * Displays license key details in cart / checkout pages.
     * @since 1.1.0
     * 
     * @hook woocommerce_get_item_data
     * 
     * @global \LicenseKeys\Main $licensekeys Plugin's main bridge.
     *
     * @param array $item_data Item data to displat in cart.
     * @param array $cart_item Cart item.
     */
    public function license_key_details( $item_data, $cart_item )
    {
        if ( $cart_item['data']->get_type() === WC_Product_License_Key::TYPE ) {
            global $licensekeys;
            $license_key = $licensekeys->{'_c_return_WooCommerceController@get_default_license_key'}( $cart_item['data'] );
            if ( $license_key->expire )
                $item_data[] = array(
                    'key'       => __( 'Expiration', 'woo-license-keys' ),
                    'display'   => wc_clean( sprintf(
                                    __( 'Lasts for %d %s.', 'woo-license-keys' ),
                                    $license_key->expire_value,
                                    _n(
                                        substr( $license_key->expire_interval, 0, strlen( $license_key->expire_interval ) - 1 ),
                                        $license_key->expire_interval,
                                        $license_key->expire_value,
                                        'woo-license-keys'
                                    )
                                ) ),
                );
            $item_data = apply_filters( 'woocommerce_license_key_cart_details', $item_data, $license_key );
        }
        return $item_data;
    }
}