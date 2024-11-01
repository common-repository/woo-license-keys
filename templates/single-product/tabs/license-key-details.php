<?php
/**
 * WooCommerce > Product (License Ket) > Tabs > License Detals.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/license-key-details.php.
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
<?php if ( $heading ) : ?>
    <h2><?= $heading ?></h2>
<?php endif ?>
<table class="<?= $tab ?> shop_attributes">
    <tbody>
        <?php if ( apply_filters( 'woocommerce_license_key_show_expire_tab', true, $license_key ) ) : ?>
            <tr>
                <th><?php _e( 'Expiration', 'woo-license-keys' ) ?></th>
                <td class="expire">
                    <?php if ( $license_key->expire ) : ?>
                        <?= sprintf(
                            __( 'Lasts for %d %s.', 'woo-license-keys' ),
                            $license_key->expire_value,
                            _n(
                                substr( $license_key->expire_interval, 0, strlen( $license_key->expire_interval ) - 1 ),
                                $license_key->expire_interval,
                                $license_key->expire_value,
                                'woo-license-keys'
                            )
                        ) ?>
                    <?php else : ?>
                        <?php _e( 'Never. (Lifetime license)', 'woo-license-keys' ) ?>
                    <?php endif ?>
                    <?php do_action( 'woocommerce_license_key_expire_details', $license_key ) ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ( apply_filters( 'woocommerce_license_key_show_limit_tab', true, $license_key ) ) : ?>
            <tr>
                <th><?php _e( 'Limit', 'woo-license-keys' ) ?></th>
                <td class="limit">
                    <?php if ( $license_key->limit ) : ?>
                        <?php if ( $license_key->limit_type === 'count' ) : ?>
                            <span class="description">
                                <?php _e( 'Limited in the number of activations. ', 'woo-license-keys' ) ?>
                            </span>
                            <span class="activations">
                                <?= sprintf(
                                    __( 'Up to %d %s.', 'woo-license-keys' ),
                                    $license_key->limit_reach,
                                    _n(
                                        'activation allowed',
                                        'activations allowed',
                                        $license_key->limit_reach,
                                        'woo-license-keys'
                                    )
                                ) ?>
                            </span>
                        <?php elseif ( $license_key->limit_type === 'domain' ) : ?>
                            <span class="description">
                                <?php _e( 'Limited in the number of domains activated. ', 'woo-license-keys' ) ?>
                            </span>
                            <span class="activations">
                                <?= sprintf(
                                    __( 'Up to %d %s.', 'woo-license-keys' ),
                                    $license_key->limit_reach,
                                    _n(
                                        'domain allowed',
                                        'domains allowed',
                                        $license_key->limit_reach,
                                        'woo-license-keys'
                                    )
                                ) ?>
                            </span>
                        <?php endif ?>
                    <?php else : ?>
                        <?php _e( 'Unlimited uses and activations.', 'woo-license-keys' ) ?>
                    <?php endif ?>
                    <?php do_action( 'woocommerce_license_key_limit_details', $license_key ) ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ( apply_filters( 'woocommerce_license_key_show_dev_tab', true, $license_key )
            && ! $license_key->is_desktop && $license_key->limit && ! $license_key->limit_dev
        ) : ?>
            <tr>
                <th><?php _e( 'Development', 'woo-license-keys' ) ?></th>
                <td class="development">
                    <?php _e( 'Developers support. Development environments (running on localhost) do not increase limit capacity, allowing this license to be used on production and development.', 'woo-license-keys' ) ?>
                </td>
            </tr>
        <?php endif ?>
        <?php do_action( 'woocommerce_license_key_tab_details_options', $license_key ) ?>
    </tbody>
</table>
<?php do_action( 'woocommerce_license_key_after_tab_details', $license_key ) ?>