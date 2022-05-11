<?php
function render_block_stm_gutenberg_video_format_posts_slider( $attributes ) {

    $vs = $attributes['viewStyle'];
    $contWidth = $attributes['contWidth'];
    $headerStyle = $attributes['headerStyle'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';

    $style = '';
    $bgColor = (!empty($attributes['bgColor'])) ? $style = 'background-color: ' . $attributes['bgColor'] . '; ' : '';
    if(stmt_gutenmag_generateWrapStyle($attributes) != '') $style .= stmt_gutenmag_generateWrapStyle($attributes);
    $wrapStyle = (!empty($style)) ? 'style="' . $style . '"' : '';

    $perPage = ( !empty($attributes['postsToShow']) ) ? $attributes['postsToShow'] : 0;
    $offset = ( !empty($attributes['offset']) ) ? $attributes['offset'] : 0;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $perPage,
        'offset' => $offset * $perPage,
        'orderby' => $attributes['orderBy'],
        'order' => $attributes['order'],
        'tax_query' => array(
            array(
                'taxonomy' => 'post_format',
                'field'    => 'slug',
                'terms'    => array( 'post-format-video' ),
            )
        ),
    );

    $featured_posts = new WP_Query( $args );

    $navShow = ($vs == 'style_3') ? false : true;

    $output = '<script>
        var VFPSVisibleItem = ' . esc_html($attributes["columns"]) . '
    </script>';
    $output .= '<div class="stm-video-format-posts-slider-block ' . $vs . ' ' . $contWidth . '" ' . $wrapStyle . '>'; //plwbp
    $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] != 'full_width_with_content') ? '<div class="container">' : '';//container
    if (!empty($attributes['title'])) {
        $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $headerStyle . '">';
        $output .= $attributes['title'];
        $output .= '</' . $attributes['headingTag'] . '>';
    }

    if($vs == 'style_3') {
        $output .= '<div class="stmt-vps-nav"><span class="prev"></span><span class="next"></span></div>';
    }

    ob_start();
    require_once get_template_directory() . '/template-parts/loop/video-format-posts-slider/video-format-posts-slider-loop-' . $vs . '.php';
    $output .= ob_get_clean();
    $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] != 'full_width_with_content') ? '</div>' : '';//container
    $output .= '</div>';//plwbp

    wp_reset_postdata();
    return $output;
}

function register_block_stm_gutenberg_video_format_posts_slider() {

	$v = stmt_gutenmag_v();

    wp_register_script('stm_gutenberg_video_format_posts_slider',
        STM_GUTENBERG_URL . 'gutenberg/js/video-format-posts-slider.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        $v,
        true
    );

    if(!is_admin()) {
        wp_register_script('stm_gutenberg_video_format_posts_slider_front',
            STM_GUTENBERG_URL . 'gutenberg/js/front-end/video-format-posts-slider-front.js',
            array('wp-blocks', 'wp-element', 'wp-data', 'wp-components', 'wp-editor', 'wp-compose', 'utils'),
			$v,
            true
        );
    }

    wp_enqueue_style( 'stm-owl-style', STM_GUTENBERG_URL . 'assets/css/owl.carousel.css', null, $v, 'all' );
    wp_enqueue_style( 'stm-animate-style', STM_GUTENBERG_URL . 'assets/css/animate.css', null, $v, 'all' );
    wp_enqueue_script('stm-owl-js', STM_GUTENBERG_URL . 'assets/js/owl.carousel.js', array( 'jquery' ), $v, true);

    wp_register_style('stm_gutenberg_video_format_posts_slider',
        STM_GUTENBERG_URL . 'gutenberg/css/video-format-posts-slider.css',
        array( 'wp-edit-blocks' ),
		$v
    );

    register_block_type( 'stm-gutenberg/video-format-posts-slider', array(
        'attributes'      => array(
            'contWidth'     => array ( 'type' => 'string', 'default' => 'boxed' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'columns'       => array ( 'type' => 'string', 'default' => '4'),
            'title'         => array ( 'type' => 'string' ),
            'headerStyle'  => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'    => array ( 'type' => 'string', 'default' => 'h1' ),
            'headingCFS'    => array ( 'type' => 'string' ),
            'postsToShow'   => array ( 'type' => 'string', 'default' => '5', ),
            'offset'        => array ( 'type' => 'string', 'default' => '0', ),
            'order'         => array ( 'type' => 'string', 'default' => 'desc', ),
            'orderBy'       => array ( 'type' => 'string', 'default' => 'date', ),
            'bgColor'       => array ( 'type' => 'string' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_video_format_posts_slider',
        'editor_style' => 'stm_gutenberg_video_format_posts_slider',
        'style' => 'stm_gutenberg_video_format_posts_slider',
        'script' => 'stm_gutenberg_video_format_posts_slider_front',
        'render_callback' => 'render_block_stm_gutenberg_video_format_posts_slider',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_video_format_posts_slider' );