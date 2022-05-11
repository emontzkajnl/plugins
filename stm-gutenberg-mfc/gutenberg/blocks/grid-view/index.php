<?php
function render_block_stm_gutenberg_grid_view( $attributes ) {

    if( $attributes['showNavigation'] ) {
        global $wp_query;
        global $paged;
    }
    
    $perPage = (!empty($attributes['postsToShow'])) ? $attributes['postsToShow'] : 0;
    $offset = (!empty($attributes['offset'])) ? $attributes['offset'] : 0;
    
    $vs = $attributes['viewStyle'];
    $headerStyle = $attributes['headerStyle'];
    $headingCFSStyle = (!empty($attributes['headingCFS'])) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

    if(get_query_var('page') != 0) {
        $offset = get_query_var('page') - 1;
        $paged = get_query_var('page');
    }

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $perPage,
        'offset' => $offset * $perPage,
        'orderby' => $attributes['orderBy'],
        'order' => $attributes['order'],
    );

    $loadContainer = 'l-b-' . rand(0, 100000);

    $cats = (!empty($attributes['selectCategoriesId'])) ? explode(',', $attributes['selectCategoriesId']) : $attributes['categories'];

    if($attributes['categories'] != '' || !empty($attributes['selectCategoriesId'])) {
        $tax = array (
            array (
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $cats
            )
        );
        $args['tax_query'] = $tax;
    }

    $wp_query = new WP_Query( $args );

    /*if( $attributes['showNavigation'] ) {
        $paged = get_query_var('page');
        
        $foundPosts = $wp_query->found_posts;
    }*/

    if($wp_query->have_posts()) {
        $masonry = '';
        $masonryCats = 'grid_view_category_navigation';
        if($attributes['viewStyle'] == 'style_4' || $attributes['viewStyle'] == 'style_5') $masonry = 'stmt-grid-mosaic';
        if($attributes['viewStyle'] == 'style_4' || $attributes['viewStyle'] == 'style_5') $masonryCats = 'mosaic_grid_view_category_navigation';
        $tabFont = ($attributes['viewStyle'] == 'style_4' || $attributes['viewStyle'] == 'style_5') ? 'heading-font' : 'normal-font';

        $output = '<div class="stm-grid-view-block ' . $vs . ' " ' . $wrapStyle .  '>'; //plwbp
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '<div class="container">' : '';//container
        if( !empty($attributes['selectCategoriesId']) ) {
            $output .= '<div class="headTabsWrap">';
            if (!empty($attributes['title'])) {
                $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $headerStyle . '">';
                $output .= $attributes['title'];
                $output .= '</' . $attributes['headingTag'] . '>';
            }
            $output .= '<ul class="' . $masonryCats . '" data-cols="' . esc_attr((12/$attributes['columns'])) . '" data-view="' . $attributes['viewStyle'] . '">';
            $output .= '<li class="' . esc_attr($tabFont) . ' active"><a href="#" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="0" data-args="' . esc_attr(json_encode($args)) . '" data-columns="' . $attributes['columns'] . '" data-lb="' . esc_attr($loadContainer) . '">' . esc_html__('All', 'stm-gutenberg') . '</a></li>';
            foreach (explode(',', $attributes['selectCategoriesId']) as $k => $val) {
                $term = get_term($val);
                $output .= '<li class="' . esc_attr($tabFont) . '"><a href="' . get_category_link($val) . '" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="0" data-args="' . esc_attr(json_encode($args)) . '" data-columns="' . $attributes['columns'] . '" data-lb="' . esc_attr($loadContainer) . '" data-cat-id="' . esc_attr($val) . '">' . $term->name . '</a></li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        } else {
            if (!empty($attributes['title'])) {
                $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $headerStyle . '">';
                $output .= $attributes['title'];
                $output .= '</' . $attributes['headingTag'] . '>';
            }
        }

        $output .= '<div class="row ' . esc_attr($masonry) . '" id="' . esc_attr($loadContainer) . '">';//stm_g_flex
        $k = 0;
        while($wp_query->have_posts()) {
            $wp_query->the_post();
            $output .= '<div class="col-md-' . (12/$attributes['columns']) . ' col-sm-12 col">';
            ob_start();
            if($attributes['viewStyle'] != 'style_4' && $attributes['viewStyle'] != 'style_5') {
                get_template_part('template-parts/loop/grid-view/grid-view-loop-' . $attributes['viewStyle']);
            } else {
                $style = ($k%4) + 1;
                get_template_part('template-parts/loop/grid-view/' . $attributes['viewStyle'] . '/grid-view-loop-' . $style);
                $k++;
            }
            $output .= ob_get_clean();
            $output .= '</div>';
        }

        $output .= '</div>';//stm_g_flex
        if($attributes['showLoadMore']) {
            $output .= '<div class="stmt-load-more-btn-wrap">';
            $output .= '<button class="button heading-font stmt-grid-load-more" data-load-id="' . esc_attr($loadContainer) . '" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="1" data-args="' . esc_attr(json_encode($args)) . '" data-vs="' . esc_attr($attributes['viewStyle']) . '" data-columns="' . $attributes['columns'] . '">' . esc_html__('Load More', 'stm-gutenberg') . '<i class="icon-sync"></i></button>';
            $output .= '</div>';
        }

        if( $attributes['showNavigation'] ) {
            $output .= '<div class="stmt-navigation-wrap">';
            $output .= get_the_posts_navigation();
            $output .= '</div>';
        }
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '</div>' : '';//container

        $output .= '</div>';//plwbp

        wp_reset_query();
        wp_reset_postdata();

        return $output;
    }

    return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_grid_view() {
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('stm-isotope-js', STM_GUTENBERG_URL . 'assets/js/isotope.pkgd.min.js', 'jquery', STM_GUTENBERG_VER, true);
    wp_enqueue_script('stm-packery-js', STM_GUTENBERG_URL . 'assets/js/packery-mode.pkgd.min.js', 'jquery', STM_GUTENBERG_VER, true);


    wp_register_script('stm_gutenberg_grid_view',
        STM_GUTENBERG_URL . 'gutenberg/js/grid-view.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_grid_view',
        STM_GUTENBERG_URL . 'gutenberg/css/grid-view.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    wp_register_script('stm_gutenberg_grid_view_front',
        STM_GUTENBERG_URL . 'gutenberg/js/front-end/stm_gutenberg_grid_view_front.js',
        array('jquery', 'utils'),
        STM_GUTENBERG_VER,
        true
    );

    register_block_type( 'stm-gutenberg/grid-view', array(
        'attributes'      => array(
            'contWidth'     => array ( 'type' => 'string', 'default' => 'boxed' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'columns'       => array ( 'type' => 'string', 'default' => '4'),
            'categories'    => array ( 'type' => 'string', 'default' => 'all' ),
            'selectCategories' => array ( 'type' => 'string', 'default' => '' ),
            'selectCategoriesId' => array ( 'type' => 'string'),
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
            'showNavigation' => array ('type' => 'boolean', 'default' => false),
        ),
        'editor_script' => 'stm_gutenberg_grid_view',
        'editor_style' => 'stm_gutenberg_grid_view',
        'style' => 'stm_gutenberg_grid_view',
        'script' => 'stm_gutenberg_grid_view_front',
        'render_callback' => 'render_block_stm_gutenberg_grid_view',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_grid_view' );
