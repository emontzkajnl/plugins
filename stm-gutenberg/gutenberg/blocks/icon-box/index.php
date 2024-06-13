<?php
function render_block_stm_gutenberg_icon_box( $attributes ) {

    $headerStyle = $attributes['headerStyle'];
    $headingCFSStyle = (!empty($attributes['headingCFS'])) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    
    return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_icon_box() {\

    wp_register_script('stm_gutenberg_icon_box',
        STM_GUTENBERG_URL . 'gutenberg/js/icon-box.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_icon_box',
        STM_GUTENBERG_URL . 'gutenberg/css/icon-box.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/icon-box', array(
        'attributes'      => array(
            'contWidth'     => array ( 'type' => 'string', 'default' => 'boxed' ),
            'columns'       => array ( 'type' => 'string', 'default' => '4'),
            'title'         => array ( 'type' => 'string' ),
            'icon'          => array ( 'type' => 'string'),
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
            'showLoadMore' => array ('type' => 'boolean', 'default' => false),
        ),
        'editor_script' => 'stm_gutenberg_icon_box',
        'editor_style' => 'stm_gutenberg_icon_box',
        'style' => 'stm_gutenberg_icon_box',
        'script' => 'stm_gutenberg_icon_box_front',
        'render_callback' => 'render_block_stm_gutenberg_icon_box',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_icon_box' );
