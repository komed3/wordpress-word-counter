<?php

    /**
     * Plugin Name: WordPress word counter
     * Plugin URI: https://github.com/komed3/wordpress-word-counter
     * Description: WordPress content word counter
     * Author: Paul Köhler (komed3)
     * Author URI: https://komed3.de
     * Version: 0.01 [ALPHA] @ 12/09/2022
     * Text Domain: wpwc
     */

    /**
     * Essential parameters can be set using these global variables.
     *
     * @since 0.01 [ALPHA]
     * 
     * @var string  $__wpwc_capability      User capability to access WPWC admin page.
     *                                      Default 'manage_options'
     * @var bool    $__wpwc_titles          If true, titles are included in the calculation.
     *                                      Default true
     * @var int     $__wpwc_reading_speed   Reading speed in words per minute.
     *                                      Default 300
     */

    $__wpwc_capability = 'manage_options';
    $__wpwc_titles = true;
    $__wpwc_reading_speed = 300;

    require_once __DIR__ . '/src/functions.php';

?>