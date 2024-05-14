<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Carousel;

use WeCodePixels\TheiaPostSlider\Options;
use WeCodePixels\TheiaPostSlider\PostOptions;

class CarouselPostOptions {
    public function __construct() {
        add_action( 'tps_post_options_tabs_menu', [ $this, 'tps_post_options_tabs_menu' ], 10, 1 );
        add_action( 'tps_post_options_tabs_content', [ $this, 'tps_post_options_tabs_content' ], 10, 2 );
        add_filter( 'tps_get_post_defaults', [ $this, 'tps_get_post_defaults' ] );
    }

    public function tps_post_options_tabs_menu( $menu ) {
        $menu[25] = [ 'id' => 'carousel', 'title' => 'Carousel' ];

        return $menu;
    }

    public function tps_post_options_tabs_content( $post, $options ) {
        ?>
        <div id="carousel">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_options_cftps_enable">Enabled:</label>
                    </th>
                    <td>
                        <select id="tps_options_cftps_enable" name="tps_options[cftps_enable_carousel]">
                            <?php
                            foreach ( PostOptions::get_enabled_options() as $key => $value ) {
                                $output = '<option value="' . $key . '"' . ( $key == $options['cftps_enable_carousel'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                                echo $output;
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_options_cftps_items">No. of thumbnails displayed:</label>
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               value="<?php echo $options['cftps_items']; ?>"
                               id="tps_options_cftps_items"
                               name="tps_options[cftps_items]">

                        <p class="description">
                            Leave empty to use global settings
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_options_cftps_height">Height (px):</label>
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               value="<?php echo $options['cftps_height']; ?>"
                               id="tps_options_cftps_height"
                               name="tps_options[cftps_height]">

                        <p class="description">
                            Leave empty to use global settings
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    public function tps_get_post_defaults( $defaults ) {
        $defaults['cftps_enable_carousel'] = 'global';
        $defaults['cftps_items']           = '';
        $defaults['cftps_height']          = '';

        return $defaults;
    }

    public function get_post_option_enable( $postId ) {
        $postOptions = PostOptions::get_post_options( $postId );
        if ( $postOptions['cftps_enable_carousel'] == 'global' ) {
            return Options::get( 'enabled_by_default', 'tps_carousel' );
        }

        return $postOptions['cftps_enable_carousel'] == 'enabled';
    }

    public function get_post_option_items( $postId ) {
        $postOptions = PostOptions::get_post_options( $postId );
        if ( $postOptions['cftps_items'] == '' ) {
            return Options::get( 'items', 'tps_carousel' );
        }

        $items = (int) $postOptions['cftps_items'];
        $items = MAX( 1, $items );

        return $items;
    }

    public static function get_post_option_height( $postId ) {
        $postOptions = PostOptions::get_post_options( $postId );
        if ( $postOptions['cftps_height'] == '' ) {
            return Options::get( 'height', 'tps_carousel' );
        }

        return $postOptions['cftps_height'];
    }
}
