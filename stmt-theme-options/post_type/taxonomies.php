<?php

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly


class STMT_TO_Taxonomies
{
	function __construct()
	{
		add_action('init', array($this, 'taxonomies_init'), -1);
	}

	function taxonomies_init()
	{
		$taxonomies = $this->taxonomies();
		foreach ($taxonomies as $taxonomy => $taxonomy_args) {
			register_taxonomy($taxonomy, $taxonomy_args['post_type'], $taxonomy_args['args']);
		}
	}

	function taxonomies()
	{
		$rewrite_slug = STMT_TO_Options::get_option('courses_categories_slug', 'stmt_to_course_category');
		return apply_filters('stmt_to_taxonomies', array(
			'stmt_to_course_taxonomy' => array(
				'post_type' => 'stmt-courses',
				'args' => array(
					'hierarchical'      => true,
					'labels'            => array(
						'name'              => _x('Courses category', 'taxonomy general name', 'stmt_theme_options'),
						'singular_name'     => _x('Course category', 'taxonomy singular name', 'stmt_theme_options'),
						'search_items'      => __('Search Courses category', 'stmt_theme_options'),
						'all_items'         => __('All Courses category', 'stmt_theme_options'),
						'parent_item'       => __('Parent Course category', 'stmt_theme_options'),
						'parent_item_colon' => __('Parent Course category:', 'stmt_theme_options'),
						'edit_item'         => __('Edit Course category', 'stmt_theme_options'),
						'update_item'       => __('Update Course category', 'stmt_theme_options'),
						'add_new_item'      => __('Add New Course category', 'stmt_theme_options'),
						'new_item_name'     => __('New Course category Name', 'stmt_theme_options'),
						'menu_name'         => __('Course category', 'stmt_theme_options'),
					),
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array('slug' => $rewrite_slug),
				)
			)
		));
	}

}

new STMT_TO_Taxonomies();