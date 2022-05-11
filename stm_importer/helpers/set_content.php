<?php
function stm_set_content_options( $chosen_template ) {
	/*Set menus*/
    $locations = get_theme_mod('nav_menu_locations');
    $menus = wp_get_nav_menus();

    if (!empty($menus)) {
        foreach ($menus as $menu) {
            if (is_object($menu)) {
                switch ($menu->name) {
                    case 'Primary menu':
                        $locations['primary'] = $menu->term_id;
                        break;
                    case 'Top bar menu':
                        $locations['top_bar'] = $menu->term_id;
                        break;
                    case 'Footer menu':
                        $locations['bottom_menu'] = $menu->term_id;
                        break;
                }
            }
        }
    }

    set_theme_mod('nav_menu_locations', $locations);

	//Set pages
	update_option( 'show_on_front', 'page' );

    $front_page = get_page_by_title('Home page');
    if (isset($front_page->ID)) {
        update_option('page_on_front', $front_page->ID);
    }

    $blog_page = get_page_by_title('Posts');
    if (isset($blog_page->ID)) {
        update_option('page_for_posts', $blog_page->ID);
    }

    $a2aUpdOpt = array(
        'display_in_posts_on_front_page' => -1,
        'display_in_posts_on_archive_pages' => -1,
        'display_in_excerpts' => -1,
        'display_in_posts' => -1,
        'display_in_pages' => -1,
        'display_in_attachments' => -1,
        'display_in_feed' => -1,
        'display_in_cpt_stm_office' => -1,
        'display_in_cpt_sidebar' => -1,
        'display_in_cpt_test_drive_request' => -1,
        'display_in_cpt_listings' => -1,
        'display_in_cpt_product' => -1
    );

    $a2aGetOpt = get_option('addtoany_options');

    if(!empty($a2aGetOpt)) {
        $upd = array_replace($a2aGetOpt, $a2aUpdOpt);
        update_option('addtoany_options', $upd);
    }
}
