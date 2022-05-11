<?php

class STMT_TO_Settings
{
	private static $typography_fonts = array();
	private static $themeOptions = array();

	function __construct()
	{
	    self::stmt_to_get_settings();
		self::set_typography_fonts();

		add_action('admin_menu', array($this, 'stmt_to_settings_page'), 1000);
		add_action('wp_ajax_stmt_save_settings', array($this, 'stmt_save_settings'));
		add_action('wp_enqueue_scripts', array($this, 'stmt_enqueue_custom_fonts'), 160);
		add_action('admin_enqueue_scripts', array($this, 'stmt_enqueue_custom_fonts'), 160);
		add_action('wp_enqueue_scripts', array($this, 'stmt_enqueue_typography_css'), 200);
		add_action('admin_head', array($this, 'generate_admin_custom_css'));
	}

	function stmt_to_settings_page()
	{
		add_menu_page(
			'Theme Options',
			'Theme Options',
			'manage_options',
			'stmt-to-settings',
			array($this, 'stmt_to_settings_page_view'),
			'dashicons-welcome-learn-more',
			5
		);
	}

	public static function stmt_get_post_type_array($post_type, $args = array())
	{
		$r = array(
			'' => esc_html__('Choose Page', 'stmt_theme_options'),
		);

		$default_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish'
		);

		$q = get_posts(wp_parse_args($args, $default_args));

		if (!empty($q)) {
			foreach ($q as $post_data) {
				$r[$post_data->ID] = $post_data->post_title;
			}
		}

		wp_reset_query();

		return $r;
	}

	function stmt_to_settings()
	{
		global $wp_registered_sidebars;
		$sidebars = array('' => esc_html__('Select Sidebar', 'stmt_theme_options'));

		$sidebarPosition = array(
			'no_sidebar' => esc_html__('Without Sidebar', 'stmt_theme_options'),
			'left'       => esc_html__('Left', 'stmt_theme_options'),
			'right'      => esc_html__('Right', 'stmt_theme_options')
		);

		$viewType = array(
			''     => esc_html__('Select View Type', 'stmt_theme_options'),
			'list' => esc_html__('List', 'stmt_theme_options'),
			'grid' => esc_html__('Grid', 'stmt_theme_options'),
		);

		$crop = array(
			'yes' => esc_html__('Yes', 'stmt_theme_options'),
			'no'  => esc_html__('No', 'stmt_theme_options'),
		);

		$postTemplate = array(
			'default' => esc_html__('Default Template', 'stmt_theme_options'),
			'style_1' => esc_html__('Template 1', 'stmt_theme_options'),
			'style_3' => esc_html__('Template 2', 'stmt_theme_options'),
			'style_4' => esc_html__('Template 3', 'stmt_theme_options'),
			'style_5' => esc_html__('Template 4', 'stmt_theme_options'),
			'style_6' => esc_html__('Template 5', 'stmt_theme_options'),
		);

		$pages = $this->stmt_get_post_type_array('page');

		foreach ($wp_registered_sidebars as $k => $val) {
			$sidebars[$val['id']] = $val['name'];
		}

		return apply_filters('stmt_to_settings', array(
			'id'   => 'stmt_to_settings',
			'args' => array(
				'stmt_to_settings' => array(
					'section_1'      => array(
						'name'   => esc_html__('General', 'stmt_theme_options'),
						'fields' => array(
							'primary_color'         => array(
								'type'    => 'color',
								'label'   => esc_html__('Primary Color', 'stmt_theme_options'),
								'columns' => '50'
							),
							'main_bg'               => array(
								'type'    => 'color',
								'label'   => esc_html__('Main Background Color', 'stmt_theme_options'),
								'columns' => '50'
							),
							'max_width_content'     => array(
								'type'    => 'text',
								'label'   => esc_html__('Max width content', 'stmt_theme_options'),
								'columns' => '50'
							),
                            'stmt_post_head_bg'               => array(
                                'type'    => 'color',
                                'label'   => esc_html__('Post Template 3 Head BG Color', 'stmt_theme_options'),
                                'columns' => '50'
                            ),
							'boxed_layout'          => array(
								'type'  => 'checkbox',
								'label' => esc_html__('Boxed layout', 'stmt_theme_options'),
							),
							'boxed_layout_bg'       => array(
								'type'       => 'image',
								'dependency' => array(
									'key'   => 'boxed_layout',
									'value' => 'not_empty'
								),
								'label'      => esc_html__('Boxed Layout Img BG', 'stmt_theme_options'),
							),
                            'home_sticky_sidebar'          => array(
                                'type'  => 'checkbox',
                                'label' => esc_html__('Home Page Sticky Sidebar', 'stmt_theme_options'),
                            ),
							'post_global_template'  => array(
								'type'    => 'select',
								'label'   => esc_html__('Single Post Template', 'stmt_theme_options'),
								'options' => $postTemplate,
								'value'   => 'default'
							),
							'post_crop_image'       => array(
								'type'    => 'select',
								'label'   => esc_html__('Crop Single Post Featured Image', 'stmt_theme_options'),
								'options' => $crop,
								'value'   => 'yes'
							),
							'post_sidebar_id'       => array(
								'type'    => 'select',
								'label'   => esc_html__('Global Sidebar Name', 'stmt_theme_options'),
								'options' => $sidebars,
								'value'   => ''
							),
							'post_sidebar_position' => array(
								'type'    => 'select',
								'label'   => esc_html__('Global Sidebar Position', 'stmt_theme_options'),
								'options' => $sidebarPosition,
								'value'   => 'no_sidebar'
							),
							'posts_view_type'       => array(
								'type'    => 'select',
								'label'   => esc_html__('Posts Page View Type', 'stmt_theme_options'),
								'options' => $viewType,
								'value'   => 'list'
							)
						)
					),
					'section_2'      => array(
						'name'   => esc_html__('Typography', 'stmt_theme_options'),
						'fields' => array(
							'body'                       => array(
								'type'     => 'typography',
								'label'    => esc_html__('Body Font', 'stmt_theme_options'),
								'selector' => 'body, .normal-font'
							),
							'.menu'                      => array(
								'type'     => 'typography',
								'label'    => esc_html__('Menu Font', 'stmt_theme_options'),
								'selector' => '.nav-menu, .menu'
							),
							'.blockquote'                => array(
								'type'     => 'typography',
								'label'    => esc_html__('Quote Font', 'stmt_theme_options'),
								'selector' => 'blockquote, .quote, blockquote .normal-font'
							),
							'default_header_font_family' => array(
								'type'    => 'select',
								'label'   => esc_html__('Default Headings Font Family', 'stmt_theme_options'),
								'options' => stmt_get_google_fonts()
							),
							'default_header_font_color'  => array(
								'type'  => 'color',
								'label' => esc_html__('Default Headings Font Color', 'stmt_theme_options'),
							),
							'h1, .h1'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H1', 'stmt_theme_options'),
							),
							'h2, .h2'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H2', 'stmt_theme_options'),
							),
							'h3, .h3'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H3', 'stmt_theme_options'),
							),
							'h4, .h4'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H4', 'stmt_theme_options'),
							),
							'h5, .h5'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H5', 'stmt_theme_options'),
							),
							'h6, .h6'                    => array(
								'type'  => 'typography',
								'label' => esc_html__('H6', 'stmt_theme_options'),
							),

						)
					),
					'section_header' => array(
						'name'   => esc_html__('Header', 'stmt_theme_options'),
						'fields' => array(
							'enable_crypto_ticker' => array(
								'type'  => 'checkbox',
								'label' => esc_html__('Enable Crypto Ticker', 'stmt_theme_options'),
							),
							'cryptos_api_key'              => array(
								'type'       => 'text',
								'label'      => esc_html__('Coinmarketcap API key', 'stmt_theme_options'),
								'dependency' => array(
									'key'   => 'enable_crypto_ticker',
									'value' => 'not_empty'
								),
								'value'      => ''
							),
							'cryptos'              => array(
								'type'       => 'multiselect',
								'label'      => esc_html__('Select Currencies', 'stmt_theme_options'),
								'dependency' => array(
									'key'   => 'enable_crypto_ticker',
									'value' => 'not_empty'
								),
								'value'      => ''
							),
						)
					),
					'section_3'      => array(
						'name'   => esc_html__('Footer', 'stmt_theme_options'),
						'fields' => array(
							'scroll_top' => array(
								'type'  => 'checkbox',
								'label' => esc_html__('Enable Scroll Top', 'stmt_theme_options'),
                                'columns' => '50'
							),
                            'scroll_arrow_position' => array(
                                'type'    => 'select',
                                'label'   => esc_html__('Scroll Arrow Position', 'stmt_theme_options'),
                                'options' => array('pos_1' => 'Before Footer', 'pos_2' => 'Bottom-Right'),
                                'columns' => '50',
                                'value'   => 'pos_1'
                            ),
							'footer_top_bg' => array(
								'type'    => 'color',
								'label'   => esc_html__('Footer Main Background Color', 'stmt_theme_options'),
								'columns' => '50'
							),
							'footer_main_bg' => array(
								'type'    => 'color',
								'label'   => esc_html__('Footer Bottom Background Color', 'stmt_theme_options'),
								'columns' => '50'
							),
							'footer_logo'  => array(
								'type'  => 'image',
								'label' => esc_html__('Footer Logo', 'stmt_theme_options'),
							),
							'footer_bg_img'  => array(
								'type'  => 'image',
								'label' => esc_html__('Footer Img BG', 'stmt_theme_options'),
							),
							'copyright'      => array(
								'type'  => 'text',
								'label' => esc_html__('Copyright', 'stmt_theme_options'),
							),
							'privacy_link'   => array(
								'type'    => 'text',
								'label'   => esc_html__('Privacy & Cookie policy', 'stmt_theme_options'),
								'columns' => '50'
							),
							'terms_link'     => array(
								'type'    => 'text',
								'label'   => esc_html__('Terms & Conditions', 'stmt_theme_options'),
								'columns' => '50'
							),
						)
					),
					'section_4'      => array(
						'name'   => esc_html__('Socials', 'stmt_theme_options'),
						'fields' => array(
							'socials' => array(
								'type'  => 'socials',
								'label' => 'Social Links'
							)
						)
					),
					'section_5'      => array(
						'name'   => esc_html__('Archive Pages', 'stmt_theme_options'),
						'fields' => array(
							'404_content_bg_img' => array(
								'type'  => 'image',
								'label' => esc_html__('404 Page Content Img BG', 'stmt_theme_options'),
							),
							'categ_head_bg_img'  => array(
								'type'  => 'image',
								'label' => esc_html__('Category Page Header Img BG', 'stmt_theme_options'),
							),
							'categ_view_type'    => array(
								'type'    => 'select',
								'label'   => esc_html__('Category Page View Type', 'stmt_theme_options'),
								'options' => $viewType,
								'value'   => ''
							),
							'tag_head_bg_img'    => array(
								'type'  => 'image',
								'label' => esc_html__('Tags Page Header Img BG', 'stmt_theme_options')
							),
							'tag_view_type'      => array(
								'type'    => 'select',
								'label'   => esc_html__('Tags Page View Type', 'stmt_theme_options'),
								'options' => $viewType,
								'value'   => ''
							),
							'search_head_bg_img' => array(
								'type'  => 'image',
								'label' => esc_html__('Search Page Header Img BG', 'stmt_theme_options'),
							),
							'advert_banner'      => array(
								'type'  => 'image',
								'label' => esc_html__('Advertisement banner', 'stmt_theme_options'),
							),
							'advert_link'        => array(
								'type'  => 'text',
								'label' => esc_html__('Advertisement link', 'stmt_theme_options'),
							)
						)
					),
					'mailchimp' => array(
						'name'   => esc_html__('Mailchimp', 'stmt_theme_options'),
						'fields' => array(
							'mc_api_key'              => array(
								'type'       => 'text',
								'label'      => esc_html__('Mailchimp API key', 'stmt_theme_options'),
								'value'      => ''
							),
							'mc_list_id'              => array(
								'type'       => 'text',
								'label'      => esc_html__('Mailchimp List ID', 'stmt_theme_options'),
								'value'      => ''
							),
						)
					),
				)
			)
		));
	}

	function stmt_to_get_settings()
	{
		self::$themeOptions = get_option('stmt_to_settings', array());
		return self::$themeOptions;
	}

	private static function set_typography_fonts()
	{
		$to = self::$themeOptions;

		if (isset($to['default_header_font_family']) && !empty($to['default_header_font_family'])) self::$typography_fonts[] = $to['default_header_font_family'];

		foreach ($to as $k => $val) {
			$setting = json_decode($val);
			if (!is_null($setting) && is_object($setting)) {
				foreach ($setting as $key => $value) {
					$key = str_replace('"', '', $key);
					if ($key == 'font-family') self::$typography_fonts[] = $value;
				}
			}
		}
	}

	function stmt_to_settings_page_view()
	{
		$metabox = $this->stmt_to_settings();
		$settings = $this->stmt_to_get_settings();

		foreach ($metabox['args']['stmt_to_settings'] as $section_name => $section) {
			foreach ($section['fields'] as $field_name => $field) {
				$default_value = (!empty($field['value'])) ? $field['value'] : '';
				$metabox['args']['stmt_to_settings'][$section_name]['fields'][$field_name]['value'] = (!empty($settings[$field_name])) ? $settings[$field_name] : $default_value;
			}
		}
		require_once(STMT_TO_DIR . '/settings/view/main.php');
	}

	function stmt_save_settings()
	{
		if (empty($_REQUEST['name'])) die;
		$id = sanitize_text_field($_REQUEST['name']);
		$settings = array();
		$request_body = file_get_contents('php://input');
		if (!empty($request_body)) {
			$request_body = json_decode($request_body, true);
			foreach ($request_body as $section_name => $section) {
				foreach ($section['fields'] as $field_name => $field) {
					$settings[$field_name] = $field['value'];
				}
			}
		}

		$update = update_option($id, $settings);
		self::stmt_to_get_settings();

		self::saveTypographyCss();

		do_action('stmt_set_custom_color');

		wp_send_json($update);
	}

	public static function stmt_enqueue_typography_css()
	{
		$upload_dir = wp_upload_dir();

		if (file_exists($upload_dir['basedir'] . '/stmt_to_uploads/typography.css')) {
			wp_enqueue_style('typography-css', $upload_dir['baseurl'] . '/stmt_to_uploads/typography.css', array('bootstrap-css'), '1.0');
		}
	}

	public static function stmt_enqueue_custom_fonts()
	{
		$stmt_custom_fonts = self::$typography_fonts;

		if (empty($stmt_custom_fonts)) {
			return;
		}

		$stmt_custom_fonts = array_unique($stmt_custom_fonts);
		$all_fonts = stmt_google_fonts_array();
		foreach ($stmt_custom_fonts as $font) {
			$font = trim($font);
			if (array_key_exists($font, $all_fonts)) {
				$family[] = urlencode($font . ':' . join(',', stmt_get_google_font_variants($font, $all_fonts[$font]['variants'])));
			}
		}

		if (empty($family)) {
			return;
		} else {
			$request = '//fonts.googleapis.com/css?family=' . implode('|', $family);
		}

		wp_enqueue_style('stm-google-fonts', $request, array(), '1.0');
	}

	public static function saveTypographyCss()
	{
		global $wp_filesystem;

		$customCss = self::generate_custom_css();

		if (empty($wp_filesystem)) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$upload_dir = wp_upload_dir();

		if (!$wp_filesystem->is_dir($upload_dir['basedir'] . '/stmt_to_uploads')) {
			$wp_filesystem->mkdir($upload_dir['basedir'] . '/stmt_to_uploads', FS_CHMOD_DIR);
		}

		if (!empty($customCss)) {
			$css_to_filter = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $customCss);
			$css_to_filter = str_replace(array(
				"\r\n",
				"\r",
				"\n",
				"\t",
				'  ',
				'    ',
				'    '
			), '', $css_to_filter);

			$custom_style_file = $upload_dir['basedir'] . '/stmt_to_uploads/typography.css';

			$wp_filesystem->put_contents($custom_style_file, $css_to_filter, FS_CHMOD_FILE);
		}
	}

	public static function generate_custom_css()
	{
		$to = self::$themeOptions;
		$paramms = array(
			'font-size'   => 'px',
			'line-height' => 'px',
		);

		$inlineCss = '';

		if (isset($to['main_bg'])) $inlineCss .= 'body, .stmt-single-style_2 { background-color: ' . $to['main_bg'] . ' !important; } ';
		if (isset($to['stmt_post_head_bg'])) $inlineCss .= '.header-4-wrap { background-color: ' . $to['stmt_post_head_bg'] . ' !important; } ';
		if (isset($to['max_width_content']) && !empty($to['max_width_content'])) $inlineCss .= '
            .container, .entry-header, .entry-title, .page-header, .entry-footer, 
            .site-info, .post-navigation, .page-navigation, 
            .comments-area, .not-found .page-content, .search .entry-summary, .entry-content > *, .site-content > *:not(.alignwide), #comments { 
                max-width: ' . $to['max_width_content'] . 'px; 
            }
            body.page .site-content .entry-title{ max-width: ' . $to['max_width_content'] . 'px; }
            body .site-content{ max-width: ' . $to['max_width_content'] . 'px; } 
             ';

		if (isset($to['default_header_font_family']) || isset($to['default_header_font_color'])) {
			$inlineCss .= '.heading-font, .heading-title, .block-title, .entry-title {';
			if (isset($to['default_header_font_family'])) $inlineCss .= 'font-family: "' . $to['default_header_font_family'] . '"; ';
			if (isset($to['default_header_font_color'])) $inlineCss .= 'color: ' . str_replace('"', '', $to['default_header_font_color']) . ';';
			$inlineCss .= '} ';
		}

		foreach ($to as $k => $val) {
			$setting = json_decode($val);

			if($k == 'cryptos') continue;

			if (!is_null($setting)) {

				$selector = (isset($setting->selectors) && !empty($setting->selectors)) ? $k . ', ' . $setting->selectors : $k;
				$inlineCss .= $selector . '{';
				foreach ($setting as $key => $value) {
					$key = str_replace('"', '', $key);
					$value = ($key != 'font-family') ? str_replace('"', '', $value) : '"' . $value . '", sans-serif ';

					if ($key != 'selectors' && !empty($value)) {
						$unit = (isset($paramms[$key])) ? $paramms[$key] : '';
						$important = ($key == 'font-family') ? '!important' : '';
						$inlineCss .= $key . ': ' . $value . $unit . ' ' . $important . '; ';
					}
				}
				$inlineCss .= '} ';
			}
		}

		return $inlineCss;
	}

	public static function generate_admin_custom_css()
	{
		$to = self::$themeOptions;
		$paramms = array(
			'font-size'   => 'px',
			'line-height' => 'px',
		);

		$inlineAdminCss = '<style>';

		if (isset($to['main_bg'])) {
			$inlineAdminCss .= '.post-type-post .wp-block-freeform.block-library-rich-text__tinymce, .post-type-page .wp-block-freeform.block-library-rich-text__tinymce, .post-type-post .edit-post-layout__content, .post-type-page .edit-post-layout__content { background-color: ' . $to['main_bg'] . '; } ';
		}

		if (isset($to['default_header_font_family']) || isset($to['default_header_font_color'])) {
			$inlineAdminCss .= '
        .post-type-post .editor-block-list__layout .editor-block-list__block h1,
        .post-type-page .editor-block-list__layout .editor-block-list__block h1,
        .edit-post-visual-editor .editor-post-title__block textarea,
        .post-type-post .editor-block-list__layout .editor-block-list__block h2,
        .post-type-page .editor-block-list__layout .editor-block-list__block h2,
        .post-type-post .editor-block-list__layout .editor-block-list__block h3,
        .post-type-page .editor-block-list__layout .editor-block-list__block h3,
        .post-type-post .editor-block-list__layout .editor-block-list__block h4,
        .post-type-page .editor-block-list__layout .editor-block-list__block h4,
        .post-type-post .editor-block-list__layout .editor-block-list__block h5,
        .post-type-page .editor-block-list__layout .editor-block-list__block h5,
        .post-type-post .editor-block-list__layout .editor-block-list__block h6,
        .post-type-page .editor-block-list__layout .editor-block-list__block h6 {';
			if (isset($to['default_header_font_family'])) $inlineAdminCss .= 'font-family: "' . $to['default_header_font_family'] . '"; ';
			if (isset($to['default_header_font_color'])) $inlineAdminCss .= 'color: ' . str_replace('"', '', $to['default_header_font_color']) . ';';
			$inlineAdminCss .= '}';
		}

		foreach ($to as $k => $val) {
			$setting = json_decode($val);

			if (!is_null($setting) && is_object($setting) && $k != 'socials') {
				$selector = (isset($setting->selectors) && !empty($setting->selectors)) ? $setting->selectors : $k;

				if ($selector == 'body, .normal-font') {
					$selector = '.post-type-post .editor-block-list__layout .editor-block-list__block, .post-type-page .editor-block-list__layout .editor-block-list__block, .editor-styles-wrapper p';
				} else {
					if ($selector == 'h1, .h1') {
						$selector = '.edit-post-visual-editor .editor-post-title__block textarea, ';
						$selector .= '.post-type-post .editor-block-list__layout .editor-block-list__block h1, .post-type-page .editor-block-list__layout .editor-block-list__block h1';
					} else {
						$selector = '.post-type-post .editor-block-list__layout .editor-block-list__block ' . $selector . ', .post-type-page .editor-block-list__layout .editor-block-list__block ' . $selector;
					}
				}

				$inlineAdminCss .= $selector . '{';
				foreach ($setting as $key => $value) {
					$key = str_replace('"', '', $key);
					$value = ($key != 'font-family') ? str_replace('"', '', $value) : $value;

					if ($key != 'selectors' && !empty($value)) {
						$unit = (isset($paramms[$key])) ? $paramms[$key] : '';
						$important = ($key == 'font-family') ? '!important' : '';
						$inlineAdminCss .= $key . ': ' . $value . $unit . ' ' . $important . '; ';
					}
				}
				$inlineAdminCss .= '} ';
			}
		}

		$inlineAdminCss .= '</style>';
		echo $inlineAdminCss;
	}
}

new STMT_TO_Settings;