<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Admin;

use WeCodePixels\TheiaPostSlider\Carousel\CarouselOptions;

class Carousel {
    public function echoPage() {
        ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'tps_options_carousel' ); ?>
            <?php $options = get_option( 'tps_carousel' ); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="enabled_by_default"><?php _e( "Enabled:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <select id="enabled_by_default" name="tps_carousel[enabled_by_default]">
                            <option value="true" <?php echo $options['enabled_by_default'] == true ? 'selected' : ''; ?>>
                                Enable by default on all posts
                            </option>
                            <option value="false" <?php echo $options['enabled_by_default'] == false ? 'selected' : ''; ?>>
                                Disable by default on all posts
                            </option>
                        </select>

                        <p class="description">
                            This is the global setting for enabling or disabling the carousel.
                            You can customize this setting on a post-by-post basis.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_items"><?php _e( "Number of thumbnails displayed:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               id="tps_carousel_items"
                               name="tps_carousel[items]"
                               value="<?php echo $options['items']; ?>">

                        <p class="description">
                            The number of slide thumbnails displayed at the same time. The rest of the thumbnails cand
                            be viewed by scrolling the carousel.
                            You can customize this setting on a post-by-post basis.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_height"><?php _e( "Height (px):", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               id="tps_carousel_height"
                               name="tps_carousel[height]"
                               value="<?php echo $options['height']; ?>">

                        <p class="description">
                            The carousel's height. This will not stretch the thumbnails, since they are displayed using
                            a best fit approach.
                            You can customize this setting on a post-by-post basis.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_item_spacing"><?php _e( "Thumbnail spacing (px):", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               id="tps_carousel_item_spacing"
                               name="tps_carousel[item_spacing]"
                               value="<?php echo $options['item_spacing']; ?>">

                        <p class="description">
                            Sets the gap between thumbnails.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_thumbnail_size"><?php _e( "Thumbnail size:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <select id="tps_carousel_thumbnail_size"
                                name="tps_carousel[thumbnail_size]">
                            <?php
                            foreach ( CarouselOptions::get_thumbnail_sizes() as $key => $value ) {
                                $output = '<option value="' . $key . '"' . ( $key == $options['thumbnail_size'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                                echo $output;
                            }
                            ?>
                        </select>

                        <p class="description">
                            Choose the size of the thumbnails the Carousel uses. (file size and dimensions)
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_vertical_position"><?php _e( "Vertical position:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <select id="tps_carousel_vertical_position"
                                name="tps_carousel[vertical_position]">
                            <?php
                            foreach ( CarouselOptions::getVerticalPositionOptions() as $key => $value ) {
                                $output = '<option value="' . $key . '"' . ( $key == $options['vertical_position'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                                echo $output;
                            }
                            ?>
                        </select>

                        <p class="description">
                            The carousel can be displayed either above the post or below.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tps_carousel_source_image_position"><?php _e( "Source image position:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <select id="tps_carousel_source_image_position"
                                name="tps_carousel[source_image_position]">
                            <?php
                            foreach ( CarouselOptions::getSourceImagePosition() as $key => $value ) {
                                $output = '<option value="' . $key . '"' . ( $key == $options['source_image_position'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                                echo $output;
                            }
                            ?>
                        </select>

                        <p class="description">
                            The plugin needs to extract a thumbnail image from each slide/page.
                        </p>
                    </td>
                </tr>
            </table>

            <h3><?php _e( "Navigation Buttons", 'theia-post-slider' ); ?></h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        Icons:
                    </th>
                    <td>
                        <?php VectorIconsPicker::render( 'tps_carousel', $options ) ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        Color:
                    </th>
                    <td>
                        <input type="text"
                               id="tps_carousel_font_color"
                               name="tps_carousel[font_color]"
                               value="<?php echo $options['font_color']; ?>">
                        <script>
                            jQuery(document).ready(function ($) {
                                $('#tps_carousel_font_color').wpColorPicker();
                            });
                        </script>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        Size (px):
                    </th>
                    <td>
                        <input type="number"
                               class="small-text"
                               id="tps_carousel_font_size"
                               name="tps_carousel[font_size]"
                               value="<?php echo $options['font_size']; ?>">
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit"
                       class="button-primary"
                       value="<?php _e( 'Save All Changes', 'theia-post-slider' ) ?>" />
            </p>
        </form>
        <?php
    }
}
