<?php
add_filter( 'nav_menu_css_class', 'stm_nav_menu_css_class', 10, 4);

function stm_nav_menu_css_class( $classes, $item, $args, $depth = 0 ) {

	$id = $item->ID;

	//MEGAMENU ONLY ON FIRST LVL
	if(!$depth) {
		$mega = get_post_meta($id, stm_menu_meta('stm_mega'), true);
		if(!empty($mega) and $mega != 'disabled') {
			$classes[] = 'stm_megamenu stm_megamenu__' . $mega;

			$mega_cols = get_post_meta($id, stm_menu_meta('stm_mega_cols'), true);
			if(!empty($mega_cols)) {
				$classes[] = 'stm_megamenu_' . $mega_cols;
			}
		}

		$menuUseLogo = get_post_meta($id, '_menu_item_stm_menu_logo', true);
		if($menuUseLogo == "checked") {
			$classes[] = ' stm_menu_item_logo';
		}

        $objType = $item->object;

        $enableCatMM = get_post_meta($item->ID, '_menu_item_stm_menu_enable_cat_mm', true);
        if( $enableCatMM == 'checked') {

            $catId = get_post_meta( $item->ID, '_menu_item_stm_mega_select_category', true );
            $objId = ($catId != '0') ? $catId : $item->objectId;

            $children = get_terms( 'category', array(
                'parent'    => $objId,
                'hide_empty' => false
            ) );

            if($children) {
                $classes[] = ' stm_menu_item_has_children';
            } else {
                $classes[] = ' stm_menu_item_no_children';
            }

            $postsViewStyle =  get_post_meta( $item->ID, '_menu_item_stm_mega_view_style', true );

            $classes[] = ' stm_menu_item_has_filter_posts stm_menu_vs_' . $postsViewStyle;
        }
    }
	elseif($depth == 1) {
		$mega_col_width = get_post_meta($id, stm_menu_meta('stm_mega_col_width'), true);
		if(!empty($mega_col_width)) {
			$classes[] = 'stm_col_width_' . $mega_col_width;
		}

		$mega_col_width_inside = get_post_meta($id, stm_menu_meta('stm_mega_cols_inside'), true);
		if(!empty($mega_col_width_inside)) {
			$classes[] = 'stm_mega_cols_inside_' . $mega_col_width_inside;
		}
	}
	elseif($depth == 2) {
		$mega_second_col_width = get_post_meta($id, stm_menu_meta('stm_mega_second_col_width'), true);
		if(!empty($mega_second_col_width)) {
			$classes[] = 'stm_mega_second_col_width_' . $mega_second_col_width;
		}

		$image = get_post_meta($id, stm_menu_meta('stm_menu_image'), true);
		$menuTextData = get_post_meta($id, '_menu_item_stm_menu_text_repeater');
		$textarea = get_post_meta($id, stm_menu_meta('stm_mega_textarea'), true);
		if(!empty($image) || $menuTextData != null || !empty($textarea)) {
			$classes[] = 'stm_mega_second_col_width_' . $mega_second_col_width . ' stm_mega_has_info';
		}
	}

    return $classes;
}

add_filter( 'nav_menu_item_title', 'stm_nav_menu_item_title', 10, 4);

function stm_nav_menu_item_title($title, $item, $args, $depth) {
    $id = $item->ID;

    //MEGAMENU ONLY ON 2 AND 3
	$menuUseLogo = get_post_meta($id, '_menu_item_stm_menu_logo', true);

    if(!$depth && $menuUseLogo == "") return $title;

    /*IMAGE BANNER THIRD LVL ONLY*/
    $image = get_post_meta($id, stm_menu_meta('stm_menu_image'), true);
    if($depth == 1 || $depth == 2) {
        if(!empty($image)) {
            $img = '';
            $image = wp_get_attachment_image_src($image, 'full');

            if(!empty($image[0])) {
                $img = '<img alt="' . $title . '" src="' . $image[0] . '" />';
                $title = $img;
            }
        } else {
			if($depth == 2){
				$menuIconData = get_post_meta($id, '_menu_item_stm_menu_icon_repeater');
				if($menuIconData != null) $menuIconData = json_decode($menuIconData[0]);

				$menuTextData = get_post_meta($id, '_menu_item_stm_menu_text_repeater');
				if($menuTextData != null) $menuTextData = json_decode($menuTextData[0]);

				if($menuTextData != null && count($menuTextData) > 0) {
					$title = "";
				}
			}
		}
    }

    if($depth == 2) {
        /*Text field*/
        $textarea = get_post_meta($id, stm_menu_meta('stm_mega_textarea'), true);
        if(!empty($textarea)) {
            $textarea = '<span class="stm_mega_textarea">'.$textarea.'</span>';
            if(!empty($image)) {
            $title = $title . $textarea;
            } else {
                $title = $textarea;
            }
        }

		$menuIconData = get_post_meta($id, '_menu_item_stm_menu_icon_repeater');
        if($menuIconData != null) $menuIconData = json_decode($menuIconData[0]);

		$menuTextData = get_post_meta($id, '_menu_item_stm_menu_text_repeater');
		if($menuTextData != null) $menuTextData = json_decode($menuTextData[0]);

		if($menuTextData != null && count($menuTextData) > 0) {
			$classLi = "normal_font";
			$list = "<ul class='mm-list'>";
			for($q=0;$q<count($menuTextData);$q++) {
				if($menuTextData[$q] != "") {
					$list .= "<li class='" . $classLi . "'><i class='" . $menuIconData[$q] . "'></i>" . $menuTextData[$q] . "</li>";
				}
			}
			$list .= "</ul>";
			$title .= $list;
		}
    }

    /*Icon on both 2 and 3 lvls and not on images*/
    if(empty($image)) {
        $icon = get_post_meta($id, stm_menu_meta('stm_menu_icon'), true);
        if (!empty($icon)) {
            $icon = '<i class="stm_megaicon ' . $icon . '"></i>';
            $title = $icon . $title;
        }
    }

	if($depth == 0 && $menuUseLogo == "checked") {
		$logo_main = get_theme_mod('logo', '');
		$output = '<div class="logo-main">';
		if(empty($logo_main)):
			$output .= '<h2>' . esc_attr(get_bloginfo('name')) . '</h2>';
		else:
			$output .= '<img
                            src="' . esc_url( $logo_main ) . '"
                            style="width: ' . get_theme_mod( 'logo_width', '157' ) . 'px;"
                            title="' . esc_html_e('Home', 'stm-megamenu') . '"
                            alt="' . esc_html_e('Logo', 'stm-megamenu') . '"
								/>';
		endif;
		$output .= '</div>';
		$title = $output;
	}

    return $title;
}

add_filter( 'nav_menu_link_attributes', 'stm_nav_menu_link_attributes', 10, 4);

function stm_nav_menu_link_attributes($atts, $item, $args, $depth) {
    /*ONLY LVL 0*/
    if (!$depth) {
        $id = $item->ID;
        $enableCatMM = get_post_meta($id, '_menu_item_stm_menu_enable_cat_mm', true);

        $bg = get_post_meta($id, stm_menu_meta('stm_menu_bg'), true);

        if(!empty($bg)) {
            $bg = wp_get_attachment_image_src($bg, 'full');
            if(!empty($bg[0])) {
                $atts['data-megabg'] = esc_url($bg[0]);
            }
        }

        if($enableCatMM == 'checked') {

            $selCat = get_post_meta( $item->ID, '_menu_item_stm_mega_select_category', true );
            $filterView =  get_post_meta( $item->ID, '_menu_item_stm_mega_subcat_filter_view', true );

            $catId = ($selCat == '0') ? $item->object_id : $selCat;

            $children = get_terms( 'category', array(
                'parent'    => $selCat,
                'hide_empty' => false
            ) );

            $menuHasChild = array_search('menu-item-has-children', $item->classes);

            if($children || $menuHasChild != 0) {
                $atts['data-has-child'] = 'has_child';
            }

            if($filterView == 'horizontal') {
                $atts['data-has-child'] = 'noChild';
            }

            $atts['class'] = 'stm-mm-parent-load-on-hover';
            $atts['data-cat-id'] = $catId;
        }
    }
    return $atts;
}

add_filter( 'walker_nav_menu_start_el', 'stm_nav_start_el', 10, 4 );

function stm_nav_start_el ($item_output, $item, $depth, $args) {

    $objType = $item->object;
    $objId = $item->object_id;

    $menuPage = get_post_meta($item->ID, '_menu_item_stm_mega_use_post', true);
    $enableCatMM = get_post_meta($item->ID, '_menu_item_stm_menu_enable_cat_mm', true);

    if( $menuPage != '' ) {

        $post = get_post($menuPage);


        $output = '<div class="stm-mega-post-wrap">
            <div class="stm-mega-post">
                ' . apply_filters('the_content', $post->post_content) .  '        
            </div>
        </div>';

        $item_output = $item_output . $output;
    }

    if( $enableCatMM == 'checked') {
        $selectedCat = get_post_meta( $item->ID, '_menu_item_stm_mega_select_category', true );
        $filterView =  get_post_meta( $item->ID, '_menu_item_stm_mega_subcat_filter_view', true );
        $postsViewStyle =  get_post_meta( $item->ID, '_menu_item_stm_mega_view_style', true );

        if( $selectedCat != '' && $selectedCat != 0) {
            $objId = $selectedCat;
        }

        $idUniq = 'stm-mm-uniq-' . rand(10, 100000);

        $output = '<div class="stm-mm-category-filter-wrap stm-mm-' . stm_mm_layout_name() . '-layout-wrap stm-mm-filter-' . esc_attr($filterView) . ' stm-mm-posts-vs-' . $postsViewStyle . '">';
        if(!empty(stm_mm_subcat_list($objId))) {
            $output .= '<ul data-container="' . $idUniq . '">' . stm_mm_subcat_list($objId) . '</ul>';
        }
        $output .= '<div id="' . $idUniq . '" class="stm-mm-posts-container stm-mm-posts-view-style-' . $postsViewStyle . '" data-view-style="' . $postsViewStyle . '"></div>';

        $output .= '<div class="stm-mm-more-this" style="display: none;"><a href="' . get_category_link($objId) . '">' . esc_html__('More From This Category', 'stm-megamenu') . '</a></div>';
        $output .= '</div>';

        $item_output = $item_output . $output;
    }

    return $item_output;
}

function stm_menu_meta($name) {
    return '_menu_item_' . $name;
}


function stm_mm_subcat_list($parent_id) {

    $categories = get_categories(array(
        'child_of'            => $parent_id,
        'current_category'    => 0,
        'depth'               => 0,
        'echo'                => 1,
        'exclude'             => '',
        'exclude_tree'        => '',
        'feed'                => '',
        'feed_image'          => '',
        'feed_type'           => '',
        'hide_empty'          => 0,
        'hide_title_if_empty' => false,
        'hierarchical'        => true,
        'order'               => 'ASC',
        'orderby'             => 'name',
        'separator'           => '<br />',
        'show_count'          => 0,
        'show_option_all'     => '',
        'show_option_none'    => '',
        'style'               => 'list',
        'taxonomy'            => 'category',
        'title_li'            => '',
        'use_desc_for_title'  => 1,
    ));

    $cat_array_walker = new stm_mm_list_walker();
    $cat_array_walker->walk($categories, 4);

    return($cat_array_walker->list);
}


class stm_mm_list_walker extends Walker {

    public $tree_type = 'category';
    public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    var $list = '';

    function start_lvl( &$output, $depth = 0, $args = array() ) {}

    function end_lvl( &$output, $depth = 0, $args = array() ) {}

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

        $class = 'stm-mm-' . $depth . '-level';

        $this->list .= '<li class="' . esc_attr($class) . '"><a href="' . esc_url(get_category_link($category)) . '" class="stm-mm-load-on-hover" data-cat-id="' . esc_attr($category->term_id) . '" data-has-child="has_child">' . $category->name . '<i class="stm-mm-chevron"></i></a></li>';
    }


    function end_el( &$output, $page, $depth = 0, $args = array() ) {}
}
