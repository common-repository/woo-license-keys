<?php
/**
 * admin.woocommerce.order-license-keys
 * View to be displayed on an order's item.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license 10 Quality <http://www.10quality.com/>
 * @package woo-license-keys
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<style type="text/css">
.license-keys table {
    width: 100%;
    line-height: normal !important;
}
.license-keys table th,
.license-keys table td {
    padding: 5px !important;
}
.license-keys code {
    width: 100%;
    text-align: center;
    display: inline-block;
    margin: 0;
    padding: 5px 0;
    color: #3F51B5;
    font-weight: 700;
}
.license-keys .details {
    font-size: 11px;
    font-style: italic;
    color: #999;
}
</style>
<?php if ( isset( $license_keys ) && count( $license_keys ) > 0 ) : ?>
    <div class="license-keys">
        <table>
            <thead>
                <tr>
                    <th><?php _e( 'License Keys', 'woo-license-keys' ) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $license_keys as $license_key ) : ?>
                    <tr>
                        <td>
                            <code><?= $license_key->the_key ?></code>
                            <div class="details">
                                <span class="expire">
                                    <strong><?php _e( 'Expire', 'woo-license-keys' ) ?>:</strong> 
                                    <?php if ( $license_key->expire ) : ?>
                                        <?= date( get_option( 'date_format' ), $license_key->expire ) ?>
                                    <?php else : ?>
                                        <?php _e( 'Never', 'woo-license-keys' ) ?>
                                    <?php endif ?>
                                </span><!--.expire-->
                                <span class="separator"> <?php _e( '|', 'woo-license-keys' ) ?> </span>
                                <span class="limit">
                                    <strong><?php _e( 'Limit', 'woo-license-keys' ) ?>:</strong> 
                                    <?php if ( $license_key->limit ) : ?>
                                        <?= $license_key->limit_type ?>:<?= $license_key->limit_reach ?>
                                    <?php else : ?>
                                        <?php _e( 'Unlimited', 'woo-license-keys' ) ?>
                                    <?php endif ?>
                                </span><!--.limit-->
                                <span class="separator"> <?php _e( '|', 'woo-license-keys' ) ?> </span>
                                <span class="activations">
                                    <strong><?php _e( 'Activations', 'woo-license-keys' ) ?>:</strong> 
                                    <?= count( $license_key->uses ) ?>
                                </span><!--.activations-->
                                <?php do_action(
                                    'woocommerce_license_key_in_order_dashboard',
                                    apply_filters( 'woocommerce_license_key', $license_key )
                                ) ?>
                            </div><!--.details-->
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div><!--.license-keys-->
<?php endif ?>