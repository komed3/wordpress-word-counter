<?php

    global $__wpwc_capability;

    add_management_page(
        __( 'Word Counter', 'wpwc' ),
        __( 'Word Counter', 'wpwc' ),
        $__wpwc_capability,
        'wpwc',
        '__wpwc_admin'
    );

    function __wpwc_admin() {



    }

?>