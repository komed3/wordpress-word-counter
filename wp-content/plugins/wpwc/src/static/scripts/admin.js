( function( $ ) {

    $( '.wpwc-tabs' ).tabs();

    $( document ).on( 'click', '.wpwc-admin .wpwc-button.update', function( e ) {

        e.preventDefault();

        let button = $( this ),
            spinner = button.next( '.processing' );

        button.css( 'display', 'none' );
        spinner.css( 'display', 'flex' );

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