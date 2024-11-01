<?php
/**
 * Purchase notice on missing builk actions.
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
.bulk-manage-license-keys::before {
    content: "\f160";
    font-family: Dashicons;
    speak: none;
    font-weight: 400;
    font-variant: normal;
    text-transform: none;
}
</style>
<a type="button"
    class="button bulk-manage-license-keys"
    href="https://www.10quality.com/product/woocommerce-license-keys/"
>
    <?php esc_html_e( 'Manage license keys', 'woo-license-keys' ); ?>
</a>