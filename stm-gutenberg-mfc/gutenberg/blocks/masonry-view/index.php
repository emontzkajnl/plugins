<?php
function render_block_stm_gutenberg_masonry_view($attributes)
{
    $styles = array(
		'style_1' => array('count' => 5, 'loop' => array('580-500', '285-245', '285-245', '285-245', '285-245')),
		'style_2' => array('count' => 4, 'loop' => array('285-245', '285-245', '580-500', '580-245')),
		'style_3' => array('count' => 3, 'loop' => array('580-500', '580-245', '580-245')),
		'style_4' => array('count' => 8, 'loop' => array('285-245', '285-245', '285-245', '285-245', '285-245', '285-245', '285-245', '285-245')),
		'style_5' => array('count' => 4, 'loop' => array('580-245', '580-245', '580-245', '580-245')),
		'style_6' => array('count' => 5, 'loop' => array('690-470', '230-230', '230-230', '230-230', '230-230')),
		'style_7' => array('count' => 5, 'loop' => array('230-230', '230-230', '230-230', '230-230', '690-470')),
		'style_8' => array('count' => 3, 'loop' => array('1230-500', '615-353', '615-353')),
	);

	$vs = (isset($attributes['viewStyle'])) ? $attributes['viewStyle'] : 'style_1';

	$headingCFSStyle = (isset($attributes['headingCFS']) && !empty($attributes['headingCFS'])) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
	$wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';

	$perPage = (isset($attributes['postsToShow']) && $attributes['postsToShow'] != '') ? $attributes['postsToShow'] : 0;
	$offset = (isset($attributes['offset']) && $attributes['offset'] != '') ? $attributes['offset'] : 0;

	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $perPage,
		'offset'         => $offset * $styles[$attributes['viewStyle']]['count'],
		'orderby'        => $attributes['orderBy'],
		'order'          => $attributes['order'],
		'meta_query'     => array(array('key' => '_thumbnail_id'))
	);

    $loadContainer = 'l-b-' . rand(0, 100000);

    $cats = (!empty($attributes['selectCategoriesId'])) ? explode(',', $attributes['selectCategoriesId']) : $attributes['categories'];

    if($attributes['categories'] != '' || !empty($attributes['selectCategoriesId']) ) {
		$tax = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $cats
			)
		);
		$args['tax_query'] = $tax;
	}

	$featured_posts = new WP_Query($args);

	if ($featured_posts->have_posts()) {

        $masonryCats = 'masonry_view_category_navigation';

		$output = '<div class="stm-masonry-view-block ' . $vs . ' " ' . $wrapStyle . '>'; //plwbp
		$output .= (!empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed') ? '<div class="container">' : '';//container

        if( !empty($attributes['selectCategoriesId']) ) {
            $output .= '<div class="headTabsWrap">';
            if (!empty($attributes['title'])) {
                $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $attributes['headerStyle'] . '">';
                $output .= $attributes['title'];
                $output .= '</' . $attributes['headingTag'] . '>';
            }
            $output .= '<ul class="' . $masonryCats . '" data-view="' . $attributes['viewStyle'] . '">';
            $output .= '<li class="heading-font active"><a href="#" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="0" data-args="' . esc_attr(json_encode($args)) . '" data-lb="' . esc_attr($loadContainer) . '">' . esc_html__('All', 'stm-gutenberg') . '</a></li>';
            foreach (explode(',', $attributes['selectCategoriesId']) as $k => $val) {
                $term = get_term($val);
                $output .= '<li class="heading-font"><a href="' . get_category_link($val) . '" data-p-p="' . esc_attr($attributes['postsToShow']) . '" data-offset="0" data-args="' . esc_attr(json_encode($args)) . '" data-lb="' . esc_attr($loadContainer) . '" data-cat-id="' . esc_attr($val) . '">' . $term->name . '</a></li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        } else {
            if (!empty($attributes['title'])) {
                $output .= '<' . $attributes['headingTag'] . ' ' . $headingCFSStyle . ' class="heading-font block-title ' . $attributes['headerStyle'] . '">';
                $output .= $attributes['title'];
                $output .= '</' . $attributes['headingTag'] . '>';
            }
        }

		$output .= '<div class="stm_gutenberg_masonry" id="' . esc_attr($loadContainer) . '">';//stm_g_flex

		$q = 0;
		while ($featured_posts->have_posts()) {
			if ($q >= $styles[$attributes['viewStyle']]['count']) $q = 0;
			$featured_posts->the_post();
			$output .= '<div class="stm-msnr stm-msnr-' . $styles[$attributes['viewStyle']]['loop'][$q] . '">';
			ob_start();
			get_template_part('template-parts/loop/masonry-view/masonry-view-loop-' . $styles[$attributes['viewStyle']]['loop'][$q]);
			$output .= ob_get_clean();
			$output .= '</div>';
			$q++;
		}

		$output .= '</div>';//stm_g_flex
		$output .= ( !empty($attributes['contWidth']) && $attributes['contWidth'] == 'boxed' ) ? '</div>' : '';//container
		$output .= '</div>';//plwbp

		wp_reset_postdata();

		return $output;
	}

	return __('No Posts', 'stm-gutenberg');
}

function register_block_stm_gutenberg_masonry_view()
{
	//wp_enqueue_script('masonry');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('stm-isotope-js', STM_GUTENBERG_URL . 'assets/js/isotope.pkgd.min.js', 'jquery', STM_GUTENBERG_VER, true);
	wp_enqueue_script('stm-packery-js', STM_GUTENBERG_URL . 'assets/js/packery-mode.pkgd.min.js', 'jquery', STM_GUTENBERG_VER, true);

	$v = (WP_DEBUG) ? time() : '1.1';

	wp_register_script('stm_gutenberg_masonry_view',
		STM_GUTENBERG_URL . 'gutenberg/js/masonry-view.js',
		array('wp-blocks', 'wp-element', 'wp-data'),
		$v,
		true
	);

	wp_register_script('stm_gutenberg_masonry_view_front',
		STM_GUTENBERG_URL . 'gutenberg/js/front-end/stm_gutenberg_masonry_view_front.js',
		array('wp-blocks', 'wp-element', 'wp-data'),
		$v,
		true
	);

	wp_register_style('stm_gutenberg_masonry_view',
		STM_GUTENBERG_URL . 'gutenberg/css/masonry-view.css',
		array('wp-edit-blocks'),
		$v
	);

	register_block_type('stm-gutenberg/masonry-view', array(
		'attributes'      => array(
			'contWidth'      => array('type' => 'string', 'default' => 'boxed'),
			'viewStyle'      => array('type' => 'string', 'default' => 'style_1'),
			'categories'     => array('type' => 'string', 'default' => 'all'),
            'selectCategories' => array ( 'type' => 'string', 'default' => '' ),
            'selectCategoriesId' => array ( 'type' => 'string'),
			'title'          => array('type' => 'string'),
			'headerStyle'    => array('type' => 'string', 'default' => 'general'),
			'headingTag'     => array('type' => 'string', 'default' => 'h1'),
			'headingCFS'     => array('type' => 'string'),
			'offset'         => array('type' => 'string', 'default' => '0',),
			'postsToShow'    => array('type' => 'string', 'default' => '4',),
			'order'          => array('type' => 'string', 'default' => 'desc',),
			'orderBy'        => array('type' => 'string', 'default' => 'date',),
			'margin_top'     => array('type' => 'string'),
			'padding_top'    => array('type' => 'string'),
			'padding_left'   => array('type' => 'string'),
			'margin_left'    => array('type' => 'string'),
			'padding_right'  => array('type' => 'string'),
			'margin_right'   => array('type' => 'string'),
			'padding_bottom' => array('type' => 'string'),
			'margin_bottom'  => array('type' => 'string'),
		),
		'editor_script'   => 'stm_gutenberg_masonry_view',
		'editor_style'    => 'stm_gutenberg_masonry_view',
		'style'           => 'stm_gutenberg_masonry_view',
		'script'          => 'stm_gutenberg_masonry_view_front',
		'render_callback' => 'render_block_stm_gutenberg_masonry_view',
	));
}

add_action('init', 'register_block_stm_gutenberg_masonry_view');
