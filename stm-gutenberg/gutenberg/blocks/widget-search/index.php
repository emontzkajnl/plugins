<?php
function render_block_stm_gutenberg_widget_search ($attributes) {
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';


    $widget = '<div class="widget_search_wrapper" ' . $wrapStyle . '>';

    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    $widget .= '<div class="search_container">';
     $widget .= '<div class="beauty_search">';
        $widget .= '<form action="' . esc_url( home_url( '/' ) ) . '" method="get">';
             $widget .= '<div class="searchContainer">';
             $widget .= '<input class="searchBox" type="search" name="s" placeholder="Search for something...">';
             $widget .= '<button type="submit"> <i class="fa fa-search searchIcon"></i> </button>';
             $widget .= '</div>';
        $widget .= '</form>';
     $widget .= '</div>';
    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_search () {

    wp_register_script('stm_gutenberg_widget_search',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-search.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        '1.0',
        true
    );

    wp_register_style('stm_gutenberg_widget_search',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-search.css',
        array( 'wp-edit-blocks' ),
        '1.0'
    );

    register_block_type( 'stm-gutenberg/widget-search', array(
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
        'editor_script' => 'stm_gutenberg_widget_search',
        'editor_style' => 'stm_gutenberg_widget_search',
        'style' => 'stm_gutenberg_widget_search',
        'render_callback' => 'render_block_stm_gutenberg_widget_search',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_search' );