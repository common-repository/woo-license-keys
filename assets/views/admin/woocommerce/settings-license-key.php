<?php
/**
 * admin.woocommerce.settings-license-key
 * License Ket API settings.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10 Quality <http://www.10quality.com/>
 * @license MIT
 * @package woo-license-keys
 * @version 1.3.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<p>
    <?= sprintf(
        __( 'Visit the official <a href="%s">documentation</a>.', 'woo-license-keys' ),
        'https://www.10quality.com/docs/woocommerce-license-keys/'
    ) ?>
</p>
<hr>
<div class="implementation">
    <h3><?php _e( 'Implementation', 'woo-license-keys' ) ?></h3>
    <p><?php _e( 'Ways to implement the API:', 'woo-license-keys' ) ?></p>
    <ol>
        <li>
            <div><strong><?php _e( 'PHP client', 'woo-license-keys' ) ?></strong></div>
            <div>
                <?= sprintf(
                    __( 'We offer a PHP client library (see on %s) that can be used to integrate the API with any PHP project.', 'woo-license-keys' ),
                    '<a href="https://github.com/10quality/license-keys-php-client">Github</a>'
                ) ?>
                (<a href="https://github.com/10quality/license-keys-php-client/wiki/Tutorial:-Quick-Script"><?php _e( 'tutorial' ) ?></a>)
            </div>
        </li>
        <li>
            <div><strong><?php _e( 'Wordpress MVC addon', 'woo-license-keys' ) ?></strong></div>
            <div>
                <?= sprintf(
                    __( 'We offer an <i>add-on</i> for %s. This addon will integrate the API easily with your WordPress project.', 'woo-license-keys' ),
                    '<a href="https://github.com/10quality/wpmvc-addon-license-key">Wordpress MVC Framework</a>'
                ) ?>
            </div>
        </li>
        <li>
            <div><strong><?php _e( 'Documentation', 'woo-license-keys' ) ?></strong></div>
            <div>
                <?php _e( 'If working with something other than PHP. Follow the documentation and call the API using standard HTTP requests.', 'woo-license-keys' ) ?>
            </div>
            <h4><?php _e( 'Your API\'s base url', 'woo-license-keys' ) ?></h4>
            <ul>
                <li><strong><?php _e( 'WP Ajax', 'woo-license-keys' ) ?></strong>
                    <pre style="text-align: left;color: #F44336;">
                        <?= admin_url( '/admin-ajax.php' ) ?>
                    </pre>
                </li>
                <li><strong><?php _e( 'Wordpress REST API', 'woo-license-keys' ) ?></strong>
                    <pre style="text-align: left;color: #F44336;">
                        <?= home_url( '/wp-json/woo-license-keys/v1' ) ?>
                    </pre>
                </li>
                <?php do_action( 'woocommerce_license_keys_after_settings_api_urls' ) ?>
            </ul>
        </li>
    </ol>
</div>
<?php do_action( 'woocommerce_license_keys_after_settings' ) ?>