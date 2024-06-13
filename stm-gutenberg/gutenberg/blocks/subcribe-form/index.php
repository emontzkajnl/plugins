<?php
function render_block_stm_gutenberg_subscribe_form( $attributes ) {

    $vs = $attributes['viewStyle'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';


    $output = '<div class="stm-subscribe-form-block ' . $vs . ' " ' . $wrapStyle . '>'; //plwbp
    $output .= '<div class="container">';//container
    if(!empty($attributes['title'])) {
        $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $attributes['headerStyle'] . '">';
        $output .= $attributes['title'];
        $output .= '</' . $attributes['headingTag'] . '>';
    }
    $output .= '<div class="form-wrap">';//stm_g_flex

    $mc4wpId = get_option('mc4wp_default_form_id', '');
    if(!empty($mc4wpId)) {
        $output .= mc4wp_get_form($mc4wpId)->get_html();
    }

    $output .= '</div>';//stm_g_flex
    $output .= '</div>';//container
    $output .= '</div>';//plwbp

    wp_reset_postdata();

    return $output;
}

function register_block_stm_gutenberg_subscribe_form() {
    wp_register_script('stm_gutenberg_subscribe_form',
        STM_GUTENBERG_URL . 'gutenberg/js/subscribe-form.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_subscribe_form',
        STM_GUTENBERG_URL . 'gutenberg/css/subscribe-form.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/subscribe-form', array(
        'attributes'      => array(
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'title'         => array ( 'type' => 'string' ),
            'headerStyle'  => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'    => array ( 'type' => 'string', 'default' => 'h1' ),
            'headingCFS'    => array ( 'type' => 'string' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_subscribe_form',
        'editor_style' => 'stm_gutenberg_subscribe_form',
        'style' => 'stm_gutenberg_subscribe_form',
        'render_callback' => 'render_block_stm_gutenberg_subscribe_form',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_subscribe_form' );
