<?php

namespace LicenseKeys\Traits;

use TenQuality\WP\Database\QueryBuilder;

/**
 * License Key find options trait.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.4.0
 */
trait FindTrait
{
    /**
     * Returns base query builder structure,
     * with basic joins and wheres.
     * @since 1.4.0
     * 
     * @param string $query_key Query identifier.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder
     */
    public static function base_builder( $query_key )
    {
        return QueryBuilder::create( $query_key )
            ->select( 'items.order_id' )
            ->select( 'items.order_item_id' )
            ->select( 'metaproduct.meta_value as product_id' )
            ->select( 'metavariation.meta_value as variation_id' )
            ->from('posts as posts')
            ->join( 'woocommerce_order_items AS items', [
                [
                    'key_a' => 'items.order_id',
                    'key_b' => 'posts.ID',
                ]
            ] )
            ->join( 'woocommerce_order_itemmeta AS metaproduct', [
                [
                    'key_a' => 'metaproduct.order_item_id',
                    'key_b' => 'items.order_item_id',
                ],
                [
                    'key'   => 'metaproduct.meta_key',
                    'value' => '_product_id',
                ]
            ] )
            ->join( 'woocommerce_order_itemmeta AS metavariation', [
                [
                    'key_a' => 'metavariation.order_item_id',
                    'key_b' => 'items.order_item_id',
                ],
                [
                    'key'   => 'metavariation.meta_key',
                    'value' => '_variation_id',
                ]
            ], true )
            ->where( [
                'posts.post_type' => 'shop_order',
            ] );
    }
    /**
     * Returns base query builder with meta ID selection.
     * @since 1.4.0
     * 
     * @param string $query_key Query identifier.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder
     */
    public static function base_builder_with_meta( $query_key )
    {
        return self::base_builder( $query_key )
            ->select( 'meta.meta_id' )
            ->select( 'meta.meta_value' )
            ->join( 'woocommerce_order_itemmeta AS meta', [
                [
                    'key_a' => 'meta.order_item_id',
                    'key_b' => 'items.order_item_id',
                ],
                [
                    'key'   => 'meta.meta_key',
                    'value' => '_license_key',
                ]
            ] );
    }
    /**
     * Deprecated for `find_by_code`.
     * @since 1.0.0
     *
     * @param int    $item_id Order Item ID.
     * @param string $code    Small license key code.
     *
     * @return mixed|null|object
     */
    public static function findByCode( $item_id, $code )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::find_by_code()' );
        return self::find_by_code( $item_id, $code );
    }
    /**
     * Finds license key by Order item ID and small code.
     * @since 1.4.0
     *
     * @param int    $item_id Order Item ID.
     * @param string $code    Small license key code.
     *
     * @return mixed|null|object
     */
    public static function find_by_code( $item_id, $code )
    {
        $license_keys = self::base_builder( 'wookeys_find_by_code' )
            ->select( 'meta.meta_id' )
            ->select( 'meta.meta_value' )
            ->join( 'woocommerce_order_itemmeta AS meta', [
                [
                    'key_a' => 'meta.order_item_id',
                    'key_b' => 'items.order_item_id',
                ],
                [
                    'key'   => 'meta.meta_key',
                    'value' => '_license_key',
                ],
                [
                    'key'   => 'meta.meta_value',
                    'operator' => 'LIKE',
                    'value' => $code,
                    'sanitize_callback' => '_builder_esc_like_wild_wild',
                ]
            ] )
            ->where( [ 'items.order_item_id' => $item_id ] )
            ->limit( 1 )
            ->get( OBJECT, 'wc_license_key_builder_mapping' );
        $license_keys = array_filter( $license_keys, 'wc_license_key_array_filter' );
        return empty( $license_keys ) ? null : $license_keys[0];
    }
    /**
     * Finds by license key.
     * @since 1.0.0
     * 
     * @param string $key License key.
     *
     * @return mixed|null|object
     */
    public static function find( $key )
    {
        $key = explode( '-', $key );
        if ( count( $key ) === 2 ) {
            return self::find_by_code( intval( $key[1] ), $key[0] );
        }
        return;
    }
    /**
     * Deprecated for `find_by_user_index`.
     * @since 1.0.0
     *
     * @param int    $user_id User ID.
     * @param int    $item_id Order Item ID.
     * @param string $index   License key index in order item.
     *
     * @return mixed|null|object
     */
    public static function findByUserIndex( $user_id, $item_id, $index = 0 )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::find_by_user_index()' );
        return self::find_by_user_index( $user_id, $item_id, $index );
    }
    /**
     * Finds license key by user and index.
     * @since 1.4.0
     *
     * @param int    $user_id User ID.
     * @param int    $item_id Order Item ID.
     * @param string $index   License key index in order item.
     *
     * @return mixed|null|object
     */
    public static function find_by_user_index( $user_id, $item_id, $index = 0 )
    {
        $results = self::base_builder( 'wookeys_find_by_user_index' )
            ->join( 'postmeta AS metacustomer', [
                [
                    'key_a' => 'metacustomer.post_id',
                    'key_b' => 'posts.ID',
                ],
                [
                    'key'   => 'metacustomer.meta_key',
                    'value' => '_customer_user',
                ],
                [
                    'key'   => 'metacustomer.meta_value',
                    'value' => $user_id,
                ],
            ] )
            ->where( [
                'items.order_item_id' => $item_id,
                'posts.post_status' => [
                    'operator' => 'IN',
                    'value' => apply_filters( 'woocommerce_license_keys_query_order_status', ['wc-completed'] ),
                ],
            ] )
            ->get();
        foreach ( $results as $row ) {
            // Get meta
            $keys = wc_get_order_item_meta( $row->order_item_id, '_license_key', false );
            $product = wc_get_product( apply_filters(
                'woocommerce_license_key_traits_product_id',
                $row->product_id,
                $row
            ) );
            if ( ! is_array( $keys ) )
                continue;
            if ( ! array_key_exists( 0, $keys ) )
                $keys = [$keys];
            for ( $i = 0; $i < count( $keys ); ++$i ) {
                // Add index
                if ( intval( $index ) === $i ) {
                    $keys[$i]['key_index'] = $i;
                    $keys[$i]['order_id'] = $row->order_id;
                    $keys[$i]['order_item_id'] = $row->order_item_id;
                    $keys[$i]['product'] = $product;
                    // Return license key
                    $license_key = new self( $keys[$i] );
                    return $license_key->is_valid ? $license_key : null;
                }
            }
        }
        return;
    }
    /**
     * Deprecated for `find_by_user_key`.
     * @since 1.0.0
     *
     * @param int    $user_id User ID.
     * @param string $key     Key.
     *
     * @return mixed|null|object
     */
    public static function findByUserKey( $user_id, $key )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::find_by_user_key()' );
        return self::find_by_user_key( $user_id, $key );
    }
    /**
     * Finds license key by user and key.
     * @since 1.4.0
     *
     * @param int    $user_id User ID.
     * @param string $key     Key.
     *
     * @return mixed|null|object
     */
    public static function find_by_user_key( $user_id, $key )
    {
        $key = explode( '-', $key );
        if ( count( $key ) === 2 ) {
            $license_keys = self::base_builder( 'wookeys_find_by_user_key' )
                ->select( 'meta.meta_id' )
                ->select( 'meta.meta_value' )
                ->join( 'postmeta AS metacustomer', [
                    [
                        'key_a' => 'metacustomer.post_id',
                        'key_b' => 'posts.ID',
                    ],
                    [
                        'key'   => 'metacustomer.meta_key',
                        'value' => '_customer_user',
                    ],
                    [
                        'key'   => 'metacustomer.meta_value',
                        'value' => $user_id,
                    ],
                ] )
                ->join( 'woocommerce_order_itemmeta AS meta', [
                    [
                        'key_a' => 'meta.order_item_id',
                        'key_b' => 'items.order_item_id',
                    ],
                    [
                        'key'   => 'meta.meta_key',
                        'value' => '_license_key',
                    ],
                    [
                        'key'   => 'meta.meta_value',
                        'operator' => 'LIKE',
                        'value' => $key[0],
                        'sanitize_callback' => '_builder_esc_like_wild_wild',
                    ]
                ] )
                ->where( [
                    'items.order_item_id' => $key[1],
                    'posts.post_status' => [
                        'operator' => 'IN',
                        'value' => apply_filters( 'woocommerce_license_keys_query_order_status', ['wc-completed'] ),
                    ],
                ] )
                ->limit( 1 )
                ->get( OBJECT, 'wc_license_key_builder_mapping' );
            $license_keys = array_filter( $license_keys, 'wc_license_key_array_filter' );
            return empty( $license_keys ) ? null : $license_keys[0];
        }
        return null;
    }
    /**
     * Deprecated for `from_user`.
     * @since 1.0.0
     *
     * @param int $user_id User ID.
     *
     * @return array
     */
    public static function fromUser( $user_id )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::from_user()' );
        return self::from_user( $user_id );
    }
    /**
     * Returns a collection of license keys related to a user.
     * @since 1.4.0
     *
     * @param int $user_id User ID.
     *
     * @return array
     */
    public static function from_user( $user_id )
    {
        $license_keys = self::base_builder_with_meta( 'wookeys_from_user' )
            ->join( 'postmeta AS metacustomer', [
                [
                    'key_a' => 'metacustomer.post_id',
                    'key_b' => 'posts.ID',
                ],
                [
                    'key'   => 'metacustomer.meta_key',
                    'value' => '_customer_user',
                ],
                [
                    'key'   => 'metacustomer.meta_value',
                    'value' => $user_id,
                ],
            ] )
            ->where( [
                'posts.post_status' => [
                    'operator' => 'IN',
                    'value' => apply_filters( 'woocommerce_license_keys_query_order_status', ['wc-completed'] ),
                ],
            ] )
            ->group_by( 'meta.meta_id' )
            ->order_by( 'posts.post_date', 'DESC' )
            ->order_by( 'meta.meta_id' )
            ->get( OBJECT, 'wc_license_key_builder_mapping' );
        return array_filter( $license_keys, 'wc_license_key_array_filter' );
    }
    /**
     * Deprecated for `find_by_item`.
     * @since 1.0.0
     *
     * @param int $item_id Order Item ID.
     *
     * @return array
     */
    public static function findByItem( $item_id )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::find_by_item()' );
        return self::find_by_item( $item_id );
    }
    /**
     * Returns a collection of license keys related to a user.
     * @since 1.4.0
     *
     * @param int $item_id Order Item ID.
     *
     * @return array
     */
    public static function find_by_item( $item_id )
    {
        $license_keys = [];
        // Get meta
        $keys = wc_get_order_item_meta( $item_id, '_license_key', false );
        if ( ! is_array( $keys ) )
            return;
        if ( ! array_key_exists( 0, $keys ) )
            $keys = [$keys];
        for ( $i = 0; $i < count( $keys ); ++$i ) {
            // Add index
            $keys[$i]['key_index'] = $i;
            $keys[$i]['order_item_id'] = $item_id;
            $license_key = new self( $keys[$i] );
            if ( $license_key->is_valid )
                $license_keys[] = $license_key;
        }
        return $license_keys;
    }
    /**
     * Deprecated for `from_order`.
     * @since 1.0.0
     *
     * @param int $order_id Order ID.
     *
     * @return array
     */
    public static function fromOrder( $order_id )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::from_order()' );
        return self::from_order( $order_id );
    }
    /**
     * Returns a collection of license keys related to an order.
     * @since 1.4.0
     *
     * @param int $order_id Order ID.
     *
     * @return array
     */
    public static function from_order( $order_id )
    {
        $license_keys = self::base_builder_with_meta( 'wookeys_from_order' )
            ->where( ['posts.ID' => $order_id] )
            ->group_by( 'meta.meta_id' )
            ->order_by( 'posts.post_date', 'DESC' )
            ->order_by( 'meta.meta_id' )
            ->get( OBJECT, 'wc_license_key_builder_mapping' );
        return array_filter( $license_keys, 'wc_license_key_array_filter' );
    }
    /**
     * Deprecated for `from_order_item`.
     * @since 1.0.0
     *
     * @param int $order_id      Order ID.
     * @param int $order_item_id Order item ID.
     *
     * @return array
     */
    public static function fromOrderItem( $order_id, $order_item_id )
    {
        _deprecated_function( __FUNCTION__, '1.4.0', 'LicenseKey::from_order_item()' );
        return self::from_order_item( $order_id, $order_item_id );
    }
    /**
     * Returns a collection of license keys related to an order item.
     * @since 1.4.0
     *
     * @param int $order_id      Order ID.
     * @param int $order_item_id Order item ID.
     *
     * @return array
     */
    public static function from_order_item( $order_id, $order_item_id )
    {
        $license_keys = [];
        // Get meta
        $keys = wc_get_order_item_meta( $order_item_id, '_license_key', false );
        $product = wc_get_product( apply_filters(
            'woocommerce_license_key_traits_meta_product_id',
            wc_get_order_item_meta( $order_item_id, '_product_id' ),
            $order_item_id
        ) );
        if ( ! is_array( $keys ) )
            return $license_keys;
        if ( ! array_key_exists( 0, $keys ) )
            $keys = [$keys];
        for ( $i = 0; $i < count( $keys ); ++$i ) {
            // Add index
            $keys[$i]['key_index'] = $i;
            $keys[$i]['order_id'] = $order_id;
            $keys[$i]['order_item_id'] = $order_item_id;
            $keys[$i]['product'] = $product;
            $license_key = new self( $keys[$i] );
            if ( $license_key->is_valid )
                $license_keys[] = $license_key;
        }
        return $license_keys;
    }
}