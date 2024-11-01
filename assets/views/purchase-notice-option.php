<?php
/**
 * Purchase notice on missing product options.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license 10 Quality <http://www.10quality.com/>
 * @package woo-license-keys
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="purchase-notice">
    <span class="locked"><span class="dashicons dashicons-lock"></span> <?php _e( 'Extended option.', 'woo-license-keys' ) ?></span>
    <span class="unlock-action">
        <a href="https://www.10quality.com/product/woocommerce-license-keys/"
            ><?php _e( 'Enable this feature', 'woo-license-keys' ) ?></a> 
        <span class="key dashicons dashicons-post-status"></span>
    </span>
</div>