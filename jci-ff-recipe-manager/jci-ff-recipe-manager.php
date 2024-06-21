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
			'title'			=> __('ILFB Recipe Block', 'ff-recipe'),
			'mode'			=> 'preview',
			'description'	=> __('Link out to Farm Flavor Recipe', 'ff-recipe'),
			'render_template'	=> plugin_dir_path(__FILE__) . 'block/recipe-block.php',
			'icon'              => 'layout', 
			'keywords'		=> array()
		));
		acf_register_block_type(array(
			'name'			=> 'ncff_recipe_block',
			'title'			=> __('NCFF Recipe Block', 'ff-recipe'),
			'mode'			=> 'preview',
			'description'	=> __('Link out to Farm Flavor Recipe', 'ff-recipe'),
			'render_template'	=> plugin_dir_path(__FILE__) . 'block/ncff-recipe-block.php',
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

function update_recipes() {
	// if (! $body['data']): // not empty
	// 	echo 'has posts';
	// else: 
	// 	echo 'no posts';
	// endif;
	
	// $current_page = 1;
	// function load_recipes($current_page = 1) {
		$response = wp_remote_get('https://farmflavor.com/wp-json/wp/v2/posts?after=2015-11-07T00:00:00&categories=5,6,19,24,28,25,7,417,8,27,17,18,34,13,31,32,22,14,26,20,9,16,10,11,23,21,35,12', array('timeout' => 130));
		// page=1&per_page=100&
		$body     = json_decode(wp_remote_retrieve_body( $response ), true);
		// echo 'current page is '.$current_page;

		// if (! $body['data']): 
			foreach($body as $b) {

				$this_post = get_posts(array(
					'numberposts'	=> 1,
					'post_status'	=> array('publish, draft'),
					'post_type'			=> 'ff_recipe',
					'meta_key'			=> 'ff_id',
					'meta_value'		=> $b['id']
				));
				if (empty($this_post)) {
					$postarr = array(
						// 'ID'		=> $b['id'],
						'post_date'		=> $b['modified'],
						'post_title'	=>  $b['title']['rendered'],
						'post_status'	=> 'publish',
						'post_type'		=> 'ff_recipe',
						'post_content'	=> $b['post_content'],
						'meta_input'   	=> array(
							'ff_id'			=> $b['id'],
							'recipe_link'	=> $b['link'],
							'recipe_image_link'	=> $b['yoast_head_json']['og_image'][0]['url']
						),
					);
					wp_insert_post($postarr);
					
				} 
			 //}
			// $current_page++;
			// load_recipes($current_page);
		// else:
			// return;
		// endif;
	}

}

add_action('ag_cron_hook', 'update_recipes');

if ( ! wp_next_scheduled( 'ag_cron_hook')) {
  wp_schedule_event( time(), 'daily', 'ag_cron_hook');
}

register_activation_hook(__FILE__, 'ag_activate' );

function ag_activate() {
	update_recipes();
	
}

register_deactivation_hook(__FILE__, 'ag_deactivate' );

function ag_deactivate() {
	$timestamp = wp_next_scheduled('ag_cron_hook' );
	wp_unschedule_event($timestamp,'ag_cron_hook' );
}