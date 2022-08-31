<?php
/*
Plugin Name: Ms Digital Magazine
Plugin URI: http://jnlcom.com
Description: Create digital magazine post type and template for display
Version: 1.0
Author: JCI Developers
Author URI: http://jnlcom.com
License: GPL2
*/

// Create the Magazine post type
function create_post_type() {
	register_post_type( 'magazine',
		array(
			'labels' => array(
				'name' => __( 'Magazines' ),
				'singular_name' => __( 'Magazine' )
			),
		'description' => 'Digital Magzines help integrate third-party provider magazine content.',
		'public' => true,
		'has_archive' => true,
		'menu_position' => 5, 
		'supports' => array('title','editor', 'thumbnail'),
		'register_meta_box_cb' => 'add_digital_magazine_fields',
		'taxonomies' => array('category'),
		)
	);
}

add_action( 'init', 'create_post_type' );