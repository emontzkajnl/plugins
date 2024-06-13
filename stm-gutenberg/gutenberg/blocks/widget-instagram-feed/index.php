<?php
function render_block_stm_gutenberg_widget_instagram_feed ($attributes) {
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $align = (!empty($attributes['align'])) ? 'align' . $attributes['align'] : '';

    $widget = '<div class="widget_instagram_feed_wrapper ' . $align . '"' . $wrapStyle . '>';
    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    if(function_exists('display_instagram')) {
        $widget .= display_instagram('');
    }

    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_instagram_feed () {

    wp_register_script('stm_gutenberg_widget_instagram_feed',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-instagram-feed.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_instagram_feed',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-instagram-feed.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-instagram-feed', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h4' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
            'align' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_instagram_feed',
        'editor_style' => 'stm_gutenberg_widget_instagram_feed',
        'style' => 'stm_gutenberg_widget_instagram_feed',
        'render_callback' => 'render_block_stm_gutenberg_widget_instagram_feed',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_instagram_feed' );