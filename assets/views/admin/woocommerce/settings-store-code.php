<?php
/**
 * admin.woocommerce.settings-store-code
 * License Ket API settings.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license MIT
 * @package woo-license-keys
 * @version 1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php do_action( 'woocommerce_license_keys_before_store_code' ) ?>
<div class="store-code">
    <p><?php _e( 'The store code acts as a KEY to validate each request done to the API. The code for this store is:', 'woo-license-keys' ) ?></p>
    <code style="font-size: large;width: 100%;display: block;text-align: center;font-weight: 800;padding: 10px;"
        ><?= get_option( 'woocommerce_store_code' ) ?></code>
</div>
<?php do_action( 'woocommerce_license_keys_after_store_code' ) ?>
<hr>