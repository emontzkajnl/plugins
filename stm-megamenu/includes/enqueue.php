<?php
function stm_megamenu_front_scripts_method() {
    $front_css = STM_MM_URL . 'assets/css/';
    $front_js = STM_MM_URL . 'assets/js/';

    wp_enqueue_style( 'stm_megamenu', $front_css . 'megamenu.css' );
    wp_enqueue_script( 'stm_megamenu', $front_js . 'megamenu.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'stm_megamenu_front_scripts_method' );