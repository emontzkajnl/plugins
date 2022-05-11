<?php
function render_block_stm_gutenberg_widget_mchmp ($attributes) {

    $vs = $attributes['viewStyle'];

    $touLink = (!empty($attributes['tou_link'])) ? $attributes['tou_link'] : '';

    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = stmt_gutenmag_generateWrapStyle($attributes);
    $wrapStyle .= (!empty($attributes['bgColor'])) ? ' background-color: ' . $attributes['bgColor'] . '; ' : '';

    $widget = '<div class="widget_mchmp_wrapper ' . $vs . '" style="' . $wrapStyle . '" >';
    if($vs == 'style_2') $widget .= '<div class="inner">';
    if(!empty($attributes['subtitle'])) $widget .= '<div class="subtitle normal-font">' . $attributes['subtitle'] . '</div>';
    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    ob_start();
    echo do_shortcode('[stm_mailchimp]');
    $widget .= ob_get_clean();
    if(!empty($attributes['show_termofuse'])) $widget .= '<div class="normal-font tou_text">' . sprintf( __('By signing up, you agree to <a href="%s">our terms</a>.'), $touLink ) . '</div>';
    if($vs == 'style_2') $widget .= '</div>';
    $widget .= '</div>';

    return $widget;
}

function register_block_stm_gutenberg_widget_mchmp () {

    wp_register_script('stm_gutenberg_widget_mchmp',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-mchmp.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_mchmp',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-mchmp.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-mchmp', array(
        'attributes'            => array(
            'viewStyle'         => array ( 'type' => 'string', 'default' => 'style_1' ),
            'title'             => array ( 'type' => 'string', ),
            'subtitle'          => array ( 'type' => 'string', ),
            'show_termofuse'    => array ( 'type' => 'boolean', 'default' => false),
            'tou_link'          => array ( 'type' => 'string' ),
            'headerStyle'       => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h4' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'bgColor'           => array ( 'type' => 'string' ),
            'margin_top'        => array ( 'type' => 'string' ),
            'padding_top'       => array ( 'type' => 'string' ),
            'padding_left'      => array ( 'type' => 'string' ),
            'margin_left'       => array ( 'type' => 'string' ),
            'padding_right'     => array ( 'type' => 'string' ),
            'margin_right'      => array ( 'type' => 'string' ),
            'padding_bottom'    => array ( 'type' => 'string' ),
            'margin_bottom'     => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_mchmp',
        'editor_style' => 'stm_gutenberg_widget_mchmp',
        'style' => 'stm_gutenberg_widget_mchmp',
        'render_callback' => 'render_block_stm_gutenberg_widget_mchmp',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_mchmp' );