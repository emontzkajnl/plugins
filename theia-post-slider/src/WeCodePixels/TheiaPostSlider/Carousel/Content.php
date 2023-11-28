<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Carousel;

use WeCodePixels\TheiaPostSlider\Options;

class Content {
    public function __construct() {
        add_filter( 'tps_the_content_before', [ $this, 'the_content_before' ], 10, 2 );
        add_filter( 'tps_the_content_after', [ $this, 'the_content_after' ], 10, 2 );
        add_filter( 'tps_the_content_after_current_slide', [ $this, 'tps_the_content_after_current_slide' ], 10, 2 );
        add_filter( 'tps_the_content_before_current_slide', [ $this, 'tps_the_content_before_current_slide' ], 10, 2 );
    }

    public function tps_the_content_after_current_slide( $html, $content ) {
        if ( Misc::is_compatible_post() && Options::get( 'vertical_position', 'tps_carousel' ) == 'bottom_before_nav_bar' ) {
            return self::the_content( $html, $content );
        }

        return $html;
    }

    public function tps_the_content_before_current_slide( $html, $content ) {
        if ( Misc::is_compatible_post() && Options::get( 'vertical_position', 'tps_carousel' ) == 'top_after_nav_bar' ) {
            return self::the_content( $html, $content );
        }

        return $html;
    }

    public function the_content_before( $html, $content ) {
        if ( Misc::is_compatible_post() && Options::get( 'vertical_position', 'tps_carousel' ) == 'top' ) {
            return self::the_content( $html, $content );
        }

        return $html;
    }

    public function the_content_after( $html, $content ) {
        if ( Misc::is_compatible_post() && Options::get( 'vertical_position', 'tps_carousel' ) == 'bottom' ) {
            return self::the_content( $html, $content );
        }

        return $html;
    }

    public function the_content( $html, $content ) {
        global $post, $pages;

        if ( ! Misc::is_compatible_post( $post ) ) {
            return false;
        }

        $carousel_items = min( $GLOBALS['theiaPostSlider']['carousel']['postOptions']->get_post_option_items( $post->ID ), count( $pages ) );

        // Get image for each page.
        $images = array();
        foreach ( $pages as $page ) {
            $src = '';

            // Parse HTML.
            $dom     = new \DOMDocument();
            $success = @$dom->loadHTML( $page );
            if ( ! $success ) {
                $images[] = '';
                continue;
            }

            // Search for <img> tags.
            $xpath = new \DOMXPath( $dom );
            $imgs  = $xpath->query( '//img' );

            // Use featured image, if available.
            foreach ( $imgs as $img ) {
                $classes = $img->getAttribute( 'class' );
                $classes = explode( ' ', $classes );
                if ( in_array( 'cftps-featured', $classes ) ) {
                    $src = $img;

                    break;
                }
            }

            // Use first or last image.
            if ( $src == '' && $imgs->length > 0 ) {
                if ( Options::get( 'source_image_position', 'tps_carousel' ) == 'first' ) {
                    $src = $imgs->item( 0 );
                } else {
                    $src = $imgs->item( $imgs->length - 1 );
                }
            }

            // Get "src" attribute.
            if ( $src != '' ) {
                $src = $src->getAttribute( 'src' );
            }

            // Get smaller version of this image.
            $attachment_id = self::pn_get_attachment_id_from_url( $src );
            if ( $attachment_id !== null ) {
                $src = wp_get_attachment_image_src( $attachment_id, Options::get( 'thumbnail_size', 'tps_carousel' ) );
                if ( ! $src ) {
                    continue;
                }
                $src = $src[0];
            }

            $images[] = $src;
        }

        $html  .= '<div id="tps_carousel_container">';
        $html  .= self::echo_button( 'prev' );
        $html  .= self::echo_button( 'next' );
        $html  .= '<div class="_carousel"><div id="tps_carousel">';
        $slide = 0;
        foreach ( $images as $key => $image ) {
            $active = $GLOBALS['page'] - 1 === $key ? 'active' : '';
            $html   .= '<div class="item ' . $active . '" data-slide="' . $slide . '"><div style="background-image: url(' . $image . ')"></div></div>';
            $slide ++;
        }
        $html      .= '</div></div>';
        $cfOptions = array(
            'selector'     => '#tps_carousel',
            'prevSelector' => '#tps_carousel_container ._prev',
            'nextSelector' => '#tps_carousel_container ._next',
            'items'        => $carousel_items,
            'margin'       => (int) Options::get( 'item_spacing', 'tps_carousel' ),
            'speed'        => 100
        );
        $html      .= '
			<style>
				#tps_carousel_container ._button {
					margin-top: ' . ( $GLOBALS['theiaPostSlider']['carousel']['postOptions']->get_post_option_height( $post->ID ) / 2 - Options::get( 'font_size', 'tps_carousel' ) / 2 ) . 'px;
				}

				#tps_carousel .item {
					height: ' . $GLOBALS['theiaPostSlider']['carousel']['postOptions']->get_post_option_height( $post->ID ) . 'px !important;
				}
			</style>
		';
        $html      .= "<div data-cftps-options='" . json_encode( $cfOptions ) . "'></div>";
        $html      .= '</div>';

        return $html;
    }

    public function echo_button( $direction ) {
        $text   = $direction == 'next' ? CarouselOptions::get_font_icon( 'right' ) : CarouselOptions::get_font_icon( 'left' );
        $button = '<div class="_button _' . $direction . '">' . $text . '</div>';

        return $button;
    }

    // Inspired from https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
    public function pn_get_attachment_id_from_url( $attachment_url = '' ) {
        global $wpdb;
        $attachment_id = false;

        // If there is no url, return.
        if ( '' == $attachment_url ) {
            return '';
        }

        // Get the upload directory paths
        $upload_dir_paths = wp_upload_dir();

        // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
        if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

            // If this is the URL of an auto-generated thumbnail, get the URL of the original image
            $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
        }

        return $attachment_id;
    }
}
