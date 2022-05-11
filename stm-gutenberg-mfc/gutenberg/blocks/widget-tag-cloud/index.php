<?php
function render_block_stm_gutenberg_widget_tag_cloud ($attributes) {
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $tag_cloud = wp_tag_cloud(
        array(
            'taxonomy'   => 'post_tag',
            'echo'       => false,
            'show_count' => false,
        )
    );

    $tags = ( !empty( $tag_cloud ) ) ? $tag_cloud : esc_html__('No Tags', 'stm-gutenberg' );

    $widget = '<div class="widget_tag_cloud_wrapper" ' . $wrapStyle . '>';

    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    $widget .= '<div class="tagcloud">';
    $widget .= $tags;
    $widget .= "</div>";
    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_tag_cloud () {

    wp_register_script('stm_gutenberg_widget_tag_cloud',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-tag-cloud.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_tag_cloud',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-tag-cloud.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-tag-cloud', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_tag_cloud',
        'editor_style' => 'stm_gutenberg_widget_tag_cloud',
        'style' => 'stm_gutenberg_widget_tag_cloud',
        'render_callback' => 'render_block_stm_gutenberg_widget_tag_cloud',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_tag_cloud' );