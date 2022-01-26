<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Carousel;

class Enqueues {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
    }

    public function wp_enqueue_scripts() {
        // Do not load unless necessary.
        if ( ! is_admin() && ! Misc::is_compatible_post() ) {
            return;
        }

        // The carousel
        wp_register_script( 'theiaPostSlider/carousel.js', THEIA_POST_SLIDER_PLUGINS_URL . 'dist/js/carousel-for-theia-post-slider.js', [], THEIA_POST_SLIDER_VERSION, true );
        wp_enqueue_script( 'theiaPostSlider/carousel.js' );
    }
}
