<?php
/**
 * WooCommerce > My Account > View License Key template.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-license-key.php.
 *
 * HOWEVER, on occasion 10 Quality will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 10 Quality
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license 10 Quality <http://www.10quality.com/>
 * @package woo-license-keys
 * @version 1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php do_action( 'woocommerce_before_license_key' ) ?>
<style type="text/css">
.license-key .hero {
    background-color: #f2f2f2;
    padding: 15px;
}
.license-key .hero h2 {
    margin-top: 0;
    margin-bottom: 10px;
}
.license-key .hero .the-key {
    font-size: xx-large;
    font-weight: 800;
    margin-bottom: 0;
    width: 100%;
    display: block;
    text-align: center;
}
.license-key-details-table .license-status {
    display: inline-block;
    position: relative;
    width: 26px;
    height: 18px;
}
.license-key-details-table .limit-dev-status {
    display: inline-block;
    position: relative;
    width: 15px;
    height: 15px;
}
.license-key-details-table .license-status::after,
.license-key-details-table .limit-dev-status::after {
    font-family: Dashicons;
    speak: none;
    font-weight: 400;
    font-variant: normal;
    text-transform: none;
    margin: 0;
    text-indent: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    text-align: center;
    line-height: 1.85;
}
.license-key-details-table .license-status.active::after,
.license-key-details-table .limit-dev-status.inactive::after {
    content: '\f147';
    color: #8BC34A;
}
.license-key-details-table .license-status.inactive::after,
.license-key-details-table .limit-dev-status.active::after {
    content: '\f534';
    color: #F44336;
}
.license-key .hero .actions {
    float: right;
    font-size: smaller;
    text-transform: uppercase;
}
.license-key .hero .actions a {
    color: #2898d4;
    cursor: pointer;
}
.license-key .hero .actions a:hover {
    color: #1f5a79;
    cursor: pointer;
}
.license-key .hero .actions a:active {
    color: #2898d4;
    cursor: pointer;
}
</style>
<?php wc_print_notices() ?>
<?php if ( isset( $license_key ) ) : ?>
    <div class="license-key">
        <div class="hero">
            <div class="actions">
                <a id="copy"
                    title="<?php _e( 'Copy' ) ?>"
                    class="clipboard-copy"
                    data-clipboard-text="<?= $license_key->the_key ?>"
                >
                    <span class="dashicons dashicons-admin-page"></span><?php _e( 'Copy' ) ?>
                </a>
                <?php do_action( 'woocommerce_view_license_key_hero_actions' ) ?>
            </div>
            <h2><?php _e( 'License Key Code', 'woo-license-keys' ) ?></h2>
            <code class="the-key"><?= $license_key->the_key ?></code>
        </div>
        <h2><?php _e( 'Details', 'woo-license-keys' ) ?></h2>
        <table class="woocommerce-table shop_table license-key-details-table">
            <tbody>
                <?php if ( apply_filters( 'woocommerce_view_license_key_show_product', true, $license_key ) ) : ?>
                    <tr id="product-data">
                        <th><?php _e( 'Product', 'woocommerce' ) ?></th>
                        <td>
                            <a href="<?= get_permalink( $license_key->product->id ) ?>">
                                <?= $license_key->product->get_name() ?>
                            </a>
                            <?php do_action( 'woocommerce_view_license_key_product_td', $license_key ) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php if ( apply_filters( 'woocommerce_view_license_key_show_status', true, $license_key ) ) : ?>
                    <tr id="status-data">
                        <th><?php _e( 'Active', 'woo-license-keys' ) ?></th>
                        <td>
                            <span class="license-status <?= apply_filters( 'woocommerce_license_key_status', $license_key->status, $license_key ) ?>"></span>
                            <?php if ( $license_key->expire ) : ?>
                                <span class="expire">
                                    <?=  sprintf(
                                        __( $license_key->has_expired ? 'Expired on %s' : 'Expires on %s', 'woo-license-keys' ),
                                        date( get_option( 'date_format' ), $license_key->expire )
                                    ) ?>
                                </span>
                            <?php endif ?>
                            <?php do_action( 'woocommerce_view_license_key_status_td', $license_key ) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php if ( $license_key->limit ) : ?>
                    <tr id="limit-data">
                        <th><?php _e( 'Limit', 'woo-license-keys' ) ?></th>
                        <td>
                            <?php if ( $license_key->limit_type === 'count' ) : ?>
                                <?php _e( 'Limit the amount of activations.', 'woo-license-keys' ) ?>
                            <?php elseif ( $license_key->limit_type === 'domain' ) : ?>
                                <?php _e( 'Limit the amount of domains.', 'woo-license-keys' ) ?>
                            <?php else : ?>
                                <?php do_action( 'woocommerce_license_key_custom_limit_display', $license_key ) ?>
                            <?php endif ?>
                            <?php if ( $license_key->limit_reach && $license_key->limit_count ) : ?>
                                <i class="status"> 
                                    <?= sprintf(
                                        __( 'Current status: %d of %d', 'woo-license-keys' ),
                                        $license_key->limit_count,
                                        $license_key->limit_reach
                                    ) ?>
                                </i>
                            <?php endif ?>
                            <?php do_action( 'woocommerce_view_license_key_limit_td', $license_key ) ?>
                        </td>
                    </tr>
                    <?php if ( ! $is_desktop ) : ?>
                        <tr id="dev-data">
                            <th><?php _e( 'Development', 'woo-license-keys' ) ?></th>
                            <td>
                                <small>
                                    <?php if ( $license_key->limit_dev ) : ?>
                                        <span class="limit-dev-status active"></span>
                                        <?php _e( 'Development environments (http://localhost) will count as valid activations and will increase limit capacity.', 'woo-license-keys' ) ?>
                                    <?php else : ?>
                                        <span class="limit-dev-status inactive"></span>
                                        <?php _e( 'Development environments (http://localhost) will not increase the limit capacity.', 'woo-license-keys' ) ?>
                                    <?php endif ?>
                                </small>
                                <?php do_action( 'woocommerce_view_license_key_dev_td', $license_key ) ?>
                            </td>
                        </tr>
                    <?php endif ?>
                <?php endif ?>
                <?php if ( apply_filters( 'woocommerce_view_license_key_show_activation_stats', true, $license_key ) ) : ?>
                    <tr id="activation-stats-data">
                        <th><?php _e( 'Activations', 'woo-license-keys' ) ?></th>
                        <td>
                            <?php if ( $license_key->limit && array_key_exists( 'count', $license_key->limit ) ) : ?>
                                <?= sprintf( __( '%d of %d', 'woo-license-keys' ), count( $license_key->uses ), $license_key->limit['count'] ) ?>
                            <?php else : ?>
                                <?= count( $license_key->uses ) ?>
                            <?php endif ?>
                            <?php do_action( 'woocommerce_view_license_key_activation_stats_td', $license_key ) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php do_action( 'woocommerce_license_key_myaccount_details', $license_key ) ?>
            </tbody>
        </table>
        <?php do_action( 'woocommerce_before_license_key_activations', $license_key ) ?>
        <?php if ( apply_filters( 'woocommerce_view_license_key_show_activations', true, $license_key ) ) : ?>
            <h2><?php _e( 'Activations', 'woo-license-keys' ) ?></h2>
            <?php if ( count( $license_key->uses ) > 0) : ?>
                <small>
                    <table class="woocommerce-table shop_table  activations">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID' ) ?></th>
                                <th><?php _e( 'Activation date', 'woo-license-keys' ) ?></th>
                                <th>
                                    <?php _e( 'IP', 'woo-license-keys' ) ?>
                                    <?php if ( ! $is_desktop ) : ?> | <?php _e( 'Domain', 'woo-license-keys' ) ?><?php endif ?>
                                 </th>
                                <th><?php _e( 'Actions', 'woo-license-keys' ) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $license_key->uses as $activation ) : ?>
                                <tr>
                                    <td><?= $activation['date'] ?></td>
                                    <td><?= date( get_option( 'date_format' ), $activation['date'] ) ?></td>
                                    <td>
                                        <?php if ( isset( $activation['ip'] ) ) : ?>
                                            <?= $activation['ip'] ?>
                                        <?php endif ?>
                                        <?php if ( ! $is_desktop ) : ?> | <?= $activation['domain'] ?><?php endif ?>
                                    </td>
                                    <td>
                                        <a href="<?= $license_key->url ?>&deactivate=<?= $activation['date'] ?>"
                                            class="woocommerce-button button deactivate"
                                        ><?php _e( 'Deactivate', 'woo-license-keys' ) ?></a>
                                    </td>
                                    <?php do_action(
                                        'woocommerce_license_key_activation_in_myaccount',
                                        apply_filters( 'woocommerce_license_key_activation', $activation, $license_key )
                                    ) ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table><!--.activations-->
                </small>
            <?php else : ?>
                <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
                    <?php _e( 'No activations for this license key yet.', 'woo-license-keys' ) ?>
                </div>
            <?php endif ?>
        <?php endif ?>
    </div><!--.license-key-->
<?php endif ?>
<?php do_action( 'woocommerce_after_license_key' ) ?>