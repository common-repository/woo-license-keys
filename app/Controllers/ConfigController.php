<?php

namespace LicenseKeys\Controllers;

use Keygen\Keygen;
use WPMVC\MVC\Controller;

/**
 * Configuration hooks.
 * Handles all configuration related business logic.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.4.1
 */
class ConfigController extends Controller
{
    /**
     * Setups / creates store code if unavailable.
     * Plugin Activation.
     * @since 1.0.0
     */
    public function setup()
    {
        // Add store code
        if ( get_option( 'woocommerce_store_code' ) === false ) {
            update_option( 'woocommerce_store_code', Keygen::alphanum( 15 )->generate() );
        }
    }
    /**
     * Returns plugin action links.
     * @since 1.0.0
     * 
     * @hook plugin_action_links_[...]
     *
     * @param array $current Current links.
     *
     * @return array
     */
    public function action_links( $current = [] )
    {
        $links = [];
        $links[] = '<a href="'. esc_url( admin_url( 'admin.php?page=wc-settings&tab=advanced&section=license_keys' ) ).'">'
            .__( 'API Settings', 'woo-license-keys' )
            .'</a>';
        foreach ($current as $link) {
            $links[] = $link;
        }
        return $links;
    }
    /**
     * Returns list of row meta links to display in plugins page.
     * @since 1.1.0
     * 
     * @hook plugin_row_meta
     * 
     * @param array  $classes
     * @param string $file
     * 
     * @return array
     */
    public function row_meta( $meta, $file )
    {
        if ( $file === 'woo-license-keys/plugin.php' ) {
            $meta['docs'] = '<a href="' . esc_url( 'https://www.10quality.com/docs/woocommerce-license-keys/' ) . '" aria-label="' . esc_attr__( 'Docs', 'woocommerce' ) . '" target="_blank">' . esc_html__( 'Docs', 'woocommerce' ) . '</a>';
            if ( ! apply_filters( 'woocommerce_license_keys_has_extended', false ) )
                $meta['extension'] = '<a href="' . esc_url( 'https://www.10quality.com/product/woocommerce-license-keys/' ) . '" aria-label="' . esc_attr__( 'Premium features', 'woo-license-keys' ) . '" target="_blank">' . esc_html__( 'Premium features', 'woo-license-keys' ) . '</a>';
        }
        return $meta;
    }
    /**
     * Returns image url for reviewer addon.
     * @since 1.4.1
     *
     * @hook wpmvc_addon_reviewer_img_LicenseKeys
     *
     * @return string
     */
    public function reviewer_img()
    {
        return assets_url( 'svgs/woo-license-keys-reviewer-logo.svg', __DIR__ );
    }
}