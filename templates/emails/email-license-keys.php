<?php
/**
 * WooCommerce > Emails > License Keys template.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-license-keys.php.
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
<div style="margin-bottom: 40px;">
    <h2 class="woocommerce-order-license_keys__title"><?php _e( 'License Keys', 'woo-license-keys' ) ?></h2>
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <thead>
            <tr>
                <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ) ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ) ?>;"><?php esc_html_e( 'License Key', 'woo-license-keys' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $license_keys as $license_key ) : ?>
                <tr class="license-key">
                    <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                        <?php echo $license_key->product->get_name() ?>
                    </td>
                    <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                        <?php echo $license_key->the_key ?>
                    </td>
                    <?php do_action(
                        'woocommerce_license_key_in_email',
                        apply_filters( 'woocommerce_license_key', $license_key )
                    ) ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>