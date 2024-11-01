<?php

namespace LicenseKeys\Models;

use LicenseKeys\Models\Model;
use LicenseKeys\Traits\FindTrait;
use LicenseKeys\Controllers\AccountController as Account;
/**
 * License Key data model.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.0
 */
class LicenseKey extends Model
{
    use FindTrait;
    /**
     * Aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = [
        'ID'                => 'func_get_id',
        'expire_date'       => 'func_get_expire_date',
        'timezone'          => 'func_get_timezone',
        'the_key'           => 'func_get_the_key',
        'url'               => 'func_get_url',
        'order_url'         => 'func_get_order_url',
        'has_expired'       => 'func_get_has_expired',
        'status'            => 'func_get_status',
        'is_valid'          => 'func_get_is_valid',
        'limit_type'        => 'func_get_limit_type',
        'limit_reach'       => 'func_get_limit_reach',
        'limit_count'       => 'func_get_limit_count',
        'limit_dev'         => 'func_get_limit_dev',
        'allow_offline'     => 'func_get_allow_offline',
        'offline_interval'  => 'func_get_offline_interval',
        'offline_value'     => 'func_get_offline_value',
    ];
    /**
     * Hidden properties for casting.
     * @since 1.0.0
     *
     * @var array
     */
    protected $hidden = [
        'is_valid',
        'code',
        'uses',
        'limit',
        'limit_type',
        'limit_reach',
        'limit_count',
        'limit_dev',
        'offline',
        'key_index',
        'order',
        'order_id',
        'order_item_id',
        'order_url',
        'product',
        'meta_id',
        'ID',
    ];
    /**
     * Returns license key URL.
     * @since 1.0.0
     * 
     * @link https://developer.wordpress.org/reference/functions/add_query_arg/
     *
     * @return string
     */
    protected function get_url()
    {
        $url = wc_get_endpoint_url( Account::VIEW_ENDPOINT );
        if ( strpos( 'http', $url ) === false ) {
            $url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
            if ( substr( $url, -1 ) !== '/' )
                $url .= '/';
            $url .= Account::VIEW_ENDPOINT . '/';
        }
        return add_query_arg(
            'key',
            $this->the_key,
            $url
        );
    }
    /**
     * Returns an order URL.
     * @since 1.0.0
     * 
     * @link https://developer.wordpress.org/reference/functions/add_query_arg/
     *
     * @return string
     */
    protected function get_order_url()
    {
        if ( $this->order_id )
            return add_query_arg(
                'view-order',
                $this->order_id,
                get_permalink( get_option( 'woocommerce_myaccount_page_id' ) )
            );
    }
    /**
     * Returns flag indicating if license has expired.
     * @since 1.0.0
     *
     * @return bool
     */
    protected function get_has_expired()
    {
        if ( $this->expire )
            return time() > $this->expire;
        return false;
    }
    /**
     * Returns license status.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_status()
    {
        return $this->has_expired ? 'inactive' : 'active';
    }
    /**
     * Returns the "license key" visible to the customer.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_the_key()
    {
        return sprintf( '%s-%d',
            $this->code,
            apply_filters( 'woocommerce_license_key_model_key_id', $this->order_item_id, $this->code )
        );
    }
    /**
     * Returns expire date.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_expire_date()
    {
        if ( $this->expire )
            return date( 'Y-m-d H:i', $this->expire );
    }
    /**
     * Returns system timezone.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_timezone()
    {
        if ( $this->expire )
            return date_default_timezone_get();
    }
    /**
     * Returns meta_id.
     * @since 1.0.0
     *
     * @return int
     */
    protected function get_id()
    {
        if ( isset( $this->attributes['meta_id'] ) )
            return $this->attributes['meta_id'];
    }
    /**
     * Returns limit type.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_limit_type()
    {
        if ( $this->limit )
            return array_keys( $this->limit )[0];
    }
    /**
     * Returns limit reach amount.
     * @since 1.0.0
     *
     * @return int
     */
    protected function get_limit_reach()
    {
        if ( $this->limit )
            return $this->limit[$this->limit_type];
    }
    /**
     * Returns current activation count based on limit type.
     * @since 1.0.0
     *
     * @return int
     */
    protected function get_limit_count()
    {
        if ( $this->limit ) {
            switch ( $this->limit_type ) {
                case 'count':
                    return count( $this->uses );
                case 'domain':
                    $domains = [];
                    for ( $i = count( $this->uses )-1; $i >= 0; --$i ) {
                        if ( ! isset( $domains[$this->uses[$i]['domain']] ) )
                            $domains[$this->uses[$i]['domain']] = 0;
                        $domains[$this->uses[$i]['domain']]++;
                    }
                    return count( $domains );
            }
        }
    }
    /**
     * Returns flag indicating if development environments are a limitation or not.
     * @since 1.0.0
     * @since 1.0.11 Fixes wc_doing_it_wrong error.
     *
     * @link https://docs.woocommerce.com/wc-apidocs/class-WC_Data.html#_get_id
     *
     * @return bool
     */
    protected function get_limit_dev()
    {
        return $this->limit
            && $this->product
            && get_post_meta( $this->product->get_id(), '_no_limit_dev', true ) === 'yes';
    }
    /**
     * Returns flag indicating if offline is allowed.
     * @since 1.0.0
     * @since 1.0.11 Fixes wc_doing_it_wrong error.
     *
     * @link https://docs.woocommerce.com/wc-apidocs/class-WC_Data.html#_get_id
     *
     * @return bool
     */
    protected function get_allow_offline()
    {
        if ( $this->offline ) {
            return get_post_meta( $this->product->get_id(), '_offline', true ) === 'yes';
        }
        return false;
    }
    /**
     * Returns offline interval.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_offline_interval()
    {
        if ( $this->offline ) {
            $offline = explode( ',', $this->offline );
            return empty( $offline[0] ) ? 'unlimited' : $offline[0];
        }
    }
    /**
     * Returns offline interval.
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_offline_value()
    {
        if ( $this->offline ) {
            $offline = explode( ',', $this->offline );
            return $offline[1];
        }
    }
    /**
     * Returns flag indicating if license key is valid, data validation.
     * @since 1.1.0
     * 
     * @return bool
     */
    protected function get_is_valid()
    {
        return $this->code && $this->order_item_id ? true : false;
    }
    /**
     * Returns model as array ready for database update.
     * @since 1.0.0
     *
     * @return array
     */
    public function to_raw()
    {
        return [
            'code'      => $this->code,
            'expire'    => $this->expire,
            'uses'      => $this->uses,
            'limit'     => $this->limit,
            'offline'   => $this->offline,
        ];
    }
    /**
     * Returns flag indicating if domain exists in activations or not.
     * @since 1.0.0
     *
     * @param string $domain Domain to check.
     *
     * @return bool
     */
    public function has_domain( $domain )
    {
        if ( $this->limit ) {
            for ( $i = count( $this->uses )-1; $i >= 0; --$i ) {
                if ( $this->uses[$i]['domain'] === $domain )
                    return true;
            }
        }
        return false;
    }
    /**
     * Saves license key in the database.
     * Returns flag indicating save status.
     * @since 1.0.0
     *
     * @global object $wpdb Wordpress Database accessor.
     *
     * @return bool
     */
    public function save()
    {
        global $wpdb;
        if ( $this->ID ) {
            $result = $wpdb->update(
                $wpdb->prefix.'woocommerce_order_itemmeta',
                ['meta_value' => maybe_serialize( $this->to_raw() )],
                ['meta_id' => $this->ID],
                ['%s'],
                ['%d']
            );
            return $result !== false && $result !== 0;
        }
        return false;
    }
    /**
     * Deactivates an activation.
     * Returns flag indicating save status.
     * @since 1.0.0
     *
     * @global object $wpdb Wordpress Database accessor.
     *
     * @param int $activation_id Activation ID or date.
     *
     * @return bool
     */
    public function deactivate( $activation_id )
    {
        foreach ( array_keys( $this->attributes['uses'] ) as $i) {
            if ( $this->attributes['uses'][$i]['date'] === intval( $activation_id ) ) {
                unset( $this->attributes['uses'][$i] );
            }
        }
        $this->attributes['uses'] = array_values( $this->attributes['uses'] );
        // Update database
        return $this->save();
    }
}