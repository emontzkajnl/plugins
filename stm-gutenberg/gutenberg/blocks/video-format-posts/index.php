<?php
function render_block_stm_gutenberg_video_format( $attributes ) {

    $vs = $attributes['viewStyle'];
    $contWidth = $attributes['contWidth'];
    $headerStyle = $attributes['headerStyle'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';

    $bgColor = (!empty($attributes['bgColor'])) ? 'background-color: ' . $attributes['bgColor'] . '; ' : '';

    $wrapStyle = 'style="' . $bgColor . stmt_gutenmag_generateWrapStyle($attributes) . '"';

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

    if($featured_posts->have_posts()) {

        $output = '<div class="stm-video-format-block ' . $vs . ' ' . $contWidth . '" ' . $wrapStyle .  '>'; //plwbp
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] != 'full_width_with_content') ? '<div class="container">' : '';//container
        if(!empty($attributes['title'])) {
            $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $headerStyle . '">';
            $output .= $attributes['title'];
            $output .= '</' . $attributes['headingTag'] . '>';
        }
        $output .= '<div class="row">';//stm_g_flex

        while($featured_posts->have_posts()) {
            $featured_posts->the_post();
            $output .= '<div class="col-md-' . (12/$attributes['columns']) . ' col-sm-' . (12/$attributes['columns']) . ' col">';
            ob_start();
            get_template_part('template-parts/loop/video-format/video-format-loop-' . $attributes['viewStyle']);
            $output .= ob_get_clean();
            $output .= '</div>';
        }

        $output .= '</div>';//stm_g_flex
        $output .= ( !empty($attributes['contWidth']) && $attributes['contWidth'] != 'full_width_with_content') ? '</div>' : '';//container
        $output .= '</div>';//plwbp

        wp_reset_postdata();

        return $output;
    }

    return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_video_format() {

    wp_register_script('stm_gutenberg_video_format',
        STM_GUTENBERG_URL . 'gutenberg/js/video-format.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_video_format',
        STM_GUTENBERG_URL . 'gutenberg/css/video-format.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/video-format', array(
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
        'editor_script' => 'stm_gutenberg_video_format',
        'editor_style' => 'stm_gutenberg_video_format',
        'style' => 'stm_gutenberg_video_format',
        'render_callback' => 'render_block_stm_gutenberg_video_format',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_video_format' );
