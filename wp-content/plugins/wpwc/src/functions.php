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

        return get_option( '__wpwc', false );

    }

    add_action( 'admin_menu', function() {

        require_once __DIR__ . '/admin/wpwc.php';

        wp_register_style( '__wpwc_admin', plugin_dir_url( __FILE__ ) . 'static/styles/admin.css' );

    } );

?>