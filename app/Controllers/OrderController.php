<?php

namespace LicenseKeys\Controllers;

use Exception;
use WC_Product_License_Key;
use WPMVC\Log;
use WPMVC\MVC\Controller;
use LicenseKeys\Models\LicenseKey;

/**
 * Handles all order related business logic.
 *
 * @see https://gist.github.com/claudiosanches/a79f4e3992ae96cb821d3b357834a005
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.4.0
 */
class OrderController extends Controller
{
    /**
     * Adds license keys list to orders items.
     * @since 1.0.0
     * 
     * @hook woocommerce_after_order_itemmeta
     *
     * @param int        $item_id Order item ID.
     * @param object     $item    Order item.
     * @param WC_Prodcut $product Product.
     */
    public function show_license_keys( $item_id, $item, $product )
    {
        $generate = wc_get_order_item_meta( $item->get_id(), '_license_key_generate', true );
        if ( wc_is_license_key( $product ) && ( $generate === null || $generate === '' || $generate === false ) ) {
            $license_keys = [];
            try {
                $license_keys = LicenseKey::find_by_item( $item_id );
                for ( $i = count( $license_keys )-1;  $i >= 0 ;  --$i ) {
                    $license_keys[$i] = apply_filters( 'woocommerce_license_key', $license_keys[$i] );
                    $license_keys[$i]->product = $product;
                    $license_keys[$i]->order_id = $item->get_order_id();
                }
            } catch ( Exception $e ) {
                Log::error( $e );
            }
            $this->view->show( 'admin.woocommerce.order-license-keys', ['license_keys' => $license_keys] );
        }
    }
    /**
     * Adds manage button at the button.
     * @since 1.0.0
     * 
     * @hook woocommerce_admin_order_item_bulk_actions
     *
     * @param object $order Order.
     */
    public function show_bulk_actions( $order )
    {
        if ( ! apply_filters( 'woocommerce_license_keys_has_extended', false ) ) 
            $this->view->show( 'purchase-order-builk-actions' );
    }
    /**
     * Returns order ids found based on search terms.
     * @since 1.1.4
     * 
     * @hook woocommerce_shop_order_search_results
     * 
     * @global object $wpdb Database accessor.
     * 
     * @param array  $order_ids Current results.
     * @param string $term      Search term.
     * 
     * @return array
     */
    public function admin_search( $order_ids, $term )
    {
        global $wpdb;
        if ( $term && preg_match( '/[A-Za-z0-9]+\-[0-9]+/', $term, $match ) ) {
            $parts = explode( '-', $match[0] );
            return array_unique(
                array_merge(
                    $order_ids,
                    $wpdb->get_col(
                        preg_replace( '/{w}/', '%', $wpdb->prepare(
                            'SELECT DISTINCT items.order_id
                            FROM ' . $wpdb->posts . ' AS posts
                            INNER JOIN ' . $wpdb->prefix . 'woocommerce_order_items AS items
                                ON items.order_id = posts.ID
                                AND items.order_item_id = %d
                            INNER JOIN ' . $wpdb->prefix . 'woocommerce_order_itemmeta AS meta
                                ON meta.order_item_id = items.order_item_id
                                AND meta.meta_key = %s
                                AND meta.meta_value LIKE %s
                            WHERE posts.post_type = %s',
                            $parts[1],
                            '_license_key',
                            '{w}"'.$wpdb->esc_like( $parts[0] ).'"{w}',
                            'shop_order'
                        ) )
                    )
                )
            );
        }
        return $order_ids;
    }
}