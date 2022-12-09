( function( $ ) {

    $( '.wpwc-tabs' ).tabs();

    $( document ).on( 'click', '.wpwc-admin .wpwc-button.update', function( e ) {

        e.preventDefault();

        $.ajax( {
            url: __wpwc.ajaxurl,
            type: 'POST',
            data: {
                action: 'wpwc_update'
            },
            success: function() {

                location.reload();

            }
        } );

    } );

} )( jQuery );