<?php
function render_block_stm_gutenberg_list_view( $attributes ) {

    $vs = $attributes['viewStyle'];
    $headingCFSStyle = ( !empty($attributes['headingCFS'])) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    $perPage = (!empty($attributes['postsToShow'])) ? $attributes['postsToShow'] : 0;
    $offset = (!empty($attributes['offset'])) ? $attributes['offset'] : 0;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $perPage,
        'offset' => $offset * $perPage,
        'orderby' => $attributes['orderBy'],
        'order' => $attributes['order'],
    );

    if($attributes['categories'] != '') {
        $tax = array (
            array (
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $attributes['categories']
            )
        );
        $args['tax_query'] = $tax;
    }

    $loadId = 'rp-' . rand(0, 100000);

    $featured_posts = new WP_Query( $args );

    if($featured_posts->have_posts()) {

        $output = '<div class="stm-list-view-block ' . $vs . ' " ' . $wrapStyle . '>'; //plwbp
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '<div class="container">' : '';//container
        if(!empty($attributes['title'])) {
            $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $attributes['headerStyle'] . '">';
            $output .= $attributes['title'];
            $output .= '</' . $attributes['headingTag'] . '>';
        }
        $output .= '<div id="' . esc_attr($loadId) . '" class="row">';//stm_g_flex

        while($featured_posts->have_posts()) {
            $featured_posts->the_post();
            $output .= '<div class="col-md-12 col-sm-12">';
            ob_start();
            get_template_part('template-parts/loop/list-view/list-view-loop-' . $attributes['viewStyle']);
            $output .= ob_get_clean();
            $output .= '</div>';
        }

        $output .= '</div>';//stm_g_flex
        if($attributes['showLoadMore']) {
            $output .= '<div class="stmt-load-more-btn-wrap">';
            $output .= '<button class="button stmt-load-more" data-load-id="' . esc_attr($loadId) . '" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="1" data-args="' . esc_attr(json_encode($args)) . '" data-vs="' . esc_attr($attributes['viewStyle']) . '" data-vt="list">' . esc_html__('Load More', 'stm-gutenberg') . '<i class="icon-sync"></i></button>';
            $output .= '</div>';
        }
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '</div>' : '';//container
        $output .= '</div>';//plwbp

        wp_reset_postdata();

        return $output;
    }

    return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_list_view() {
    wp_register_script('stm_gutenberg_list_view',
        STM_GUTENBERG_URL . 'gutenberg/js/list-view.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_list_view',
        STM_GUTENBERG_URL . 'gutenberg/css/list-view.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/list-view', array(
        'attributes'      => array(
            'contWidth'     => array ( 'type' => 'string', 'default' => 'boxed' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'categories'    => array ( 'type' => 'string', 'default' => 'all' ),
            'title'         => array ( 'type' => 'string' ),
            'headerStyle'  => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'    => array ( 'type' => 'string', 'default' => 'h1' ),
            'headingCFS'    => array ( 'type' => 'string' ),
            'postsToShow'   => array ( 'type' => 'string', 'default' => '5', ),
            'offset'        => array ( 'type' => 'string', 'default' => '0', ),
            'order'         => array ( 'type' => 'string', 'default' => 'desc', ),
            'orderBy'       => array ( 'type' => 'string', 'default' => 'date', ),
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
        'editor_script' => 'stm_gutenberg_list_view',
        'editor_style' => 'stm_gutenberg_list_view',
        'style' => 'stm_gutenberg_list_view',
        'render_callback' => 'render_block_stm_gutenberg_list_view',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_list_view' );
