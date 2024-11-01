<?php
/**
 * admin.woocommerce.product-general-panel
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license MIT
 * @package woo-license-keys
 * @version 1.2.9
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script type="text/javascript">
(function($) { $(document).ready(function() {
    $('.options_group.pricing')
        .addClass("<?= wc_lk_show_if( apply_filters( 'woocommerce_license_key_types_show_if_pricing', apply_filters( 'woocommerce_license_key_types', [] ) ) ) ?>");
}) })(jQuery);
</script>