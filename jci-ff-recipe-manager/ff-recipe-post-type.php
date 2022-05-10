<?php 
function recipe_post_type() {

    $labels = array(
		'name'                  => _x( 'Recipes', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Recipe', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Recipes', 'text_domain' ),
		'name_admin_bar'        => __( 'Recipe', 'text_domain' ),
		'archives'              => __( 'Recipe Archives', 'text_domain' ),
		'attributes'            => __( 'Add a City to This State', 'text_domain' ),
		'parent_item_colon'     => __( 'Select a state:', 'text_domain' ),
		'all_items'             => __( 'All Recipes', 'text_domain' ),
		'add_new_item'          => __( 'Add New Recipe', 'text_domain' ),
		'add_new'               => __( 'Add New Recipe', 'text_domain' ),
		'new_item'              => __( 'New Recipe', 'text_domain' ),
		'edit_item'             => __( 'Edit Recipe', 'text_domain' ),
		'update_item'           => __( 'Update Recipe', 'text_domain' ),
		'view_item'             => __( 'View Recipe', 'text_domain' ),
		'view_items'            => __( 'View Recipes', 'text_domain' ),
		'search_items'          => __( 'Search Recipes', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Recipe', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Recipe', 'text_domain' ),
		'items_list'            => __( 'Recipes list', 'text_domain' ),
		'items_list_navigation' => __( 'Recipes list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Recipe list', 'text_domain' ),
	);
    $args = array(
		'label'                 => __( 'Recipe', 'text_domain' ),
		'description'           => __( 'Recipe imported from Farm Flavor Site', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		// 'taxonomies'            => array( 'place_topic' ),
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		// 'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'			=> true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		// 'capability_type'       => 'page',
		'menu_icon'   			=> 'dashicons-location',
	);
	register_post_type( 'ff_recipe', $args );
}

add_action( 'init', 'recipe_post_type');
