<?php
function render_block_stm_gutenberg_widget_category_list ($attributes) {
    $vs = $attributes['viewStyle'];
    $hTag = $attributes['headingTag'];
    $showCount = $attributes['showCount'];
    $hideEmpty = $attributes['hideEmpty'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    if($vs == 'style_1') {
        $catList = wp_list_categories(
            array(
                'title_li' => '',
                'orderby' => 'name',
                'show_count' => $showCount,
                'hideEmpty' => $hideEmpty,
                'hierarchical' => false,
                'echo' => false
            )
        );
    } else {
        $nums = (!empty($attributes['max_nums'])) ? $attributes['max_nums'] : '';

        $args = array(
            'taxonomy'              => 'category',
            'orderby'                => 'name',
            'order'                  => 'ASC',
            'hide_empty'             => true,
            'number'                 => $nums,
            'fields'                 => 'all',
            'count'                  => false,
            'hierarchical'           => true,
        );

        $cats = get_terms($args);

        $li = '';

        foreach ($cats as $k => $cat) {
            $catImgId = get_term_meta($cat->term_id, 'category_img', true);
            $catImg = stmt_gutenmag_get_thumbnail($catImgId, 'stm-gm-270-120');

            $style = (!empty($catImg)) ? 'style="background: url(' . $catImg[0] . ') no-repeat 0 0; "' : '';
            $cls = (!empty($catImg)) ? '' : 'no-img';

            $li .= '<li><a class="' . $cls . '" href="' . get_category_link($cat->term_id) . '" ' . $style . '><span class="normal-font">' . $cat->name . '</span></a></li>';
        }

        $catList = $li;
    }

    $tags = ( !empty( $catList ) ) ? '<ul class="' . $vs . '">' . $catList . '</ul>' : esc_html__('No Categories', 'stm-gutenberg' );

    $widget = '<div class="widget_category_list_wrapper" ' . $wrapStyle . ' >';

    if(!empty($attributes['title'])) {
        $widget .= '<' . $hTag .' class="heading-font block-title ' . esc_attr($attributes['headerStyle']) . '" ' . $headingCFSStyle . '>' . esc_html($attributes['title']) . '</' . $hTag . '>';
    }

    $widget .= '<div class="category_list">';
    $widget .= $tags;
    $widget .= "</div>";
    $widget .= '</div>';

    ob_start();
    echo stmt_gutenmag_print_lmth($widget);
    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_widget_category_list () {

    wp_register_script('stm_gutenberg_widget_category_list',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-category-list.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_category_list',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-category-list.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-category-list', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'showCount'         => array ('type' => 'boolean', 'default' => false),
            'hideEmpty'         => array ('type' => 'boolean', 'default' => true),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'max_nums'  => array('type' => 'string'),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_category_list',
        'editor_style' => 'stm_gutenberg_widget_category_list',
        'style' => 'stm_gutenberg_widget_category_list',
        'render_callback' => 'render_block_stm_gutenberg_widget_category_list',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_category_list' );