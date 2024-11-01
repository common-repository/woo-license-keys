<?php
/**
 * admin.notices.require-woocommerce
 * WooCommerce not activated notice.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license MIT
 * @package woo-license-keys
 * @version 1.0.9
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="notice notice-error">
    <p><?php _e( '<strong>WooCommerce License Keys:</strong> requires <strong>WooCommerce</strong> plugin to be activated in order to function.', 'woo-license-keys' ) ?></p>
</div>