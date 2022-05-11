<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Carousel;

use WeCodePixels\TheiaPostSlider\Options;

class CarouselOptions {
    public function __construct() {
        add_filter( 'tps_init_options_defaults', [ $this, 'tps_init_options_defaults' ] );
    }

    public function tps_init_options_defaults( $defaults ) {
        $defaults['tps_carousel'] = array(
            'enabled_by_default'             => false,
            'items'                          => 5,
            'height'                         => 175,
            'item_spacing'                   => 10,
            'thumbnail_size'                 => 'thumbnail',
            'vertical_position'              => 'top',
            'source_image_position'          => 'first',
            'theme_font_name'                => 'chevron',
            'theme_vector_left_icon'         => Options::get_svg_for_icon( 'chevron', 'left' ),
            'theme_vector_right_icon'        => Options::get_svg_for_icon( 'chevron', 'right' ),
            'theme_vector_custom'            => false,
            'theme_vector_custom_left_icon'  => '',
            'theme_vector_custom_right_icon' => '',
            'font_color'                     => '#f08100',
            'font_size'                      => 32
        );

        return $defaults;
    }

    // Get carousel thumbnail sizes
    public static function get_thumbnail_sizes() {
        $sizes   = self::get_image_sizes();
        $options = array();

        foreach ( $sizes as $key => $value ) {
            $options[ $key ] = $key . ' (' . $value['width'] . ' * ' . $value['height'] . ' px)';
        }

        return $options;
    }

    public static function get_font_icon( $direction ) {
        return Options::get_font_icon( $direction, 'tps_carousel' );
    }

    public static function get_image_sizes( $size = '' ) {
        global $_wp_additional_image_sizes;

        $sizes                        = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach ( $get_intermediate_image_sizes as $_size ) {
            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
                $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                $sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
                );
            }
        }

        // Get only 1 size if found
        if ( $size ) {
            if ( isset( $sizes[ $size ] ) ) {
                return $sizes[ $size ];
            } else {
                return false;
            }
        }

        return $sizes;
    }

    public static function getVerticalPositionOptions() {
        return [
            'top'                   => 'Top',
            'top_after_nav_bar'     => 'Top (after navigation bar)',
            'bottom'                => 'Bottom',
            'bottom_before_nav_bar' => 'Bottom (before navigation bar)'
        ];
    }

    public static function getSourceImagePosition() {
        return [
            'first' => 'First',
            'last'  => 'Last'
        ];
    }
}
