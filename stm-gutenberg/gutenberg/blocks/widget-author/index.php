<?php
function render_block_stm_gutenberg_widget_author ($attributes) {

    $vs = $attributes['viewStyle'];

    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $authImg = (!empty($attributes['author_img'])) ? $attributes['author_img'] : '';
    $authName = (!empty($attributes['author_name'])) ? $attributes['author_name'] : '';
    $authDesc = (!empty($attributes['author_desc'])) ? $attributes['author_desc'] : '';


    $widget = '<div class="widget_author_wrapper ' . $vs . '" ' . $wrapStyle . '>';

    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    $widget .= '<div class="author">';
    $widget .= '<div class="author-img">';
    $widget .= '<img src="' . $authImg . '" />';
    $widget .= '</div>';
    $widget .= '<div class="author-meta">';
    if(!empty($attributes['author_subtitle'])) $widget .= '<div class="author-subtitle normal-font">' . $attributes['author_subtitle'] . '</div>';
    $widget .= '<h5 class="heading-font">';
    $widget .=  esc_html($authName);
    $widget .= '</h5>';
    $widget .= '<div class="author-desc">';
    $widget .=  esc_html($authDesc);
    $widget .= '</div>';
    $widget .= '</div>';

    $widget .= "</div>";
    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_author () {

    wp_register_script('stm_gutenberg_widget_author',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-author.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_author',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-author.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-author', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'author_img' => array('type' => 'string' ),
            'author_name' => array('type' => 'string' ),
            'author_subtitle' => array('type' => 'string' ),
            'author_desc' => array('type' => 'string' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_author',
        'editor_style' => 'stm_gutenberg_widget_author',
        'style' => 'stm_gutenberg_widget_author',
        'render_callback' => 'render_block_stm_gutenberg_widget_author',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_author' );