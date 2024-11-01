<?php

namespace LicenseKeys;

use WPMVC\Bridge;
use LicenseKeys\Controllers\AccountController as Account;

/**
 * Main class.
 * Bridge between Wordpress and App.
 * Class contains declaration of hooks and filters.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license GPLv3
 * @package woo-license-keys
 * @version 1.5.5
 */
class Main extends Bridge
{
    /**
     * Declaration of public wordpress hooks.
     */
    public function init()
    {
        // General
        $this->add_filter( 'woocommerce_license_key_types', 'WooCommerceController@types', 1 );
        // Product related
        $this->add_action( 'woocommerce_license_key_add_to_cart', 'WooCommerceController@add_to_cart_template' );
        $this->add_filter( 'woocommerce_product_tabs', 'WooCommerceController@product_tabs' );
        $this->add_action( 'woocommerce_license_keys_licensed_option', 'view@purchase-notice-option' );
        $this->add_action( 'woocommerce_license_key_details_table', 'WooCommerceController@details_table', 1, 3 );
        // Order related
        $this->add_action( 'woocommerce_order_status_completed', 'WooCommerceController@order_completed' );
        $this->add_action( 'woocommerce_order_status_cancelled', 'WooCommerceController@order_cancelled' );
        $this->add_action( 'woocommerce_order_status_refunded', 'WooCommerceController@order_cancelled' );
        $this->add_action( 'woocommerce_order_status_failed', 'WooCommerceController@order_cancelled' );
        // Payment related
        $this->add_action( 'woocommerce_thankyou', 'WooCommerceController@thankyou', 10 );
        $this->add_action( 'woocommerce_email_after_order_table', 'WooCommerceController@email', 30, 4 );
        // My account related
        $this->add_action( 'init', 'AccountController@add_endpoint' );
        $this->add_filter( 'query_vars', 'AccountController@query_vars' );
        $this->add_action( 'woocommerce_license_key_enqueue', 'AccountController@view_enqueue' );
        $this->add_filter( 'woocommerce_account_menu_items', 'AccountController@menu_items' );
        $this->add_action( 'woocommerce_account_' . Account::ENDPOINT . '_endpoint', 'AccountController@endpoint' );
        $this->add_action( 'woocommerce_account_' . Account::VIEW_ENDPOINT . '_endpoint', 'AccountController@view_endpoint' );
        $this->add_filter( 'woocommerce_get_query_vars', 'AccountController@wc_query_vars' );
        $this->add_filter( 'woocommerce_endpoint_' . Account::ENDPOINT . '_title', 'AccountController@endpoint_title', 1 );
        $this->add_filter( 'woocommerce_endpoint_' . Account::VIEW_ENDPOINT . '_title', 'AccountController@view_endpoint_title', 1 );
        // Cart related
        $this->add_filter( 'woocommerce_get_item_data', 'CartController@license_key_details', 30, 2 );
        // Validations
        $this->add_filter( 'woocommerce_license_keys_enable_sku_validation', 'ValidatorController@enable_sku_validation', 1 );
        $this->add_filter( 'woocommerce_license_keys_enable_domain_validation', 'ValidatorController@enable_domain_validation', 1 );
        // API endpoints and validator
        $this->add_action( 'woocommerce_license_key_api_headers', 'ValidatorController@set_headers' );
        $this->add_filter( 'woocommerce_license_keys_activate_success_response', 'ValidatorController@success_response', 1, 2 );
        $this->add_filter( 'woocommerce_license_keys_validate_success_response', 'ValidatorController@success_response', 1, 2 );
        $this->add_filter( 'woocommerce_license_keys_validator_via_function_args', 'ValidatorController@via_function_args', 1, 3 );
        $this->add_filter( 'woocommerce_license_keys_validation_args', 'ValidatorController@validation_args', 1, 2 );
        $this->add_filter( 'wc_find_license_key_api_validator_request', 'ValidatorController@find_request' );
        // API Handler
        $handler = get_option( 'license_keys_api_handler', 'wp_ajax' );
        switch ( $handler ) {
            case 'wp_rest':
                $this->add_action( 'rest_api_init', 'WPRestController@init' );
                break;
            case 'wp_ajax':
                // - Activate
                $this->add_action( 'wp_ajax_license_key_activate', 'WPAjaxController@activate' );
                $this->add_action( 'wp_ajax_nopriv_license_key_activate', 'WPAjaxController@activate' );
                // - Validate
                $this->add_action( 'wp_ajax_license_key_validate', 'WPAjaxController@validate' );
                $this->add_action( 'wp_ajax_nopriv_license_key_validate', 'WPAjaxController@validate' );
                // - Deactivate
                $this->add_action( 'wp_ajax_license_key_deactivate', 'WPAjaxController@deactivate' );
                $this->add_action( 'wp_ajax_nopriv_license_key_deactivate', 'WPAjaxController@deactivate' );
                break;
            default:
                do_action( 'woocommerce_license_key_api_handler_' . $handler );
                break;
        }
    }
    /**
     * Declaration of admin only wordpress hooks.
     * For Wordpress admin dashboard.
     */
    public function on_admin()
    {
        // Config
        $this->add_action( 'admin_init', 'ConfigController@setup' );
        $this->add_filter( 'plugin_action_links_woo-license-keys/plugin.php', 'ConfigController@action_links' );
        $this->add_filter( 'plugin_row_meta', 'ConfigController@row_meta', 10, 2 );
        $this->add_filter( 'wpmvc_addon_reviewer_img_LicenseKeys', 'ConfigController@reviewer_img' );
        // Product admin page
        $this->add_filter( 'product_type_selector', 'WooCommerceController@product_type_selector', 15 );
        $this->add_filter( 'product_type_options', 'WooCommerceController@product_type_options', 1 );
        $this->add_filter( 'woocommerce_product_data_tabs', 'WooCommerceController@product_data_tabs', 99 );
        $this->add_action( 'woocommerce_product_data_panels', 'WooCommerceController@product_data_panels' );
        $this->add_action( 'woocommerce_update_product', 'WooCommerceController@save_product_meta' );
        $this->add_action( 'woocommerce_product_options_general_product_data', 'view@admin.woocommerce.product-general-panel', 99 );
        // Orders list admin page
        $this->add_action( 'manage_posts_custom_column', 'ProductController@display_product_icon', 2, 2 );
        $this->add_filter( 'woocommerce_shop_order_search_results', 'OrderController@admin_search', 10, 2 );
        // Order admin page
        $this->add_action( 'woocommerce_after_order_itemmeta', 'OrderController@show_license_keys', 10, 3 );
        $this->add_action( 'woocommerce_order_item_add_action_buttons', 'OrderController@show_bulk_actions' );
        // WooCommerce settings
        $this->add_filter( 'woocommerce_get_sections_advanced', 'WooCommerceController@sections_api', 99 );
        $this->add_filter( 'woocommerce_get_settings_advanced', 'WooCommerceController@settings_api', 99, 2 );
    }
}