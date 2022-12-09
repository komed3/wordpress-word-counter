<?php

    function __wpwc_ajax__update() {

        __wpwc_update();

        wp_die();

    }

?>