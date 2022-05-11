<?php
function render_block_stm_gutenberg_posts_slider ($attributes) {

    $vs = $attributes['viewStyle'];
    $headerStyle = $attributes['headerStyle'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $perPage = ( !empty($attributes['postsToShow']) ) ? $attributes['postsToShow'] : 0;
    $offset = ( !empty($attributes['offset']) ) ? $attributes['offset'] : 0;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $perPage,
        'offset' => $offset * $perPage,
        'orderby' => $attributes['orderBy'],
        'order' => $attributes['order'],
    );

    if($attributes['categories'] != '') {
        $tax = array (
            array (
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $attributes['categories']
            )
        );
        $args['tax_query'] = $tax;
    }

    $posts_slider = new WP_Query( $args );

    ob_start();
    require_once get_template_directory() . '/template-parts/loop/posts-slider/posts-slider-' . $vs . '.php';
    wp_reset_postdata();
    return ob_get_clean();
}

function register_block_stm_gutenberg_posts_slider () {

    $v = '1.1';

    wp_enqueue_style( 'stm-owl-style', STM_GUTENBERG_URL . 'assets/css/owl.carousel.css', null, $v, 'all' );
    wp_enqueue_style( 'stm-animate-style', STM_GUTENBERG_URL . 'assets/css/animate.css', null, $v, 'all' );
    wp_enqueue_script('stm-owl-js', STM_GUTENBERG_URL . 'assets/js/owl.carousel.js', array( 'jquery' ), $v, true);

    wp_register_script('stm_gutenberg_posts_slider',
        STM_GUTENBERG_URL . 'gutenberg/js/posts-slider.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        $v,
        true
    );

    wp_register_style('stm_gutenberg_posts_slider',
        STM_GUTENBERG_URL . 'gutenberg/css/posts-slider.css',
        array( 'wp-edit-blocks' ),
        $v
    );

    wp_register_script('stm_gutenberg_posts_slider_script_front',
        STM_GUTENBERG_URL . 'gutenberg/js/front-end/stm_gutenberg_posts_slider_script_front.js',
        array('jquery', 'utils'),
        $v,
        true
    );

    register_block_type( 'stm-gutenberg/posts-slider', array(
        'attributes'      => array(
            'contWidth'     => array ( 'type' => 'string', 'default' => 'full_width' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'categories'    => array ( 'type' => 'string', 'default' => 'all' ),
            'title'         => array ( 'type' => 'string' ),
            'headerStyle'  => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'    => array ( 'type' => 'string', 'default' => 'h1' ),
            'headingCFS'    => array ( 'type' => 'string' ),
            'postsToShow'   => array ( 'type' => 'string', 'default' => '5', ),
            'offset'        => array ( 'type' => 'string', 'default' => '0', ),
            'order'         => array ( 'type' => 'string', 'default' => 'desc', ),
            'orderBy'       => array ( 'type' => 'string', 'default' => 'date', ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_posts_slider',
        'editor_style' => 'stm_gutenberg_posts_slider',
        'style' => 'stm_gutenberg_posts_slider',
        'script' => 'stm_gutenberg_posts_slider_script_front',
        'render_callback' => 'render_block_stm_gutenberg_posts_slider',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_posts_slider' );