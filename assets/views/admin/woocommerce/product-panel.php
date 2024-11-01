<?php
/**
 * admin.woocommerce.product-panel
 * View to be displayed as product panel when "License Key" product type is created.
 *
 * @link http://hookr.io/functions/woocommerce_wp_select/
 * @link http://hookr.io/functions/woocommerce_wp_text_input/
 * @link http://hookr.io/functions/woocommerce_wp_checkbox/
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license MIT
 * @package woo-license-keys
 * @version 1.5.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
global $product_object
?>
<?php do_action( 'woocommerce_license_key_before_options_panel' ) ?>
<style type="text/css">
.purchase-notice {
    margin: 10px 0 0 10px;
}
.purchase-notice span.key {
    color: #FFB300;
}
</style>
<div id="license_key_product_data"
    class="panel woocommerce_options_panel"
>
    <div class="options_group <?= wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_sku', $types ) ) ?>">
        <?php woocommerce_wp_text_input( [
            'id'            => '_sku',
            'label'         => '<abbr title="' . esc_attr__( 'Stock Keeping Unit', 'woocommerce' ) . '">' . esc_html__( 'SKU', 'woocommerce' ) . '</abbr>',
            'desc_tip'      => true,
            'description'   => __( 'SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce' ),
        ] ) ?>
        <?php woocommerce_wp_checkbox( [
            'id'            => '_sold_individually_override',
            'value'         => $product_object->get_sold_individually( 'edit' ) ? 'yes' : 'no',
            'label'         => __( 'Sold individually', 'woocommerce' ),
            'description'   => __( 'Enable this to only allow one of this item to be bought in a single order', 'woocommerce' ),
        ] ) ?>
        <?php do_action( 'woocommerce_product_options_sold_individually' ) ?>
        <?php do_action( 'woocommerce_license_key_options_panel_sku' ) ?>
    </div><!--.options_group-->
    <div class="options_group desktop <?= wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_desktop', $types ) ) ?>">
        <?php woocommerce_wp_checkbox( [
            'id'            => '_desktop',
            'label'         => __( 'Desktop software', 'woo-license-keys' ),
            'desc_tip'      => true,
            'description'   => __( 'Check this option if the license key is going to be used by desktop software, making the API to bypass any domain validation.', 'woo-license-keys' ),
        ] ) ?>
        <?php do_action( 'woocommerce_license_key_options_panel_desktop' ) ?>
    </div><!--.options_group-->
    <div class="options_group expirate <?= wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_expire', $types ) ) ?>">
        <?php woocommerce_wp_checkbox( [
            'id'            => '_expire',
            'label'         => __( 'Expires', 'woo-license-keys' ),
            'desc_tip'      => true,
            'description'   => __( 'Leave unchecked if you want this to be a lifetime license, otherwise check this option.', 'woo-license-keys' ),
        ] ) ?>
        <?php woocommerce_wp_select( [
            'id'            => '_expire_interval',
            'label'         => __( 'Expire interval', 'woo-license-keys' ),
            'options'       => apply_filters( 'woocommerce_license_key_expire_interval_options', [
                                'days'      => __( 'Days', 'woo-license-keys' ),
                                'months'    => __( 'Months', 'woo-license-keys' ),
                                'years'     => __( 'Years', 'woo-license-keys' ),
                            ] ),
            'desc_tip'      => true,
            'description'   => __( 'Interval in time in which the license will expire.', 'woo-license-keys' ),
        ] ) ?>
        <?php woocommerce_wp_text_input( [
            'id'            => '_expire_value',
            'label'         => __( 'Expire value', 'woo-license-keys' ),
            'desc_tip'      => true,
            'description'   => __( 'Value in which the license will expire, this is heavily linked to the interval.', 'woo-license-keys' ),
        ] ) ?>
        <?php do_action( 'woocommerce_license_key_options_panel_expiration' ) ?>
    </div><!--.options_group-->
    <?php if ( ! apply_filters( 'woocommerce_license_keys_has_extended', false ) ) : ?>
        <div class="options_group _expire_notifications_group <?= $show_if ?>">
            <?php do_action( 'woocommerce_license_keys_licensed_option' ) ?>
            <p class="form-field">
                <label for="_missing_offline"><?php _e( 'Customer notifications', 'woo-license-keys' )?></label>
                <button class="button" disabled="disabled"><?php _e( 'Add notification', 'woo-license-keys' ) ?></button>
            </p>
        </div>
        <div class="options_group limit <?= $show_if ?>">
            <?php do_action( 'woocommerce_license_keys_licensed_option' ) ?>
            <p class="form-field">
                <label for="_missing_limit"><?php _e( 'Limit', 'woo-license-keys' )?></label>
                <select class="select short" disabled="disabled">
                    <option><?php _e( 'None', 'woo-license-keys' )?></option>
                </select>
            </p>
        </div>
        <div class="options_group offline <?= $show_if ?>">
            <?php do_action( 'woocommerce_license_keys_licensed_option' ) ?>
            <p class="form-field">
                <label for="_missing_offline"><?php _e( 'Offline', 'woo-license-keys' )?></label>
                <input class="checkbox" type="checkbox" disabled="disabled"/>
            </p>
        </div>
        <div class="options_group keygen <?= $show_if ?>">
            <?php do_action( 'woocommerce_license_keys_licensed_option' ) ?>
            <p class="form-field">
                <label for="_missing_no_keygen"><?php _e( 'Do not auto-generate codes', 'woo-license-keys' )?></label>
                <input class="checkbox" type="checkbox" disabled="disabled"/>
            </p>
        </div>
    <?php endif ?>
    <?php do_action( 'woocommerce_license_key_options_panel', $types ) ?>
</div><!--#license_key_product_data-->
<?php do_action( 'woocommerce_license_key_after_options_panel' ) ?>