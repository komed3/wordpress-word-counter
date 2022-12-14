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
                <a href="https://github.com/komed3/wordpress-word-counter/issues" target="_blank" class="wpwc-button feedback">
                    <span class="dashicons dashicons-megaphone"></span>
                    <span class="label"><?php _e( 'Feedback', 'wpwc' ); ?></span>
                </a>
            </h1>
            <div class="wpwc-intro">
                <p><?php printf(
                    __( '<b>WordPress Word Counter</b> gives you access to statistics regarding your <b>published ' .
                        'texts</b> with just one click. Who has posted? When did you publish the most words? In ' .
                        'which categories is text still missing?', 'wpwc' )
                ); ?></p>
            </div>
            <div class="wpwc-refresh">
                <h2><?php _e( 'Refresh statistics', 'wpwc' ); ?></h2>
                <a href="#" class="wpwc-button update">
                    <span class="dashicons dashicons-update"></span>
                    <span class="label"><?php _e( 'Refresh', 'wpwc' ); ?></span>
                </a>
                <div class="processing">
                    <span class="dashicons dashicons-update spinning"></span>
                    <span class="label"><?php _e( 'Processing. This may take some time ???', 'wpwc' ); ?></span>
                </div>
            </div>
            <?php if( $wpwc = __wpwc_get() ) {

                $status = $types = $roles = $taxoms = [];
                $__status = get_post_statuses();
                $__roles = wp_roles()->roles;

                foreach( $wpwc['status'] as $key => $val ) {

                    $status[ $__status[ $key ] ] = $val;

                }

                foreach( $wpwc['type'] as $key => $val ) {

                    $types[ get_post_type_object( $key )->label ] = $val;

                }

                foreach( $wpwc['role'] as $key => $val ) {

                    $roles[ $__roles[ $key ]['name'] ] = $val;

                }

                foreach( $wpwc['tax'] as $key => $val ) {

                    $taxoms[ get_taxonomy( $key )->label ] = $val;

                }

            ?>
                <div class="wpwc-tabs">
                    <ul>
                        <li><a href="#wpwc__general">
                            <span class="dashicons dashicons-chart-bar"></span>
                            <span class="label"><?php _e( 'General', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__post">
                            <span class="dashicons dashicons-admin-post"></span>
                            <span class="label"><?php _e( 'Posts', 'wpwc' ); ?></span>
                        </a></li>
                        <li><a href="#wpwc__type">
                            <span class="dashicons dashicons-format-aside"></span>
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
                                <div class="value"><?php echo number_format_i18n( $wpwc['any'], 0 ); ?></div>
                                <div class="label"><?php _e( 'All words', 'wpwc' ); ?></div>
                            </div>
                            <div class="wpwc-box">
                                <div class="value"><?php echo __wpwc_reading_time( $wpwc['any'] ); ?></div>
                                <div class="label"><?php _e( 'Reading time', 'wpwc' ); ?></div>
                            </div>
                            <div class="wpwc-box">
                                <div class="value"><?php echo number_format_i18n( $wpwc['any'] / max( 1, $wpwc['results'] ), 0 ); ?></div>
                                <div class="label"><?php _e( 'Post average', 'wpwc' ); ?></div>
                            </div>
                            <div class="wpwc-box">
                                <div class="value"><?php echo number_format_i18n( max( $wpwc['post'] ), 0 ); ?></div>
                                <div class="label"><?php _e( 'Longest post', 'wpwc' ); ?></div>
                            </div>
                        </div>
                        <?php __wpwc_chart( $wpwc ); ?>
                    </div>
                    <div class="wpwc-tab" id="wpwc__post">
                        <table class="wp-list-table widefat striped">
                            <thead>
                                <tr>
                                    <th class="column-title"><?php _e( 'Post', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Words', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Reading time', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Status', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Date', 'wpwc' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php arsort( $wpwc['post'] ); foreach( array_slice( $wpwc['post'], 0, 99, true ) as $post => $words ) { ?>
                                    <tr>
                                        <td><a href="<?php the_permalink( $post ); ?>">
                                            <?php echo get_the_title( $post ); ?>
                                        </a></td>
                                        <td><?php echo number_format_i18n( $words, 0 ); ?></td>
                                        <td><?php echo __wpwc_reading_time( $words ); ?></td>
                                        <td><?php echo $__status[ get_post_status( $post ) ]; ?></td>
                                        <td><?php echo get_the_date( '', $post ); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="wpwc-tab" id="wpwc__type">
                        <h2><?php _e( 'Post status', 'oipm' ); ?></h2>
                        <?php __wpwc_bar( $status ); ?>
                        <h2><?php _e( 'Post types', 'oipm' ); ?></h2>
                        <?php __wpwc_bar( $types ); ?>
                    </div>
                    <div class="wpwc-tab" id="wpwc__author">
                        <h2><?php _e( 'User roles', 'oipm' ); ?></h2>
                        <?php __wpwc_bar( $roles ); ?>
                        <h2><?php _e( 'Authors', 'oipm' ); ?></h2>
                        <?php

                            $author_max = max( $wpwc['author'] );
                            arsort( $wpwc['author'] );

                            foreach( $wpwc['author'] as $key => $val ) {

                                $author = get_user_by( 'ID', $key );

                            ?>
                            <div class="wpwc-author">
                                <div class="fill" style="width: <?php
                                    echo ( $val / $author_max * 100 );
                                ?>%;"></div>
                                <?php echo get_avatar( $author->user_email, 32 ); ?>
                                <div class="info">
                                    <h3><a href="<?php echo get_edit_user_link( $author->ID ); ?>">
                                        <?php echo $author->display_name; ?>
                                    </a></h3>
                                    <?php echo $__roles[ $author->roles[0] ]['name']; ?>
                                </div>
                                <div class="words">
                                    <?php echo number_format_i18n( $val, 0 ); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="wpwc-tab" id="wpwc__date">
                        <h2><?php _e( 'Years', 'oipm' ); ?></h2>
                        <?php __wpwc_chart( $wpwc, 'year' ); ?>
                        <h2><?php _e( 'Months', 'oipm' ); ?></h2>
                        <table class="wp-list-table widefat fixed striped centered">
                            <thead>
                                <tr>
                                    <th class="column-title"><?php _e( 'Year', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Jan', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Feb', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Mrz', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Apr', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'May', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Jun', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Jul', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Aug', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Sep', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Oct', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Nov', 'wpwc' ); ?></th>
                                    <th class="column-title"><?php _e( 'Dec', 'wpwc' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( array_keys( $wpwc['year'] ) as $year ) { ?>
                                    <tr>
                                        <td><?php echo $year; ?></td>
                                        <?php for( $m = 1; $m <= 12; $m++ ) { ?>
                                            <td><?php echo number_format_i18n( $wpwc['month'][
                                                $year . '-' . str_pad( $m, 2, '0', STR_PAD_LEFT )
                                            ] ?? 0, 0 ); ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="wpwc-tab" id="wpwc__tax">
                        <h2><?php _e( 'Taxonomies', 'oipm' ); ?></h2>
                        <?php __wpwc_bar( $taxoms ); ?>
                        <h2><?php _e( 'Terms', 'oipm' ); ?></h2>
                        <div class="wpwc-boxes">
                            <?php foreach( $wpwc['term'] as $key => $val ) { ?>
                                <div class="wpwc-box">
                                    <div class="value"><?php echo number_format_i18n( $val, 0 ); ?></div>
                                    <div class="label"><?php echo get_term( $key )->name; ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="wpwc-footer">
                <p><?php $plugin_data = get_plugin_data( ABSPATH . 'wp-content/plugins/wpwc/wpwc.php' ); printf(
                    __( '%s %s | &copy; %s', 'wpwc' ),
                    $plugin_data['Title'],
                    $plugin_data['Version'],
                    $plugin_data['Author'],
                ); ?></p>
            </div>
        </div><?php

    }

?>