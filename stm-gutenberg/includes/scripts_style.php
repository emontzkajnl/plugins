<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'stm_gutenberg_front_script_styles' );
} else {
    add_action( 'admin_enqueue_scripts', 'stm_gutenberg_scripts_styles' );
}

function stm_gutenberg_front_script_styles () {
    $theme_info = stm_gutenberg_get_assets_path();

    wp_enqueue_script('resize-sensor-js', $theme_info['js'] . 'ResizeSensor.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_enqueue_script('sticky-sidebar-js', $theme_info['js'] . 'sticky-sidebar.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_register_script('stm-owl-js', $theme_info['js'] . 'owl.carousel.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_register_script('stm-isotope-js', $theme_info['js'] . 'isotope.pkgd.min.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_register_script('stm-packery-js', $theme_info['js'] . 'packery-mode.pkgd.min.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
}

function stm_gutenberg_scripts_styles () {
    $theme_info = stm_gutenberg_get_assets_path();
    wp_register_style( 'stm-owl-style', $theme_info['css'] . 'owl.carousel.css', null, STM_GUTENBERG_VER, 'all' );
    wp_register_style( 'stm-animate-style', $theme_info['css'] . 'animate.css', null, STM_GUTENBERG_VER, 'all' );

    wp_register_script('stm-owl-js', $theme_info['js'] . 'owl.carousel.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('stm-isotope-js', $theme_info['js'] . 'isotope.pkgd.min.js', array( 'jquery' ), STM_GUTENBERG_VER, true);
    wp_enqueue_script('stm-packery-js', $theme_info['js'] . 'packery-mode.pkgd.min.js', array( 'jquery' ), STM_GUTENBERG_VER, true);

    wp_enqueue_script('stm_gutenberg_constants', $theme_info['js_gb'] . 'constants.js', array('wp-blocks', 'wp-element', 'wp-data', 'wp-components', 'wp-editor', 'wp-compose', 'utils'), STM_GUTENBERG_VER);
    wp_enqueue_script('stm_gutenberg_block_options', $theme_info['js_gb'] . 'options.js', array('wp-blocks', 'wp-element', 'wp-data', 'utils'), STM_GUTENBERG_VER);
    wp_enqueue_script('stm_gutenberg_admin-app', $theme_info['js'] . 'admin-app.js', array('wp-blocks', 'wp-element', 'wp-data', 'utils'), STM_GUTENBERG_VER);
}