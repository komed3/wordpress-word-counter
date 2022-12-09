<?php

    /**
     * Calculation of word count in all posts or a subset of them.
     * 
     * @since 0.01 [ALPHA]
     * 
     * @param   array   $args   Arguments to retrieve posts.
     *                          See WP_Query::parse_query() for all available arguments.
     *                          Default []
     * @return  array           Word counting result as multidimensional array.
     */

    function __wpwc_word_counter(
        array $args = []
    ) {

        global $__wpwc_titles;

        $wc = [
            'timestamp' => date( 'c' ),
            'time' => microtime( true )
        ];

        foreach( get_posts( array_merge( [
            'post_type' => 'any',
            'post_status' => 'any',
            'numberposts' => -1
        ], $args ) ) as $post ) {

            if( empty( $text = wp_strip_all_tags( trim(
                    ( $__wpwc_titles ? $post->post_title : '' ) . ' ' .
                    $post->post_content
                ) ) ) || (
                $count = count( explode( '-',
                    preg_replace( '/[\s]{1,}/', '-',
                        preg_replace( '/[^A-Za-z0-9 \s]/', '',
                            remove_accents( $text )
                        )
                    )
                )
            ) ) == 0 ) {

                /* empty content >> continue to next post */
                continue;

            }

            $wc['post'][ $post->ID ] = $count;

            $wc['results']++;
            $wc['any'] += $count;

            /* types */

            $wc['type'][ $post->post_type ] += $count;
            $wc['status'][ $post->post_status ] += $count;

            /* authors */

            $wc['author'][ $post->post_author ] += $count;

            foreach( get_userdata( $post->post_author )->roles as $role ) {

                $wc['role'][ $role ] += $count;

            }

            /* dates */

            $ts = strtotime( $post->post_date );

            $wc['date'][ date( 'Y-m-d', $ts ) ] += $count;
            $wc['week'][ date( 'Y-W', $ts ) ] += $count;
            $wc['month'][ date( 'Y-m', $ts ) ] += $count;
            $wc['year'][ date( 'Y', $ts ) ] += $count;

            /* taxonomies */

            foreach( get_object_taxonomies( $post ) as $tax ) {

                $wc['tax'][ $tax ] += $count;

                foreach( get_the_terms( $post, $tax ) as $term ) {

                    $wc['term'][ $term->term_id ] += $count;

                }

            }

        }

        $wc['time'] = microtime( true ) - $wc['time'];

        return $wc;

    }

    /**
     * Updating word count using __wpwc_word_counter().
     * 
     * @since 0.01 [ALPHA]
     * 
     * @param   array   $args   Arguments to retrieve posts.
     *                          See WP_Query::parse_query() for all available arguments.
     *                          Default []
     * @return  bool            Result of update_option() function.
     */

    function __wpwc_update(
        array $args = []
    ) {

        return update_option( '__wpwc', json_encode(
            __wpwc_word_counter( $args ),
            JSON_NUMERIC_CHECK
        ) );

    }

    /**
     * Reading the __wpwc option with word count.
     * 
     * @since 0.01 [ALPHA]
     * 
     * @return  array|bool      On success, returns an array containing the last word count.
     *                          If an error occurs, false will be returned.
     */

    function __wpwc_get() {

        return empty( $wpwc = json_decode( get_option( '__wpwc', '{}' ), true ) ) ? false : $wpwc;

    }

    /**
     * Calculation of expected reading time for given word count.
     * 
     * @since 0.01 [ALPHA]
     * 
     * @param   int   $words    Word count.
     *                          Default 0
     * @return  string          Formatted expected reading time.
     */

    function __wpwc_reading_time(
        int $words = 0
    ) {

        global $__wpwc_reading_speed;

        if( $words == 0 )
            return '&mdash;';

        $m = ceil( $words / $__wpwc_reading_speed );

        foreach( [
            525960 => __( 'Yrs', 'wpwc' ),
            1440 => __( 'Day', 'wpwc' ),
            60 => __( 'Hrs', 'wpwc' ),
            1 => __( 'Min', 'wpwc' )
        ] as $t => $l ) {

            if( $m >= $t )
                return number_format_i18n( $m / $t, 0 ) . '&nbsp;' . $l;

        }

    }

    /**
     * Output a column chart for time-based data.
     * 
     * @since 0.01 [ALPHA]
     * 
     * @param   array   $wpwc       The wpwc data array.
     *                              Required.
     * @param   string  $date_type  Period to evaluate for the diagram.
     *                              Possible values: 'day', 'week', 'month', 'year'.
     *                              Default 'month'
     * @return  empty
     */

    function __wpwc_chart(
        array $wpwc,
        string $date_type = 'month'
    ) {

        if( empty( $data = array_reverse(
            array_slice( $wpwc[ $date_type ], 0, 99, true ),
            true
        ) ) ) return '';

        ?><div class="wpwc-chart"><?php

        $max_val = max( 1, max( $data ) );

        $axis = floor( $max_val / 2 / pow( 10, floor( log10( $max_val / 2 ) ) ) ) *
            pow( 10, floor( log10( $max_val / 2 ) ) );

        foreach( $data as $label => $value ) {

            ?><div class="wpwc-chart-column" style="height: <?php
                echo $value == 0 ? 0 : max( 5, ( $value / $max_val * 100 ) );
            ?>%;" title="<?php printf(
                __( '%s / %s words', 'wpwc' ),
                $label,
                number_format_i18n( $value )
            ); ?>"></div><?php

        }

        ?><div class="wpwc-chart-axis">
            <?php for( $a = $axis; $a <= $max_val; $a += $axis ) { ?>
                <div class="wpwc-chart-axis-line" style="bottom: <?php
                        echo $a == 0 ? 0 : ( $a / $max_val * 100 );
                    ?>%;">
                    <div class="label"><?php
                        echo number_format_i18n( $a );
                    ?></div>
                </div>
            <?php } ?>
        </div></div><?php

    }

    /**
     * Output a bar chart processing given data.
     * 
     * @since 0.01 [ALPHA]
     * 
     * @param   array   $data   Array of data to be processed.
     *                          Keys specify set names (should be unique), associated values specify the range.
     *                          Default []
     * @return  empty
     */

    function __wpwc_bar(
        array $data = []
    ) {

        arsort( $data );

        $all = max( 1, array_sum( $data ) );

        ?><div class="wpwc-bar-chart">
            <div class="bars">
                <?php foreach( $data as $label => $value ) { ?>
                    <div class="bar" style="flex: <?php
                        echo max( 1, min( 20, $value / $all * 20 ) );
                    ?>;"></div>
                <?php } ?>
            </div>
            <div class="legend">
                <?php foreach( $data as $label => $value ) { ?>
                    <div class="item">
                        <span class="marker"></span>
                        <div class="label"><?php printf(
                            __( '<b>%s</b> %s words | %s%%', 'wpwc' ),
                            $label,
                            number_format_i18n( $value, 0 ),
                            number_format_i18n( $value / $all * 100, 1 )
                        ); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div><?php

    }

    add_action( 'admin_menu', function() {

        require_once __DIR__ . '/admin/wpwc.php';

        wp_register_style( '__wpwc_admin', plugin_dir_url( __FILE__ ) . 'static/styles/admin.css' );

        wp_register_script( '__wpwc', plugin_dir_url( __FILE__ ) . 'static/scripts/admin.js', [ 'jquery-ui-tabs' ] );
        wp_localize_script( '__wpwc', '__wpwc', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

    } );

    add_action( 'wp_ajax_wpwc_update', function() {

        require_once __DIR__ . '/ajax/update.php';

        __wpwc_ajax__update();

    } );

?>