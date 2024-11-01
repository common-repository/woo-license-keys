<?php

namespace WPMVC\Addons\Reviewer\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\OptionModel as Model;

/**
 * License key controller.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @license GPLv3
 * @version 1.0.0
 */
class Review extends Model
{
    use FindTrait;
    /**
     * Constant value that indicates that the response to the
     * review request has been to dismiss notice.
     * @since 1.0.0
     * @var int
     */
    const RESPONSE_DISMISS = 0;
    /**
     * Constant value that indicates that the response to the
     * review request has been to remind later.
     * @since 1.0.0
     * @var int
     */
    const RESPONSE_REMIND = 1;
    /**
     * Constant value that indicates that the response to the
     * review request has been that review has been done.
     * @since 1.0.0
     * @var int
     */
    const RESPONSE_DONE = 2;
    /**
     * Constant value that indicates that the response to the
     * review request has been that review is in progress.
     * @since 1.0.0
     * @var int
     */
    const RESPONSE_IN_PROGRESS = 3;
    /**
     * Model ID.
     * @var string
     * @since 1.0.0
     */
    protected $id = 'wpmvc_reviewer';
    /**
     * Aliases.
     * Mapped against custom fields functions.
     * @since 1.0.0
     * @var array
     */
    protected $aliases = [
        'bridges'   => 'field_bridges',
    ];
    /**
     * Returns bridge review data.
     * @since 1.0.0
     * 
     * @param string $namespace Bridge namespace.
     * 
     * @return array
     */
    public function get_bridge( $namespace )
    {
        return is_array( $this->bridges ) && array_key_exists( $namespace , $this->bridges )
            ? $this->bridges[$namespace]
            : ['start' => null];
    }
    /**
     * Sets bridge review data.
     * @since 1.0.0
     * 
     * @param string $namespace Bridge namespace.
     * @param array  $data
     */
    public function set_bridge( $namespace, $data )
    {
        $bridges = $this->bridges;
        if ( $bridges === null || ! is_array( $bridges ) )
            $bridges = [];
        $bridges[$namespace] = $data;
        $this->bridges = $bridges;
    }
}