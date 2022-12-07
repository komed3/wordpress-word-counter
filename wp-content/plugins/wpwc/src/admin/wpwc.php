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

        global $__wpwc_build;

        wp_enqueue_style( '__wpwc_admin' );
        wp_enqueue_script( '__wpwc' );

        ?><div class="wpwc-admin">
            <h1>
                <span><?php _e( 'WordPress Word Counter', 'wpwc' ); ?></span>
                <a href="https://github.com/komed3/wordpress-word-counter" target="_blank" class="wpwc-button donate">
                    <span class="dashicons dashicons-heart"></span>
                    <span class="label"><?php _e( 'Donate', 'wpwc' ); ?></span>
                </a>
            </h1>
            <div class="wpwc-intro">
                <p><?php printf(
                    __( '<b>WordPress Word Counter</b> gives you access to statistics regarding your <b>published ' .
                        'texts</b> with just one click. Who has posted? When did you publish the most words? In ' .
                        'which categories is text still missing?', 'oipm' )
                ); ?></p>
            </div>
            <div class="wpwc-refresh">
                <h2><?php _e( 'Refresh statistics', 'wpwc' ); ?></h2>
                <a href="#" class="wpwc-button update">
                    <span class="dashicons dashicons-update"></span>
                    <span class="label"><?php _e( 'Refresh', 'wpwc' ); ?></span>
                </a>
            </div>
            <?php if( $wpwc = __wpwc_get() ) { ?>
                <div class="wpwc-tabs">
                    <ul>
                        <li><a href="#wpwc__general">
                            <span class="dashicons dashicons-chart-bar"></span>
                            <span class="label"><?php _e( 'General', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__type">
                            <span class="dashicons dashicons-admin-post"></span>
                            <span class="label"><?php _e( 'Types/States', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__author">
                            <span class="dashicons dashicons-admin-users"></span>
                            <span class="label"><?php _e( 'Authors', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__date">
                            <span class="dashicons dashicons-calendar"></span>
                            <span class="label"><?php _e( 'Dates', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__tax">
                            <span class="dashicons dashicons-tag"></span>
                            <span class="label"><?php _e( 'Taxonomies', 'wpwc' ); ?></span>
                        </a></li>
                    </ul>
                    <div class="wpwc-tab" id="wpwc__general">
                        <div class="wpwc-boxes">
                            <div class="wpwc-box">
                                <div class="value"><?php echo number_format_i18n( $wpwc['any'] ); ?></div>
                                <div class="label"><?php _e( 'Words', 'oipm' ); ?></div>
                            </div>
                            <div class="wpwc-box">
                                <div class="value"><?php echo number_format_i18n( $wpwc['results'] ); ?></div>
                                <div class="label"><?php _e( 'Results', 'oipm' ); ?></div>
                            </div>
                            <div class="wpwc-box">
                                <div class="value"><?php echo date_i18n( __( 'm/d/Y', 'wpwc' ), strtotime( $wpwc['timestamp'] ) ); ?></div>
                                <div class="label"><?php _e( 'Date', 'oipm' ); ?></div>
                            </div>
                        </div>
                        <?php __wpwc_calendar( $wpwc ); ?>
                    </div>
                    <div class="wpwc-tab" id="wpwc__type"></div>
                    <div class="wpwc-tab" id="wpwc__author"></div>
                    <div class="wpwc-tab" id="wpwc__date"></div>
                    <div class="wpwc-tab" id="wpwc__tax"></div>
                </div>
            <?php } ?>
            <div class="wpwc-footer">
                <p><?php printf(
                    __( 'WPWC %s | &copy; 2022 by komed3.', 'wpwc' ),
                    $__wpwc_build,
                ); ?></p>
            </div>
        </div><?php

    }

?>