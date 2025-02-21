<?php

namespace WPMVC\MVC\Models;

use WP_Post;
use WPMVC\MVC\Contracts\Modelable;
use WPMVC\MVC\Contracts\Findable;
use WPMVC\MVC\Contracts\Metable;
use WPMVC\MVC\Contracts\Parentable;
use WPMVC\MVC\Contracts\PostCastable;
use WPMVC\MVC\Contracts\Arrayable;
use WPMVC\MVC\Contracts\JSONable;
use WPMVC\MVC\Contracts\Stringable;
use WPMVC\MVC\Contracts\Traceable;
use WPMVC\MVC\Traits\MetaTrait;
use WPMVC\MVC\Traits\PostCastTrait;
use WPMVC\MVC\Traits\CastTrait;
use WPMVC\MVC\Traits\AliasTrait;
use WPMVC\MVC\Traits\SetterTrait;
use WPMVC\MVC\Traits\ArrayCastTrait;
use WPMVC\MVC\Traits\RelationshipTrait;

/**
 * Abstract Post Model Class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
abstract class PostModel implements Modelable, Findable, Metable, Parentable, PostCastable, Arrayable, JSONable, Stringable
{
    use MetaTrait, PostCastTrait, CastTrait, AliasTrait, SetterTrait, ArrayCastTrait, RelationshipTrait;
    /**
     * Post type.
     * @since 1.0.0
     * @var string
     */
    protected $type = 'post';
    /**
     * Default post status.
     * @since 1.0.0
     * @var string
     */
    protected $status = 'draft';
    /**
     * Posts are moved to trash when on soft delete.
     * @since 1.0.0
     * @var bool
     */
    protected $forceDelete = false;
    /**
     * Attributes in model.
     * @since 1.0.0
     * @var array
     */
    protected $attributes = [];
    /**
     * Attributes and aliases hidden from print.
     * @since 1.0.0
     * @var array
     */
    protected $hidden = [];
    /**
     * Registry options for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry = [
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
    ];
    /**
     * Labels for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_labels = [];
    /**
     * WordPress support for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_supports = [
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'comments',
    ];
    /**
     * Rewrite rules for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_rewrite = [];
    /**
     * Flag that indicates if model should generate metabox for aliases upon registration.
     * @since 1.0.2
     * @var bool
     */
    protected $registry_metabox = true;
    /**
     * Name of the controller used for registration purposes.
     * @since 1.0.2
     * @var string
     */
    protected $registry_controller;
    /**
     * Taxonomies to register during autoregistration.
     * @since 1.0.2
     * @var array
     */
    protected $registry_taxonomies = [];
    /**
     * Default constructor.
     * @since 1.0.0
     * 
     * @param int|array|\WP_Post $post
     */
    public function __construct( $post = 0 )
    {
        if ( $post ) {
            if ( is_numeric( $post ) ) {
                $this->load( $post );
            } elseif ( is_array( $post ) ) {
                $this->load_attributes( $post );
            } elseif ( is_object( $post ) && is_a( $post, 'WP_Post' ) ) {
                $this->load_wp_post( $post );
            }
        }
    }
    /**
     * Loads model from db.
     * @since 1.0.0
     *
     * @param mixed $id Rercord ID.
     */
    public function load( $id )
    {
        $this->load_attributes( get_post( $id, ARRAY_A ) );
    }
    /**
     * Loads model from db.
     * @since 2.1.10
     *
     * @param array $attributes Rercord attributes.
     */
    public function load_attributes( $attributes )
    {
        if ( !empty( $attributes ) ) {
            $this->attributes = $attributes;
            $this->load_meta();
        }
    }
    /**
     * Loads model from db.
     * @since 2.1.10
     *
     * @param \WP_Post $post Post object.
     */
    public function load_wp_post( WP_Post $post )
    {
        $this->load_attributes( (array)$post );
    }
    /**
     * Saves current model in the db.
     * @since 1.0.0
     *
     * @return bool|\WP_Error.
     */
    public function save()
    {
        if ( ! $this->is_loaded() ) return false;
        $this->fill_defaults();
        $return = isset( $this->attributes['ID'] )
            ? wp_update_post( $this->attributes, true )
            : wp_insert_post( $this->attributes, true );
        if ( !is_wp_error( $return ) && !empty( $return ) ) {
            $this->attributes['ID'] = $return;
            $this->save_meta_all();
            $this->clear_wp_cache();
        }
        return is_wp_error( $return ) ? $return : !empty( $return );
    }
    /**
     * Deletes current model in the db.
     * @since 1.0.0
     *
     * @return mixed.
     */
    public function delete()
    {
        if ( ! $this->is_loaded() ) return false;
        $error = wp_delete_post( $this->attributes['ID'], $this->forceDelete );
        return $error !== false;
    }
    /**
     * Returns flag indicating if object is loaded or not.
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_loaded()
    {
        return !empty( $this->attributes );
    }
    /**
     * Returns flag indicating if model has a trace in the database (an ID).
     * @since 2.1.11
     *
     * @param bool
     */
    public function has_trace()
    {
        return $this->ID !== null;
    }
    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property
     *
     * @return mixed
     */
    public function &__get( $property )
    {
        $value = null;
        $property = $this->get_alias_property( $property );
        if ( method_exists( $this, $property ) ) {
            $rel = $this->get_relationship( $property );
            if ( $rel )
                return $rel;
        }
        if ( preg_match( '/meta_/', $property ) ) {
            $value = $this->get_meta( preg_replace( '/meta_/', '', $property ) );
        } else if ( preg_match( '/func_/', $property ) ) {
            $function_name = preg_replace( '/func_/', '', $property );
            $value = $this->$function_name();
        } if ( is_array( $this->attributes ) && array_key_exists( $property, $this->attributes ) ) {
            return $this->attributes[$property];
        } else {
            switch ($property) {
                case 'type':
                case 'status':
                case 'meta':
                case 'aliases':
                case 'registry':
                case 'registry_taxonomies':
                case 'registry_controller':
                case 'registry_metabox':
                case 'registry_rewrite':
                case 'registry_supports':
                case 'registry_labels':
                    return $this->$property;
                case 'post_content_filtered':
                    $value = apply_filters( 'the_content', $this->attributes[$property] );
                    $value = str_replace( ']]>', ']]&gt;', $content );
            }
        }
        return $value;
    }
    /**
     * Fills default when about to create object
     * @since 1.0.0
     */
    private function fill_defaults()
    {
        if ( !array_key_exists( 'ID', $this->attributes ) ) {
            if ( !array_key_exists( 'post_type', $this->attributes ) )
                $this->attributes['post_type'] = $this->type;
            if ( !array_key_exists( 'post_status', $this->attributes ) )
                $this->attributes['post_status'] = $this->status;
        }
    }
    /**
     * Clears WP cache.
     * @since 2.1.10
     */
    private function clear_wp_cache()
    {
        if ( $this->ID )
            wp_cache_delete( $this->ID, 'posts' );
    }
}
