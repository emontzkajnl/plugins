<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Carousel;

use WeCodePixels\TheiaPostSlider\Colors;
use WeCodePixels\TheiaPostSlider\Options;
use WeCodePixels\TheiaPostSlider\PostOptions;

class Misc {
    public function __construct() {
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'wp_head', [ $this, 'wp_head' ] );
    }

    public function init() {
        $legacyCarouselPlugin = 'carousel-for-theia-post-slider/main.php';
        if ( is_plugin_active( $legacyCarouselPlugin ) ) {
            deactivate_plugins( $legacyCarouselPlugin );
        }
    }

    public function wp_head() {
        $colors = Colors::get_variations( Options::get( 'font_color', 'tps_carousel' ) );

        ?>
        <style>
            #tps_carousel_container ._button,
            #tps_carousel_container ._button svg {
                color: <?php echo Options::get( 'font_color', 'tps_carousel' ); ?>;
                fill: <?php echo Options::get( 'font_color', 'tps_carousel' ); ?>;
            }

            #tps_carousel_container ._button {
                font-size: <?php echo Options::get( 'font_size', 'tps_carousel' ); ?>px;
                line-height: <?php echo Options::get( 'font_size', 'tps_carousel' ); ?>px;
            }

            #tps_carousel_container ._button svg {
                width: <?php echo Options::get( 'font_size', 'tps_carousel' ); ?>px;
                height: <?php echo Options::get( 'font_size', 'tps_carousel' ); ?>px;
            }

            #tps_carousel_container ._button:hover,
            #tps_carousel_container ._button:focus,
            #tps_carousel_container ._button:hover svg,
            #tps_carousel_container ._button:focus svg {
                color: <?php echo Colors::hsl_to_hex( $colors['hover_color'] ); ?>;
                fill: <?php echo Colors::hsl_to_hex( $colors['hover_color'] ); ?>;
            }

            #tps_carousel_container ._disabled,
            #tps_carousel_container ._disabled svg {
                color: <?php echo Colors::hsl_to_hex( $colors['disabled_color'] ); ?> !important;
                fill: <?php echo Colors::hsl_to_hex( $colors['disabled_color'] ); ?> !important;
            }

            #tps_carousel_container ._carousel {
                margin: 0 <?php echo Options::get( 'font_size', 'tps_carousel' ) + Options::get( 'item_spacing', 'tps_carousel' ); ?>px;
            }

            #tps_carousel_container ._prev {
                margin-right: <?php echo Options::get( 'item_spacing', 'tps_carousel' ); ?>px;
            }

            #tps_carousel_container ._next {
                margin-left: <?php echo Options::get( 'item_spacing', 'tps_carousel' ); ?>px;
            }
        </style>
        <?php
    }

    // Is this post a "post" or a "page" (i.e. should we display the slider)?
    public static function is_compatible_post( $post = null ) {
        $post = get_post( $post );

        if ( $post ) {
            if (
                false == PostOptions::get_post_option_enabled( $post->ID ) ||
                false == $GLOBALS['theiaPostSlider']['carousel']['postOptions']->get_post_option_enable( $post->ID )
            ) {
                return false;
            }
        }

        return true;
    }
}
