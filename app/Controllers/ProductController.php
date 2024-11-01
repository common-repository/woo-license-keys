<?php

namespace LicenseKeys\Controllers;

use WC_Product_License_Key;
use WPMVC\MVC\Controller;

/**
 * ProductController controller.
 * Handles all product related business logic.
 * 
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.1.0
 */
class ProductController extends Controller
{
    /**
     * Displays license key icon next to the sku when displaying the product on admin cpanel.
     * @since 1.1.0
     * 
     * @hook manage_posts_custom_column
     */
    public function display_product_icon($column, $post_id)
    {
        if ( $column !== 'sku' ) return;
        if ( get_post_meta( $post_id, '_10q_lk', true ) === WC_Product_License_Key::TYPE )
            $this->view->show( 'admin.woocommerce.license-key-product-icon' );
    }
}