<?php
function render_block_stm_gutenberg_posts_list_with_big_preview( $attributes ) {

    $vs = $attributes['viewStyle'];
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';
    $headingCFSStyle = ( !empty($attributes['headingCFS'])) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';

    $perPage = ( !empty($attributes['postsToShow']) ) ? $attributes['postsToShow'] : 0;
    $offset = ( !empty($attributes['offset']) ) ? $attributes['offset'] : 0;

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



    $featured_posts = new WP_Query( $args );

    if($featured_posts->have_posts()) {

        $output = '<div class="stm-posts-list-with-big-preview-block ' . $vs . ' " ' . $wrapStyle . '>'; //plwbp
        $output .= ( !empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '<div class="container">' : '';//container
        $output .= '<div class="stm_gutenberg_flex_n-w">';//stm_g_flex
        if ($vs == 'style_1' || $vs == 'style_2') {
            $post = $featured_posts->post;
            $imgSrc = stmt_gutenmag_get_thumbnail(get_post_thumbnail_id($post->ID), 'stm-gm-760-376');
            $cat = get_the_category($post->ID);

            $output .= '<div class="stm-plwbp-preview">';
            $output .= '<div class="stm-plwbp-preview-image-wrap"><a href="' . esc_url(get_the_permalink($post->ID)) . '"><img src="' . $imgSrc[0] . '" alt="' . esc_attr(get_the_title(get_post_thumbnail_id($post->ID))) . '"  /></a></div>';
            $output .= '<div class="stm-plwbp-preview-meta-wrap">';
            $output .= '<h2 class="heading-font"><a href="' . esc_url(get_the_permalink($post->ID)) . '">' . $post->post_title . '</a></h2>';
            $output .= '<ul class="meta">
                            <li>
                                <a href="' . get_category_link($cat[0]->term_id) . '">' . $cat[0]->name . '</a>
                            </li>
                            <li>
                                <span class="stm-gm-icon-calendar"></span>
                                ' . get_the_date() . '
                            </li>
                            <li><span class="stm-gm-icon-testimonials"></span> ' . get_comments_number() . '</li>';

            if(function_exists('stmt_gutenmag_get_post_view_count')) {
                $output .= '<li><span class="stm-gm-icon-eye"></span> ' . stmt_gutenmag_get_post_view_count($post->ID) . '</li>';
            }

            $output .='</ul>';
            $output .= '</div>';
            $output .= '</div>';

            $output .= '<div class="stm-plwbp-list">';
            if(!empty($attributes['title'])) {
                $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $attributes['headerStyle'] .'">';
                $output .= $attributes['title'];
                $output .= '</' . $attributes['headingTag'] . '>';
            }
            $output .= '<ul>';

            ob_start();
            while($featured_posts->have_posts()) {
                $featured_posts->the_post();
                get_template_part('template-parts/loop/posts-list-with-big-preview-loop');
            }

            $output .= ob_get_clean();
            $output .= '</ul>';
            $output .= '</div>';
        }
        $output .= '</div>';//stm_g_flex
        $output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '</div>' : '';//container
        $output .= '</div>';//plwbp

        wp_reset_postdata();

        return $output;
    }

    return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_posts_list_with_big_preview() {
    wp_register_script('stm_gutenberg_posts_list_with_big_preview',
        STM_GUTENBERG_URL . 'gutenberg/js/posts-list-with-big-preview.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_posts_list_with_big_preview',
        STM_GUTENBERG_URL . 'gutenberg/css/posts-list-with-big-preview.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/posts-list-with-big-preview', array(
        'attributes'      => array(
            'contWidth'  => array (
                'type' => 'string',
                'default' => 'boxed'
            ),
            'viewStyle' => array (
                'type' => 'string',
                'default' => 'style_1'
            ),
            'categories'      => array(
                'type' => 'string',
                'default' => 'all'
            ),
            'title'       => array(
                'type' => 'string',
            ),
            'headerStyle'       => array(
                'type' => 'string',
                'default' => 'general'
            ),
            'headingTag'       => array(
                'type' => 'string',
                'default' => 'h1'
            ),
            'headingCFS'       => array(
                'type' => 'string'
            ),
            'postsToShow'     => array(
                'type'    => 'string',
                'default' => '5',
            ),
            'offset'     => array(
                'type'    => 'string',
                'default' => '0',
            ),
            'order'           => array(
                'type'    => 'string',
                'default' => 'desc',
            ),
            'orderBy'         => array(
                'type'    => 'string',
                'default' => 'date',
            ),
            'margin_top' => array (
                'type' => 'string'
            ),
            'padding_top' => array (
                'type' => 'string'
            ),
            'padding_left' => array (
                'type' => 'string'
            ),
            'margin_left' => array (
                'type' => 'string'
            ),
            'padding_right' => array (
                'type' => 'string'
            ),
            'margin_right' => array (
                'type' => 'string'
            ),
            'padding_bottom' => array (
                'type' => 'string'
            ),
            'margin_bottom' => array (
                'type' => 'string'
            )
        ),
        'editor_script' => 'stm_gutenberg_posts_list_with_big_preview',
        'editor_style' => 'stm_gutenberg_posts_list_with_big_preview',
        'style' => 'stm_gutenberg_posts_list_with_big_preview',
        'render_callback' => 'render_block_stm_gutenberg_posts_list_with_big_preview',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_posts_list_with_big_preview' );
