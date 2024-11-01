<?php

namespace WPMVC\Addons\Reviewer\Controllers;

use Exception;
use WPMVC\Log;
use WPMVC\Request;
use WPMVC\Response;
use WPMVC\MVC\Controller;
use WPMVC\Addons\Reviewer\Models\Review;

/**
 * License key controller.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-reviewer
 * @license GPLv3
 * @version 1.0.1
 */
class ReviewController extends Controller
{
    /**
     * Registers addon assets.
     * @since 1.0.0
     * 
     * @hook admin_enqueue_scripts
     */
    public function assets()
    {
        wp_register_script(
            'wpmvc_addon_reviewer',
            addon_assets_url( 'js/jquery.wpmvc-addon-reviewer.js', __DIR__ ),
            ['jquery'],
            '1.0.0',
            true
        );
    }
    /**
     * Evals and displays review notice.
     * @since 1.0.0
     * 
     * @hook admin_notices
     * 
     * @param \WPMVC\Bridge $main
     */
    public function admin_notices( $main )
    {
        $config = $main->config->get( 'reviewer' );
        if ( ! array_key_exists( 'enabled' , $config ) || ! $config['enabled'] )
            return;
        if ( ! array_key_exists( 'interval' , $config ) || empty( $config['interval'] ) )
            $config['interval'] = 1440;
        if ( ! array_key_exists( 'url' , $config )
            || empty( $config['url'] )
            || ! filter_var( $config['url'], FILTER_VALIDATE_URL )
        )
            $config['url'] = 'https://wordpress.org/support/plugin/'
                . $main->config->get( 'localize.textdomain' )
                . '/reviews/#new-post';
        $config['img'] = apply_filters(
            'wpmvc_addon_reviewer_img_' . $main->config->get( 'namespace' ),
            addon_assets_url( 'img/stars.svg', __DIR__ )
        );
        // Prepare
        $review = Review::find();
        $data = $review->get_bridge( $main->config->get( 'namespace' ) );
        // Check saved data
        if ( empty( $data['start'] ) ) {
            $data['start'] = time();
            $review->set_bridge( $main->config->get( 'namespace' ), $data );
            $review->save();
        }
        $time = array_key_exists( 'reminder', $data ) && ! empty( $data['reminder'] ) ? $data['reminder'] : $data['start'];
        $minutes = ( time() - $time ) / 60;
        if ( $minutes > $config['interval']
            && ( ! array_key_exists( 'res', $data )
                || in_array( $data['res'] , [Review::RESPONSE_REMIND, Review::RESPONSE_IN_PROGRESS] )
            )
        ) {
            wp_enqueue_script( 'wpmvc_addon_reviewer' );
            $this->view->show( 'admin.review-notice', [
                'main'          => $main,
                'config'        => $config,
                'days'          => $this->minutes_to_days( $minutes ),
                'namespace'     => $main->config->get( 'namespace' ),
            ] );
        }
    }
    /**
     * Process ajax action request and response.
     * @since 1.0.0
     * 
     * @hook wp_ajax_wpmvc_addon_reviewer
     */
    public function ajax()
    {
        $response = new Response();
        try {
            $request = [
                'namespace' => sanitize_text_field( Request::input( 'namespace', '' ) ),
                'res'       => absint( Request::input( 'res', Review::RESPONSE_DISMISS ) ),
            ];
            if ( empty( $request['namespace'] ) )
                $response->error( 'namespace', __( 'Required' ) );
            if ( $response->passes ) {
                // Review check
                $review = Review::find();
                $data = $review->get_bridge( $request['namespace'] );
                // Save response
                $data['res'] = $request['res'];
                if ( $data['res'] === Review::RESPONSE_REMIND ) {
                    $data['reminder'] = time();
                }
                $review->set_bridge( $request['namespace'], $data );
                $review->save();
                // Response
                $response->data = [
                    'dismiss' => $data['res'] !== Review::RESPONSE_IN_PROGRESS,
                ];
                $response->success = true;
            }
        } catch ( Exception $e ) {
            Log::error( $e );
        }
        $response->json();
    }
    /**
     * Returns the amount of days in a minutes int value. (floor)
     * @since 1.0.0.
     * 
     * @param int $minutes
     * 
     * @return int
     */
    private function minutes_to_days( $minutes )
    {
        return floor( $minutes / 1440 );
    }
}