<?php
/**
 * Global functions for addons.
 * Wordpress MVC.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Addons
 * @version 1.0.1
 */

if ( ! function_exists( 'addon_assets_url' ) ) {
    /**
     * Returns url of an asset located in the addon.
     * Addon package name should not be prefixed with "addon" or "assets".
     * @since 1.0.0
     *
     * @link https://codex.wordpress.org/Function_Reference/home_url
     * @link https://codex.wordpress.org/Function_Reference/network_home_url
     * @param string $path       Asset relative path.
     * @param string $file       File location path.
     * @param string $scheme     Scheme to give the home url context. Currently 'http','https'.
     * @param bool   $is_network Flag that indicates if base url should be retrieved from a network setup.
     *
     * @return string URL
     */
    function addon_assets_url( $path, $file, $scheme = null, $is_network = false )
    {
        // Preparation
        $route = preg_replace( '/\\\\/', '/', $file );
        $url = apply_filters(
            'asset_base_url',
            rtrim( $is_network ? network_home_url( '/', $scheme ) : home_url( '/', $scheme ), '/' )
        );
        // Clean base path
        $route = preg_replace( '/.+?(?=wp-content)/', '', $route );
        // Clean project relative path
        $route = preg_replace( '/\/addon[\/\\\\A-Za-z0-9\.\-]+/', '', $route );
        $route = preg_replace( '/\/assets[\/\\\\A-Za-z0-9\.\-]+/', '', $route );
        $route = apply_filters( 'app_route', $route );
        return $url . '/' . apply_filters( 'app_route_addon', $route ) . '/assets/' . $path;
    }
}