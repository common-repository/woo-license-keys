<?php
/**
 * WooCommerce > Emails > License Keys (plain email) template.
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
echo __( 'License Keys:', 'woo-license-keys' ) . "\n\n";
foreach ( $license_keys as $license_key ) {
    echo sprintf( "%s\n %s\n\n", $license_key->product->get_name(), $license_key->the_key );
    do_action( 'woocommerce_license_key_in_plain_email', apply_filters( 'woocommerce_license_key', $license_key ) );
}
echo "==========\n\n";