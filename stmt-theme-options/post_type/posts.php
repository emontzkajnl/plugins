<?php

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly



require_once STMT_TO_DIR . '/post_type/taxonomy_meta/metaboxes.php';
require_once STMT_TO_DIR . '/post_type/metaboxes/metabox.php';

class STMT_TO_Post_Type
{
	function __construct()
	{
		add_action('init', array($this, 'post_types_init'), -1);
	}

	function post_types_init()
	{

		$post_types = $this->post_types();

		foreach ($post_types as $post_type => $post_type_info) {

			$current_user = STMT_TO_User::get_current_user(null, true);
			if(!empty($current_user['id']) and in_array(STMT_TO_Instructor::role(), $current_user['roles'])) {
				$post_type_info['args']['show_in_menu'] = true;
			}

			$add_args = (!empty($post_type_info['args'])) ? $post_type_info['args'] : array();
			$args = $this->post_type_args(
				$this->post_types_labels($post_type_info['single'],
					$post_type_info['plural']
				),
				$post_type,
				$add_args
			);
			register_post_type($post_type, $args);
		}
	}

	function post_types()
	{
		return array(
			'stmt-courses' => array(
				'single'   => 'Course',
				'plural'   => 'Courses',
				'args'   => array(
					'publicly_queryable' => true,
					'has_archive' => false,
					'rewrite' => array(
						'slug' => 'courses',
					),
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author'),
					'capability_type' => 'stmt_to_post',
					'capabilities' => array(
						'publish_posts' => 'publish_stmt_to_posts',
						'edit_posts' => 'edit_stmt_to_posts',
						'delete_posts' => 'delete_stmt_to_posts',
						'edit_post' => 'edit_stmt_to_post',
						'delete_post' => 'delete_stmt_to_post',
						'read_post' => 'read_stmt_to_posts',
						'edit_others_posts' => 'edit_others_stmt_to_posts',
						'delete_others_posts' => 'delete_others_stmt_to_posts',
						'read_private_posts' => 'read_private_stmt_to_posts',
					),
				)
			),
			'stmt-lessons' => array(
				'single' => 'Lesson',
				'plural' => 'Lessons',
				'args'   => array(
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'supports'     => array('title', 'editor', 'thumbnail', 'comments', 'revisions', 'author'),
					'capability_type' => 'stmt_to_post',
					'capabilities' => array(
						'publish_posts' => 'publish_stmt_to_posts',
						'edit_posts' => 'edit_stmt_to_posts',
						'delete_posts' => 'delete_stmt_to_posts',
						'edit_post' => 'edit_stmt_to_post',
						'delete_post' => 'delete_stmt_to_post',
						'read_post' => 'read_stmt_to_posts',
						'edit_others_posts' => 'edit_others_stmt_to_posts',
						'delete_others_posts' => 'delete_others_stmt_to_posts',
						'read_private_posts' => 'read_private_stmt_to_posts',
					),
				)
			),
			'stmt-quizzes' => array(
				'single' => 'Quiz',
				'plural' => 'Quizzes',
				'args'   => array(
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'supports'     => array('title', 'editor', 'thumbnail', 'revisions', 'author'),
					'capability_type' => 'stmt_to_post',
					'capabilities' => array(
						'publish_posts' => 'publish_stmt_to_posts',
						'edit_posts' => 'edit_stmt_to_posts',
						'delete_posts' => 'delete_stmt_to_posts',
						'edit_post' => 'edit_stmt_to_post',
						'delete_post' => 'delete_stmt_to_post',
						'read_post' => 'read_stmt_to_posts',
						'edit_others_posts' => 'edit_others_stmt_to_posts',
						'delete_others_posts' => 'delete_others_stmt_to_posts',
						'read_private_posts' => 'read_private_stmt_to_posts',
					),
				),
			),
			'stmt-questions' => array(
				'single' => 'Question',
				'plural' => 'Questions',
				'args'   => array(
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'capability_type' => 'stmt_to_post',
					'capabilities' => array(
						'publish_posts' => 'publish_stmt_to_posts',
						'edit_posts' => 'edit_stmt_to_posts',
						'delete_posts' => 'delete_stmt_to_posts',
						'edit_post' => 'edit_stmt_to_post',
						'delete_post' => 'delete_stmt_to_post',
						'read_post' => 'read_stmt_to_posts',
						'edit_others_posts' => 'edit_others_stmt_to_posts',
						'delete_others_posts' => 'delete_others_stmt_to_posts',
						'read_private_posts' => 'read_private_stmt_to_posts',
					),
				)
			),
			'stmt-reviews' => array(
				'single' => 'Review',
				'plural' => 'Reviews',
				'args'   => array(
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'supports'     => array('title', 'editor')
				)
			),
			'stmt-orders' => array(
				'single' => 'Order',
				'plural' => 'Orders',
				'args'   => array(
					'show_in_menu' => 'admin.php?page=stmt-to-settings',
					'supports'     => array('title')
				)
			),
		);
	}

	function post_types_labels($singular, $plural)
	{
		$admin_bar_name = (!empty($admin_bar_name)) ? $admin_bar_name : $plural;
		return array(
			'name'               => _x(sprintf('%s', $plural), 'post type general name', 'stmt_theme_options'),
			'singular_name'      => sprintf(_x('Book', 'post type singular name', 'stmt_theme_options'), $singular),
			'menu_name'          => _x(sprintf('%s', $plural), 'admin menu', 'stmt_theme_options'),
			'name_admin_bar'     => sprintf(_x('%s', 'Admin bar ' . $singular . ' name', 'stmt_theme_options'), $admin_bar_name),
			'add_new_item'       => sprintf(__('Add New %s', 'stmt_theme_options'), $singular),
			'new_item'           => sprintf(__('New %s', 'stmt_theme_options'), $singular),
			'edit_item'          => sprintf(__('Edit %s', 'stmt_theme_options'), $singular),
			'view_item'          => sprintf(__('View %s', 'stmt_theme_options'), $singular),
			'all_items'          => sprintf(_x('%s', 'Admin bar ' . $singular . ' name', 'stmt_theme_options'), $admin_bar_name),
			'search_items'       => sprintf(__('Search %s', 'stmt_theme_options'), $plural),
			'parent_item_colon'  => sprintf(__('Parent %s:', 'stmt_theme_options'), $plural),
			'not_found'          => sprintf(__('No %s found.', 'stmt_theme_options'), $plural),
			'not_found_in_trash' => sprintf(__('No %s found in Trash.', 'stmt_theme_options'), $plural),
		);
	}

	function post_type_args($labels, $slug, $args = array())
	{
		$can_edit = (current_user_can('edit_posts'));
		$default_args = array(
			'labels'             => $labels,
			'public'             => $can_edit,
			'publicly_queryable' => $can_edit,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => $slug),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title')
		);

		return wp_parse_args($args, $default_args);
	}

}

new STMT_TO_Post_Type();

require_once STMT_TO_DIR . '/post_type/taxonomies.php';