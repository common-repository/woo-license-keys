<?php

namespace WPMVC\Addons\Reviewer;

use WPMVC\Addon;

/**
 * Addon class.
 * Wordpress MVC.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-reviewer
 * @license GPLv3
 * @version 1.0.0
 */
class ReviewerAddon extends Addon
{
    /**
     * Function called when user is on admin dashboard.
     * Add wordpress hooks (actions, filters) here.
     * @since 1.0.0
     */
    public function on_admin()
    {
        add_action( 'admin_notices', [&$this, 'admin_notices'] );
        add_action( 'wp_ajax_wpmvc_addon_reviewer', [&$this, 'ajax'] );
        add_action( 'admin_enqueue_scripts', [&$this, 'register_assets'] );
        // Localization
        add_action( 'init', [&$this, 'load_textdomain'], 10 );
    }
    /**
     * Evals and displays review notice.
     * @since 1.0.0
     * 
     * @hook admin_notices
     */
    public function admin_notices()
    {
        $this->mvc->call( 'ReviewController@admin_notices', $this->main );
    }
    /**
     * Process ajax action request and response.
     * @since 1.0.0
     * 
     * @hook wp_ajax_wpmvc_addon_reviewer
     */
    public function ajax()
    {
        $this->mvc->call( 'ReviewController@ajax' );
    }
    /**
     * Registers addon assets.
     * @since 1.0.0
     * 
     * @hook admin_enqueue_scripts
     */
    public function register_assets()
    {
        $this->mvc->call( 'ReviewController@assets' );
    }
    /**
     * Loads localization / text domain files.
     * @since 1.0.0
     * 
     * @hook init
     */
    public function load_textdomain()
    {
        $domain = 'wpmvc-addon-reviewer';
        load_textdomain(
            $domain,
            sprintf(
                '%s/%ss/%s/vendor/10quality/wpmvc-addon-reviewer/assets/lang/%s-%s.mo',
                WP_CONTENT_DIR,
                $this->main->config->get( 'type' ),
                $this->main->config->get( 'paths.root_folder' ),
                $domain,
                is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale()
            )
        );
    }
}