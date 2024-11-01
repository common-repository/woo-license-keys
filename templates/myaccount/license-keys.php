<?php
/**
 * WooCommerce > My Account > License Keys template.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/license-keys.php.
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
 * @version 1.2.9
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php do_action( 'woocommerce_before_license_keys' ) ?>
<style type="text/css">
.account-license-keys-table {
    font-size: .85em;
}
.account-license-keys-table th {
    padding: 4px 8px;
    vertical-align: middle;
}
.account-license-keys-table .expire {
    font-size: small;
    font-weight: 600;
}
.account-license-keys-table .license-status {
    display: inline-block;
    position: relative;
    width: 26px;
    height: 26px;
}
.account-license-keys-table .license-status::after {
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
.account-license-keys-table .license-status.active::after {
    content: '\f147';
    color: #8BC34A;
}
.account-license-keys-table .license-status.inactive::after {
    content: '\f534';
    color: #F44336;
}
</style>
<?php if ( isset( $license_keys ) && count( $license_keys ) > 0 ) : ?>
    <table class="woocommerce-license-keys-table shop_table shop_table_responsive account-license-keys-table">
        <thead>
            <tr>
                <th><?php _e( 'Order', 'woocommerce' ) ?></th>
                <th><?php _e( 'Product', 'woocommerce' ) ?></th>
                <th><?php _e( 'Active', 'woo-license-keys' ) ?></th>
                <th><?php _e( 'Activations', 'woo-license-keys' ) ?></th>
                <th><?php _e( 'Actions', 'woocommerce' ) ?></th>
                <?php do_action( 'woocommerce_myaccount_license_keys_table_headers' ) ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $license_keys as $license_key ) : ?>
                <tr>
                    <td>
                        <a href="<?= $license_key->order_url ?>">
                            <?php _e( '#', 'woo-license-keys' ) ?><?= $license_key->order_id ?>
                        </a>
                        <?php do_action( 'woocommerce_myaccount_license_keys_order_td', $license_key ) ?>
                    </td>
                    <td>
                        <a href="<?= get_permalink( $license_key->product->id ) ?>">
                            <?= $license_key->product->get_name() ?>
                        </a>
                        <?php do_action( 'woocommerce_myaccount_license_keys_product_td', $license_key ) ?>
                    </td>
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
                        <?php do_action( 'woocommerce_myaccount_license_keys_expire_td', $license_key ) ?>
                    </td>
                    <td>
                        <?php if ( $license_key->limit && array_key_exists( 'count', $license_key->limit ) ) : ?>
                            <?= sprintf( __( '%d of %d', 'woo-license-keys' ), count( $license_key->uses ), $license_key->limit['count'] ) ?>
                        <?php else : ?>
                            <?= count( $license_key->uses ) ?>
                        <?php endif ?>
                        <?php do_action( 'woocommerce_myaccount_license_keys_activations_td', $license_key ) ?>
                    </td>
                    <td>
                        <a href="<?= $license_key->url ?>"
                            class="woocommerce-button button view"
                        ><?php _e( 'View', 'woocommerce' ) ?></a>
                        <?php do_action( 'woocommerce_myaccount_license_key_actions', $license_key ) ?>
                    </td>
                    <?php do_action(
                        'woocommerce_license_key_in_myaccount_list',
                        apply_filters( 'woocommerce_license_key', $license_key )
                    ) ?>
                    <?php do_action( 'woocommerce_myaccount_license_keys_table_data', $license_key ) ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="woocommerce-Button button"
            href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) ?>"
        >
            <?php _e( 'Go shop', 'woocommerce' ) ?>
        </a>
        <?php _e( 'No license keys purchased yet.', 'woo-license-keys' ) ?>
    </div>
<?php endif ?>
<?php do_action( 'woocommerce_after_license_keys' ) ?>