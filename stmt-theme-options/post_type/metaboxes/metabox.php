<?php

if (!defined('ABSPATH')) exit; //Exit if accessed directly


class STMT_Metaboxes
{

	function __construct()
	{
		add_action('add_meta_boxes', array($this, 'stmt_to_register_meta_boxes'));
		add_action('admin_enqueue_scripts', array($this, 'stmt_to_scripts'));
		add_action('save_post', array($this, 'stmt_to_save'), 10, 3);
		add_action('wp_ajax_stmt_curriculum', array($this, 'stmt_search_posts'));
		add_action('wp_ajax_stmt_curriculum_create_item', array($this, 'stmt_curriculum_create_item'));

		add_action('wp_ajax_stmt_save_questions', array($this, 'stmt_save_questions'));
		add_action('wp_ajax_stmt_save_title', array($this, 'stmt_save_title'));
		add_action('wp_ajax_stmt_get_image_url', 'STMT_Metaboxes::get_image_url');
	}

	function boxes()
	{
		return apply_filters('stmt_to_page_settings', array(
			'stmt_to_page_settings_fields' => array(
				'post_type' => array('page'),
				'label'     => esc_html__('Page Settings', 'stmt_theme_options'),
			),
            'stmt_to_post_settings_fields' => array(
				'post_type' => array('post'),
				'label'     => esc_html__('Post Settings', 'stmt_theme_options'),
			),
		));
	}

	function get_users()
	{
		$users = array(
			'' => esc_html__('Choose User', 'stmt_theme_options')
		);

		if (!is_admin()) return $users;

		$users_data = get_users();
		foreach ($users_data as $user) {
			$users[$user->ID] = $user->data->user_nicename;
		}

		return $users;
	}

	function fields()
	{

		return apply_filters('stmt_to_page_settings', array(
			'stmt_to_page_settings_fields' => array(
				'section_1' => array(
					'name'   => esc_html__('Page', 'stmt_theme_options'),
					'fields' => array(
						'show_page_title' => array(
							'label' => esc_html__('Show Page Title', 'stmt_theme_options'),
							'type'  => 'checkbox',
						),
						'sidebar'         => array(
							'type'    => 'select',
							'label'   => esc_html__('Select Sidebar Position', 'stmt_theme_options'),
							'options' => array(
								''      => __('Global settings', 'gutenmag'),
								'none'  => __('None', 'stmt_theme_options'),
								'left'  => __('Left', 'stmt_theme_options'),
								'right' => __('Right', 'stmt_theme_options'),
							),
							'value'   => ''
						),
					)
				),
			),
			'stmt_to_post_settings_fields' => array(
				'section_1' => array(
					'name'   => esc_html__('Post Options', 'stmt_theme_options'),
					'fields' => array(
						'sidebar'         => array(
							'type'    => 'select',
							'label'   => esc_html__('Select Sidebar Position', 'stmt_theme_options'),
							'options' => array(
								''      => __('Global settings', 'gutenmag'),
								'none'  => __('None', 'stmt_theme_options'),
								'left'  => __('Left', 'stmt_theme_options'),
								'right' => __('Right', 'stmt_theme_options'),
							),
							'value'   => ''
						),
						'view_template'         => array(
							'type'    => 'select',
							'label'   => esc_html__('Post Template:', 'stmt_theme_options'),
							'options' => array(
								''      => __('Global Template', 'gutenmag'),
								'default'  => __('Post Template Default', 'stmt_theme_options'),
								'style_1'  => __('Post Template 1', 'stmt_theme_options'),
								'style_3' => __('Post Template 2', 'stmt_theme_options'),
								'style_4' => __('Post Template 3', 'stmt_theme_options'),
								'style_5' => __('Post Template 4', 'stmt_theme_options'),
								'style_6' => __('Post Template 5', 'stmt_theme_options'),
							),
							'value'   => ''
						),
                        'show_navigation' => array(
                            'label' => esc_html__('Show navigation', 'stmt_theme_options'),
                            'type'  => 'checkbox',
                        ),
                        'author_posts' => array(
                            'label' => esc_html__('Show Author Block', 'stmt_theme_options'),
                            'type'  => 'checkbox',
                        ),
                        'related_posts' => array(
                            'label' => esc_html__('Related posts', 'stmt_theme_options'),
                            'type'  => 'checkbox',
                        ),
					)
				),
				'section_2' => array(
					'name'   => esc_html__('Video Options', 'stmt_theme_options'),
					'fields' => array(
						'ddc_url'         => array(
							'type'    => 'text',
							'label'   => esc_html__('Dedicated Video Url', 'stmt_theme_options'),
						),
						'embed_code'         => array(
							'type'    => 'textarea',
							'label'   => esc_html__('Embed code', 'stmt_theme_options'),
						),
					)
				),
				'section_3' => array(
					'name'   => esc_html__('Audio Options', 'stmt_theme_options'),
					'fields' => array(
                        'self_hosted'         => array(
                            'type'    => 'text',
                            'label'   => esc_html__('Self-Hosted Url', 'stmt_theme_options'),
                        ),
                        'embed_code_audio'         => array(
                            'type'    => 'textarea',
                            'label'   => esc_html__('Embed code', 'stmt_theme_options'),
                        ),
					)
				),
			),
		));
	}

	function get_fields($metaboxes)
	{
		$fields = array();
		foreach ($metaboxes as $metabox_name => $metabox) {
			foreach ($metabox as $section) {
				foreach ($section['fields'] as $field_name => $field) {
					$sanitize = (!empty($field['sanitize'])) ? $field['sanitize'] : 'stmt_to_save_field';
					$fields[$field_name] = !empty($_POST[$field_name]) ? call_user_func(array($this, $sanitize), $_POST[$field_name], $field_name) : '';
				}
			}
		}

		return $fields;
	}

	function stmt_to_save_field($value)
	{
		return $value;
	}

	function stmt_to_save_number($value)
	{
		return intval($value);
	}

	function stmt_to_save_dates($value, $field_name)
	{
		global $post_id;

		$dates = explode(',', $value);
		if (!empty($dates) and count($dates) > 1) {
			update_post_meta($post_id, $field_name . '_start', $dates[0]);
			update_post_meta($post_id, $field_name . '_end', $dates[1]);
		}

		return $value;
	}

	function stmt_to_register_meta_boxes()
	{
		$boxes = $this->boxes();
		foreach ($boxes as $box_id => $box) {
			$box_name = $box['label'];
			add_meta_box($box_id, $box_name, array($this, 'stmt_to_display_callback'), $box['post_type'], 'normal', 'high', $this->fields());
		}
	}

	function stmt_to_display_callback($post, $metabox)
	{
		$meta = $this->convert_meta($post->ID);
		foreach ($metabox['args'] as $metabox_name => $metabox_data) {
			foreach ($metabox_data as $section_name => $section) {
				foreach ($section['fields'] as $field_name => $field) {
					$default_value = (!empty($field['value'])) ? $field['value'] : '';
					$value = (isset($meta[$field_name])) ? $meta[$field_name] : $default_value;
					if (!empty($value)) {
						switch ($field['type']) {
							case 'dates' :
								$value = explode(',', $value);
								break;
							case 'answers' :
								$value = unserialize($value);
								break;
						}
					}
					$metabox['args'][$metabox_name][$section_name]['fields'][$field_name]['value'] = $value;
				}
			}
		}
		include STMT_TO_DIR . '/post_type/metaboxes/metabox-display.php';
	}

	function convert_meta($post_id)
	{
		$meta = get_post_meta($post_id);
		$metas = array();
		foreach ($meta as $meta_name => $meta_value) {
			$metas[$meta_name] = $meta_value[0];
		}

		return $metas;
	}

	function stmt_to_scripts($hook)
	{
		$v = time();
		$base = STMT_TO_URL . '/post_type/metaboxes/assets/';
		wp_enqueue_media();
		wp_enqueue_script('vue.js', $base . 'js/vue.min.js', array('jquery'), $v);
		wp_enqueue_script('vue-resource.js', $base . 'js/vue-resource.min.js', array('vue.js'), $v);
		wp_enqueue_script('vue2-datepicker.js', $base . 'js/vue2-datepicker.min.js', array('vue.js'), $v);
		wp_enqueue_script('vue-select.js', $base . 'js/vue-select.js', array('vue.js'), $v);
		wp_enqueue_script('vue2-editor.js', $base . 'js/vue2-editor.min.js', array('vue.js'), $v);
		wp_enqueue_script('vue2-color.js', $base . 'js/vue-color.min.js', array('vue.js'), $v);
		wp_enqueue_script('sortable.js', $base . 'js/sortable.min.js', array('vue.js'), $v);
		wp_enqueue_script('vue-draggable.js', $base . 'js/vue-draggable.min.js', array('sortable.js'), $v);
		wp_enqueue_script('vue-multiselect.js', $base . 'js/vue-multiselect.min.js', array('vue.js'), $v);
		wp_enqueue_script('stmt_to_mixins.js', $base . 'js/mixins.js', array('vue.js'), $v);
		wp_enqueue_script('stmt_to_metaboxes.js', $base . 'js/metaboxes.js', array('vue.js'), $v);

		wp_enqueue_style('stmt-to-metaboxes.css', $base . 'css/main.css', array(), $v);
		wp_enqueue_style('stmt-to-multiselect.css', $base . 'css/vue-multiselect.min.css', array(), $v);
		//wp_enqueue_style('stmt-to-icons', STMT_TO_URL . 'assets/icons/style.css', array(), $v);
		wp_enqueue_style('linear-icons', $base . 'css/linear-icons.css', array('stmt-to-metaboxes.css'), $v);

	}

	function stmt_to_post_types()
	{
		return apply_filters('stmt_to_post_types', array(
			'post',
		));
	}

	function stmt_to_save($post_id, $post)
	{

		$post_type = get_post_type($post_id);

		if (!in_array($post_type, $this->stmt_to_post_types())) return;

		$fields = $this->get_fields($this->fields());

		foreach ($fields as $field_name => $field_value) {
			update_post_meta($post_id, $field_name, $field_value);
		}


	}

	function stmt_search_posts()
	{
		$r = array();

		$args = array(
			'posts_per_page' => 10,
		);

		if (!empty($_GET['s'])) {
			$args['s'] = sanitize_text_field($_GET['s']);
		}

		if (!empty($_GET['post_types'])) {
			$args['post_type'] = explode(',', sanitize_text_field($_GET['post_types']));
		}

		if (!empty($_GET['ids'])) {
			$args['post__in'] = explode(',', sanitize_text_field($_GET['ids']));
		}

		if (!empty($_GET['exclude_ids'])) {
			$args['post__not_in'] = explode(',', sanitize_text_field($_GET['exclude_ids']));
		}

		if (!empty($_GET['orderby'])) {
			$args['orderby'] = sanitize_text_field($_GET['orderby']);
		}

		$q = new WP_Query($args);
		if ($q->have_posts()) {
			while ($q->have_posts()) {
				$q->the_post();

				$response = array(
					'id'    => get_the_ID(),
					'title' => get_the_title(),
				);

				if (in_array('stmt-questions', $args['post_type'])) {
					$response = array_merge($response, $this->question_fields($response['id']));
				}

				$r[] = $response;
			}

			wp_reset_postdata();
		}

		$insert_sections = array();
		foreach ($args['post__in'] as $key => $post) {
			if (!is_numeric($post)) {
				$insert_sections[$key] = array('id' => $post, 'title' => $post);
			}
		}

		foreach ($insert_sections as $position => $inserted) {
			array_splice($r, $position, 0, array($inserted));
		}

		wp_send_json($r);
	}

	function get_question_fields()
	{
		return array(
			'type'                 => array(
				'default' => 'single_choice',
			),
			'answers'              => array(
				'default' => array(),
			),
			'question'             => array(),
			'question_explanation' => array(),
			'question_hint'        => array(),
		);
	}

	function question_fields($post_id)
	{
		$fields = $this->get_question_fields();
		$meta = array();

		foreach ($fields as $field_key => $field) {
			$meta[$field_key] = get_post_meta($post_id, $field_key, true);
			$default = (isset($field['default'])) ? $field['default'] : '';
			$meta[$field_key] = (!empty($meta[$field_key])) ? $meta[$field_key] : $default;
		}

		return $meta;
	}

	function stmt_curriculum_create_item()
	{
		$r = array();
		$available_post_types = array('stmt-lessons', 'stmt-quizzes', 'stmt-questions');

		if (!empty($_GET['post_type'])) $post_type = sanitize_text_field($_GET['post_type']);
		if (!empty($_GET['title'])) $title = sanitize_text_field($_GET['title']);

		/*Check if data passed*/
		if (empty($post_type) and empty($title)) return;

		/*Check if available post type*/
		if (!in_array($post_type, $available_post_types)) return;

		$item = array(
			'post_type'   => $post_type,
			'post_title'  => wp_strip_all_tags($title),
			'post_status' => 'publish',
		);

		$r['id'] = wp_insert_post($item);
		$r['title'] = get_the_title($r['id']);

		if ($post_type == 'stmt-questions') {
			$r = array_merge($r, $this->question_fields($r['id']));
		}

		wp_send_json($r);

	}

	function stmt_save_questions()
	{
		$r = array();
		$request_body = file_get_contents('php://input');

		if (!empty($request_body)) {

			$fields = $this->get_question_fields();


			$data = json_decode($request_body, true);

			foreach ($data as $question) {

				if (empty($question['id'])) continue;
				$post_id = $question['id'];

				foreach ($fields as $field_key => $field) {
					if (!empty($question[$field_key])) {
						$r[$field_key] = update_post_meta($post_id, $field_key, $question[$field_key]);
					}
				}
			}
		}
		wp_send_json($r);
	}

	function stmt_save_title()
	{
		if (empty($_GET['id']) and !empty($_GET['title'])) return false;

		$post = array(
			'ID'         => intval($_GET['id']),
			'post_title' => sanitize_text_field($_GET['title']),
		);

		wp_update_post($post);

		wp_send_json($post);
	}

	public static function get_image_url()
	{
		if (empty($_GET['image_id'])) die;
		wp_send_json(wp_get_attachment_url(intval($_GET['image_id'])));
	}
}

new STMT_Metaboxes();

function stmt_to_metaboxes_deps($field, $section_name)
{
	$dependency = '';
	if (empty($field['dependency'])) return $dependency;

	$key = $field['dependency']['key'];
	$compare = $field['dependency']['value'];
	if ($compare === 'not_empty') {
		$dependency = "v-if=data['{$section_name}']['fields']['{$key}']['value']";
	} else {
		$dependency = "v-if=data['{$section_name}']['fields']['{$key}']['value'] == '{$compare}'";
	}

	return $dependency;
}