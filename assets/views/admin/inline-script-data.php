<?php
/**
 * admin.inline-script-data view.
 * WordPress MVC view.
 *
 * @author 10 Quality Studio <https://www.10quality.com/>
 * @package woo-license-keys
 * @version 1.5.2
 */
?>window.<?php echo isset( $name ) ? $name : 'license_keys' ?> = <?php echo json_encode( $data ) ?>;