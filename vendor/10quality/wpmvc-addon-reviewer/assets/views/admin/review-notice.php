<?php

use WPMVC\Addons\Reviewer\Models\Review;

/**
 * Review notice.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-reviewer
 * @license GPLv3
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="<?php echo esc_attr( $namespace ) ?>-reviewer"
    class="notice notice-warning wpmvc-addon-reviewer"
    role="reviewer"
    aria-action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=wpmvc_addon_reviewer' ) ) ?>"
    aria-namespace="<?php echo esc_attr( $namespace ) ?>"
>
    <div style="display:flex;">
        <div class="review-img">
            <img src="<?php echo esc_url( $config['img'] ) ?>"
                style="max-width:140px;max-height:140px;width:100%;margin-right:20px;"
                alt="Review stars"
            />
        </div>
        <div class="review-content">
            <?php do_action( 'wpmvc_addon_reviewer_notice_top_' . $namespace, $config ) ?>
            <h3 style="color:#f7b404;margin-bottom:10px;margin-top:20px;"><?php echo sprintf(
                __( 'Hooray! You have been using <strong>%s</strong> for over <strong>%d days</strong>!', 'wpmvc-addon-reviewer' ),
                $config['name'],
                $days
            ) ?></h3>
            <p style="margin-bottom:15px;"><?php _e(
                'Do you like it so far? Would you like to make a review and help the developers with your feedback?',
                'wpmvc-addon-reviewer'
            ) ?></p>
            <?php do_action( 'wpmvc_addon_reviewer_notice_mid_' . $namespace, $config ) ?>
            <div class="notice-actions" style="margin-bottom: 20px;">
                <a href="#" class="button button-default" style="margin:5px;"
                    role="review-dismiss" aria-response="<?php echo esc_attr( Review::RESPONSE_DISMISS ) ?>"
                    ><?php _e( '&#128127; Don\'t ask me again', 'wpmvc-addon-reviewer' ) ?></a>
                <a href="#" class="button button-default" style="margin:5px;"
                    role="review-remind" aria-response="<?php echo esc_attr( Review::RESPONSE_REMIND ) ?>"
                    ><?php _e( '&#129320; Remind me later', 'wpmvc-addon-reviewer' ) ?></a>
                <a href="#" class="button button-default" style="margin:5px;"
                    role="review-done" aria-response="<?php echo esc_attr( Review::RESPONSE_DONE ) ?>"
                    ><?php _e( '&#128512; Already did it!', 'wpmvc-addon-reviewer' ) ?></a>
                <a href="<?php echo esc_url( $config['url'] )?>" target="_blank"
                    class="button button-primary" style="margin:5px;"
                    role="review-proceed" aria-response="<?php echo esc_attr( Review::RESPONSE_IN_PROGRESS ) ?>"
                    ><?php _e( '&#128077; Yes, I would love to', 'wpmvc-addon-reviewer' ) ?></a>
            </div>
            <?php do_action( 'wpmvc_addon_reviewer_notice_bottom_' . $namespace, $config ) ?>
        </div>
    </div>
</div>