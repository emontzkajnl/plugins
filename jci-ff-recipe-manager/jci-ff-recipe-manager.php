<?php 
/**
 * Plugin Name: FF Recipe Manager
 * Description: Custom Gutenberg blocks 
 * Text Domain: ff-recipe
 * 
 * register recipe post type? (recipes are normal posts)
 * query posts from the last 24 hours and add to database
 */



 if( ! defined( 'ABSPATH')) {
     exit;
 }


 function recipe_post_type() {

    $labels = array(
		'name'                  => _x( 'Recipes', 'Post Type General Name', 'ff-recipe' ),
		'singular_name'         => _x( 'Recipe', 'Post Type Singular Name', 'ff-recipe' ),
		'menu_name'             => __( 'Recipes', 'ff-recipe' ),
		'name_admin_bar'        => __( 'Recipe', 'ff-recipe' ),
		'archives'              => __( 'Recipe Archives', 'ff-recipe' ),
		'attributes'            => __( 'Add a City to This State', 'ff-recipe' ),
		'parent_item_colon'     => __( 'Select a state:', 'ff-recipe' ),
		'all_items'             => __( 'All Recipes', 'ff-recipe' ),
		'add_new_item'          => __( 'Add New Recipe', 'ff-recipe' ),
		'add_new'               => __( 'Add New Recipe', 'ff-recipe' ),
		'new_item'              => __( 'New Recipe', 'ff-recipe' ),
		'edit_item'             => __( 'Edit Recipe', 'ff-recipe' ),
		'update_item'           => __( 'Update Recipe', 'ff-recipe' ),
		'view_item'             => __( 'View Recipe', 'ff-recipe' ),
		'view_items'            => __( 'View Recipes', 'ff-recipe' ),
		'search_items'          => __( 'Search Recipes', 'ff-recipe' ),
		'not_found'             => __( 'Not found', 'ff-recipe' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ff-recipe' ),
		'featured_image'        => __( 'Featured Image', 'ff-recipe' ),
		'set_featured_image'    => __( 'Set featured image', 'ff-recipe' ),
		'remove_featured_image' => __( 'Remove featured image', 'ff-recipe' ),
		'use_featured_image'    => __( 'Use as featured image', 'ff-recipe' ),
		'insert_into_item'      => __( 'Insert into Recipe', 'ff-recipe' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Recipe', 'ff-recipe' ),
		'items_list'            => __( 'Recipes list', 'ff-recipe' ),
		'items_list_navigation' => __( 'Recipes list navigation', 'ff-recipe' ),
		'filter_items_list'     => __( 'Filter Recipe list', 'ff-recipe' ),
	);
    $args = array(
		'label'                 => __( 'Recipe', 'ff-recipe' ),
		'description'           => __( 'Recipe imported from Farm Flavor Site', 'ff-recipe' ),
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

function register_recipe_block() {
	if( function_exists('acf_register_block_type')) {
		acf_register_block_type(array(
			'name'			=> 'recipe_block',
			'title'			=> __('Farm Flavor Recipe Block', 'ff-recipe'),
			'mode'			=> 'preview',
			'description'	=> __('Link out to Farm Flavor Recipe', 'ff-recipe'),
			'render_template'	=> plugin_dir_path(__FILE__) . 'block/recipe-block.php',
			'icon'              => 'layout', 
			'keywords'		=> array()
		));
	}
}

add_action('acf/init', 'register_recipe_block');

function maximum_api_filter($query_params) {
    $query_params['per_page']["maximum"]=1000;
    return $query_params;
}
add_filter('rest_post_collection_params', 'maximum_api_filter');

$response = wp_remote_get('https://farmflavor.com/wp-json/wp/v2/posts?per_page=100&after=2021-11-07T00:00:00&categories=5,6,19,24,28,25,7,417,8,27,17,18,34,13,31,32,22,14,26,20,9,16,10,11,23,21,35,12');
$body     = json_decode(wp_remote_retrieve_body( $response ), true);
// echo 'start echo<br />';
// foreach($body as $b) {
//     echo 'name is '.$b['title']['rendered'].'<br />';
//     // var_dump($b);
// }
// var_dump(count($body));
