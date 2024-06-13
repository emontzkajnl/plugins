<?php
function render_block_stm_gutenberg_widget_must_read ($attributes) {
    $vs = $attributes['viewStyle'];
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $perPage = ( !empty($attributes['postsToShow']) ) ? $attributes['postsToShow'] : 0;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $perPage
    );

    $featured_posts = new WP_Query( $args );

    $widget = '<div class="widget_must_read_wrapper wmrw_' . $vs . '" ' . $wrapStyle . '>';
    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    if($featured_posts->have_posts()) {

        $widget .= '<ul>';//stm_g_flex

        while($featured_posts->have_posts()) {
            $featured_posts->the_post();
            ob_start();
            get_template_part('template-parts/loop/widget-must-read/loop');
            $widget .= ob_get_clean();
        }

        $widget .= '</ul>';//stm_g_flex
        wp_reset_postdata();
    } else {
        $widget .= __('No Posts', 'stm-gutenberg');
    }

    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_must_read () {

    wp_register_script('stm_gutenberg_widget_must_read',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-must-read.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_must_read',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-must-read.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-must-read', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'postsToShow'        => array ( 'type' => 'string', 'default' => '4' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_must_read',
        'editor_style' => 'stm_gutenberg_widget_must_read',
        'style' => 'stm_gutenberg_widget_must_read',
        'render_callback' => 'render_block_stm_gutenberg_widget_must_read',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_must_read' );