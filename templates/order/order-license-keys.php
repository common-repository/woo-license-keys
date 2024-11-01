<?php
/**
 * WooCommerce > Order > License Keys template.
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
 * @version 1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php if ( $license_keys ) : ?>
    <section class="woocommerce-order-license-keys">
        <h2 class="woocommerce-order-license-keys__title"><?php esc_html_e( 'License Keys', 'woo-license-keys' ) ?></h2>
        <table class="woocommerce-table woocommerce-table--order-license-keys shop_table shop_table_responsive order_details">
            <thead>
                <tr>
                    <th class="product-column">
                        <span class="nobr"><?= esc_html_e( 'Product', 'woocommerce' ) ?></span>
                    </th>
                    <th class="license-key-column">
                        <span class="nobr"><?= esc_html_e( 'License Key', 'woo-license-keys' ) ?></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $license_keys as $license_key ) : ?>
                    <tr>
                        <td class="product-column" data-title="<?= esc_html_e( 'Product', 'woocommerce' ) ?> ?>">
                            <?= $license_key->product->get_name() ?>
                        </td>
                        <td class="license-key-column" data-title="<?= esc_html_e( 'License Key', 'woo-license-keys' ) ?> ?>">
                            <?= $license_key->the_key ?>
                        </td>
                        <?php do_action(
                            'woocommerce_license_key_in_order',
                            apply_filters( 'woocommerce_license_key', $license_key )
                        ) ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
<?php endif ?>