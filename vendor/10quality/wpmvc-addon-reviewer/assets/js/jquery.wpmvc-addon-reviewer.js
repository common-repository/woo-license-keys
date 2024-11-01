/*!
 * WPMVC Addon reviewer script.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-reviewer
 * @license GPLv3
 * @version 1.0.0
 */
( function( $ ) { $( document ).ready( function() {
    /**
     * WPMVC reviewer addon.
     * jQuery plugin.
     * @since 1.0.0
     */
    $.fn.wpmvc_addon_reviewer = function()
    {
        var self = this;
        self.$el = $( this );
        self.data = {
            ajax_url: self.$el.attr( 'aria-action' ),
            namespace: self.$el.attr( 'aria-namespace' ),
            link_roles: ['review-dismiss', 'review-remind', 'review-done', 'review-proceed'],
        };
        self.loading = false;
        // Methods
        self.methods = {
            /**
             * Plugin initializer.
             * @since 1.0.0
             */
            init: function()
            {
                self.$el.find( 'a' ).click( self.methods.on_click );
            },
            /**
             * Handles link click event.
             * @since 1.0.0
             *
             * @param {object} event
             */
            on_click: function( event )
            {
                var $link = $( this );
                if ( $link.attr( 'href' ) === undefined
                    || $link.attr( 'href' ) === ''
                    || $link.attr( 'href' ) === '#'
                )
                    event.preventDefault();
                // Check roles
                if ( $link.attr( 'role' ) !== undefined
                    && $link.attr( 'aria-response' ) !== undefined
                    && self.data.link_roles.find( function( role ) {
                        return role === $link.attr( 'role' );
                    } ) !== undefined
                ) {
                    self.methods.send_action( {
                        namespace: self.data.namespace, 
                        res: $link.attr( 'aria-response' ),
                    } );
                }
            },
            /**
             * Sends action request to backend.
             * @since 1.0.0
             *
             * @param {object} request
             */
            send_action: function( request )
            {
                if ( self.loading ) return;
                self.methods.set_loading( true );
                $.ajax( self.data.ajax_url, {
                    method: 'POST',
                    data: request,
                    success: self.methods.on_success,
                    error: self.methods.on_error,
                    complete: self.methods.on_complete,
                } );
            },
            /**
             * AJAX success response callback.
             * Handles response returned.
             * @since 1.0.0
             *
             * @param {mixed} response
             */
            on_success: function( response )
            {
                if ( response.error === undefined
                    || response.error
                )
                    return self.methods.on_error( response );
                if ( response.data && response.data.dismiss )
                    return self.methods.dismiss();
            },
            /**
             * AJAX error callback.
             * Handles error returned.
             * @since 1.0.0
             *
             * @param {mixed} e
             */
            on_error: function( e )
            {
                console.log( e );
                throw 'Error sending review action to server.';
            },
            /**
             * AJAX completed callback.
             * @since 1.0.0
             */
            on_complete: function()
            {
                self.methods.set_loading( false );
            },
            /**
             * Removes notification from DOM.
             * @since 1.0.0
             */
            dismiss: function()
            {
                self.$el.remove();
            },
            /**
             * Sets plugin loading state.
             * @since 1.0.0
             *
             * @param {bool} flag
             */
            set_loading: function( flag )
            {
                self.loading = flag;
                self.$el.find( 'a' ).css( 'color', self.loading ? '#ccc' : '' );
                self.$el.find( 'a' ).css( 'border-color', self.loading ? '#ccc' : '' );
            },
        };
        self.methods.init();
    };
    /**
     * Plugin initializer.
     */
    $( '*[role="reviewer"]' ).each( function() {
        $( this ).wpmvc_addon_reviewer();
    } );
} ); } )( jQuery );