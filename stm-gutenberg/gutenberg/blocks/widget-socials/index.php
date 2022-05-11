<?php
function render_block_stm_gutenberg_widget_socials ($attributes) {
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $viewStyle = (!empty($attributes['viewStyle'])) ? $attributes['viewStyle'] : 'style_1';

    $widget = '<div class="socials_widget_wrapper sww_' . $viewStyle . '">';
    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    $widget .= stmt_gutenmag_get_socials( $viewStyle );

    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_socials () {

    wp_register_script('stm_gutenberg_widget_socials',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-socials.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_socials',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-socials.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-socials', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'viewStyle'        => array ( 'type' => 'string', 'style_1'),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_socials',
        'editor_style' => 'stm_gutenberg_widget_socials',
        'style' => 'stm_gutenberg_widget_socials',
        'render_callback' => 'render_block_stm_gutenberg_widget_socials',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_socials' );