<?php

    function __wpwc_word_counter(
        array $args = [],
        bool $title = true
    ) {

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
                    ( $title ? $post->post_title : '' ) . ' ' .
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

    function __wpwc_get() {

        return empty( $wpwc = json_decode( get_option( '__wpwc', '{}' ), true ) ) ? false : $wpwc;

    }

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

    } );

?>