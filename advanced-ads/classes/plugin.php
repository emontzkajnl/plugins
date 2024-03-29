<?php

/**
 * WordPress integration and definitions:
 *
 * - posttypes
 * - taxonomy
 * - textdomain
 */
class Advanced_Ads_Plugin {
	/**
	 * Instance of Advanced_Ads_Plugin
	 *
	 * @var object Advanced_Ads_Plugin
	 */
	protected static $instance;

	/**
	 * Instance of Advanced_Ads_Model
	 *
	 * @var object Advanced_Ads_Model
	 */
	protected $model;

	/**
	 * Plugin options
	 *
	 * @var array $options
	 */
	protected $options;

	/**
	 * Interal plugin options – set by the plugin
	 *
	 * @var     array $internal_options
	 */
	protected $internal_options;

	/**
	 * Default prefix of selectors (id, class) in the frontend
	 * can be changed by options
	 *
	 * @var Advanced_Ads_Plugin
	 */
	const DEFAULT_FRONTEND_PREFIX = 'advads-';

	/**
	 * Frontend prefix for classes and IDs
	 *
	 * @var string $frontend_prefix
	 */
	private $frontend_prefix;

	/**
	 * Advanced_Ads_Plugin constructor.
	 */
	private function __construct() {
		register_activation_hook( ADVADS_BASE, [ $this, 'activate' ] );
		register_deactivation_hook( ADVADS_BASE, [ $this, 'deactivate' ] );
		register_uninstall_hook( ADVADS_BASE, [ 'Advanced_Ads_Plugin', 'uninstall' ] );

		add_action( 'plugins_loaded', [ $this, 'wp_plugins_loaded' ], 20 );
		add_action( 'init', [ $this, 'run_upgrades' ], 9 );
	}

	/**
	 * Get instance of Advanced_Ads_Plugin
	 *
	 * @return Advanced_Ads_Plugin
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get instance of Advanced_Ads_Model
	 *
	 * @param Advanced_Ads_Model $model model to access data.
	 */
	public function set_model( Advanced_Ads_Model $model ) {
		$this->model = $model;
	}

	/**
	 * Execute various hooks after WordPress and all plugins are available
	 */
	public function wp_plugins_loaded() {
		// Load plugin text domain.
		$this->load_plugin_textdomain();

		// activate plugin when new blog is added on multisites // -TODO this is admin-only.
		add_action( 'wpmu_new_blog', [ $this, 'activate_new_site' ] );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_head', [ $this, 'print_head_scripts' ], 7 );
		// higher priority to make sure other scripts are printed before.
		add_action( 'wp_footer', [ $this, 'print_footer_scripts' ], 100 );

		// add short codes.
		add_shortcode( 'the_ad', [ $this, 'shortcode_display_ad' ] );
		add_shortcode( 'the_ad_group', [ $this, 'shortcode_display_ad_group' ] );
		add_shortcode( 'the_ad_placement', [ $this, 'shortcode_display_ad_placement' ] );

		// load widgets.
		add_action( 'widgets_init', [ $this, 'widget_init' ] );

		// Call action hooks for ad status changes.
		add_action( 'transition_post_status', [ $this, 'transition_ad_status' ], 10, 3 );

		// register expired post status.
		Advanced_Ads_Ad_Expiration::register_post_status();

		// if expired ad gets untrashed, revert it to expired status (instead of draft).
		add_filter( 'wp_untrash_post_status', [ Advanced_Ads_Ad_Expiration::class, 'wp_untrash_post_status' ], 10, 3 );

		// load display conditions.
		Advanced_Ads_Display_Conditions::get_instance();
		new Advanced_Ads_Frontend_Checks();
		new Advanced_Ads_Compatibility();
		Advanced_Ads_Ad_Health_Notices::get_instance(); // load to fetch notices.
	}

	/**
	 * Run upgrades.
	 *
	 * Compatibility with the Piklist plugin that has a function hooked to `posts_where` that access $GLOBALS['wp_query'].
	 * Since `Advanced_Ads_Upgrades` applies `posts_where`: (`Advanced_Ads_Admin_Notices::get_instance()` >
	 * `Advanced_Ads::get_number_of_ads()` > new WP_Query > ... 'posts_where') this function is hooked to `init` so that `$GLOBALS['wp_query']` is instantiated.
	 */
	public function run_upgrades() {
		/**
		 * Run upgrades, if this is a new version or version does not exist.
		 */
		$internal_options = $this->internal_options();

		if ( ! defined( 'DOING_AJAX' ) && ( ! isset( $internal_options['version'] ) || version_compare( $internal_options['version'], ADVADS_VERSION, '<' ) ) ) {
			new Advanced_Ads_Upgrades();
		}
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( $this->get_plugin_slug() . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), ADVADS_VERSION);
	}

	/**
	 * Return the plugin slug.
	 *
	 * @return   string plugin slug variable.
	 */
	public function get_plugin_slug() {
		return ADVADS_SLUG;
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 */
	public function enqueue_scripts() {
		if ( advads_is_amp() ) {
			return;
		}
		// wp_enqueue_script( $this->get_plugin_slug() . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery'), ADVADS_VERSION);

		wp_register_script(
			$this->get_plugin_slug() . '-advanced-js',
			sprintf( '%spublic/assets/js/advanced%s.js', ADVADS_BASE_URL, defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ),
			[ 'jquery' ],
			ADVADS_VERSION,
			false
		);

		$privacy                    = Advanced_Ads_Privacy::get_instance();
		$privacy_options            = $privacy->options();
		$privacy_options['enabled'] = ! empty( $privacy_options['enabled'] );
		$privacy_options['state']   = $privacy->get_state();

		wp_localize_script(
			$this->get_plugin_slug() . '-advanced-js',
			'advads_options',
			[
				'blog_id' => get_current_blog_id(),
				'privacy' => $privacy_options,
			]
		);

		$activated_js = apply_filters( 'advanced-ads-activate-advanced-js', isset( $this->options()['advanced-js'] ) );

		if ( $activated_js || ! empty( $_COOKIE['advads_frontend_picker'] ) ) {
			wp_enqueue_script( $this->get_plugin_slug() . '-advanced-js' );
		}

		wp_register_script(
			$this->get_plugin_slug() . '-frontend-picker',
			sprintf( '%spublic/assets/js/frontend-picker%s.js', ADVADS_BASE_URL, defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ),
			[ 'jquery', $this->get_plugin_slug() . '-advanced-js' ],
			ADVADS_VERSION,
			false
		);

		if ( ! empty( $_COOKIE['advads_frontend_picker'] ) ) {
			wp_enqueue_script( $this->get_plugin_slug() . '-frontend-picker' );
		}
	}

	/**
	 * Print public-facing JavaScript in the HTML head.
	 */
	public function print_head_scripts() {
		$short_url   = self::get_short_url();
		$attribution = '<!-- ' . $short_url . ' is managing ads with Advanced Ads%1$s%2$s -->';
		$version     = self::is_new_user( 1585224000 ) ? ' ' . ADVADS_VERSION : '';
		$plugin_url  = self::get_group_by_url( $short_url, 'a' ) ? ' – ' . ADVADS_URL : '';
		// escaping would break HTML comment tags so we disable checks here.
		// phpcs:ignore
		echo apply_filters( 'advanced-ads-attribution', sprintf( $attribution, $version, $plugin_url ) );

		if ( advads_is_amp() ) {
			return;
		}

		ob_start();
		?>
		<script id="<?php echo esc_attr( $this->get_frontend_prefix() ); ?>ready">
			<?php
			readfile( sprintf(
				'%spublic/assets/js/ready%s.js',
				ADVADS_BASE_PATH,
				defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min'
			) );
			?>
		</script>
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaping would break the HTML
		echo Advanced_Ads_Utils::get_inline_asset( ob_get_clean() );
	}

	/**
	 * Print inline scripts in wp_footer.
	 */
	public function print_footer_scripts() {
		if ( advads_is_amp() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaping would break the HTML
		echo Advanced_Ads_Utils::get_inline_asset(
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- we're getting the contents of a local file
			sprintf( '<script>%s</script>', file_get_contents( sprintf(
				'%spublic/assets/js/ready-queue%s.js',
				ADVADS_BASE_PATH,
				defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min'
			) ) )
		);
	}

	/**
	 * Register the Advanced Ads widget
	 */
	public function widget_init() {
		register_widget( 'Advanced_Ads_Widget' );
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param int $blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		$this->single_activate();
		restore_current_blog();
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 */
	protected function single_activate() {
		// $this->post_types_rewrite_flush();
		// -TODO inform modules
		$this->create_capabilities();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 */
	protected function single_deactivate() {
		// -TODO inform modules
		$this->remove_capabilities();
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'advanced-ads', false, ADVADS_BASE_DIR . '/languages' );
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {
				// get all blog ids.
				global $wpdb;
				$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
				$original_blog_id = $wpdb->blogid;

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_activate();
				}

				switch_to_blog( $original_blog_id );
			} else {
				$this->single_activate();
			}
		} else {
			$this->single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide true if Advanced Ads should be disabled network-wide.
	 *
	 * True if WPMU superadmin uses
	 * "Network Deactivate" action, false if
	 * WPMU is disabled or plugin is
	 * deactivated on an individual blog.
	 */
	public function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {
				// get all blog ids.
				global $wpdb;
				$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
				$original_blog_id = $wpdb->blogid;

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_deactivate();
				}

				switch_to_blog( $original_blog_id );
			} else {
				$this->single_deactivate();
			}
		} else {
			$this->single_deactivate();
		}
	}

	
	/**
	 * Shortcode to include ad in frontend
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string ad content.
	 */
	public function shortcode_display_ad( $atts ) {
		$atts = is_array( $atts ) ? $atts : [];
		$id   = isset( $atts['id'] ) ? (int) $atts['id'] : 0;
		// check if there is an inline attribute with or without value.
		if ( isset( $atts['inline'] ) || in_array( 'inline', $atts, true ) ) {
			$atts['inline_wrapper_element'] = true;
		}
		$atts = $this->prepare_shortcode_atts( $atts );

		// use the public available function here.
		return get_ad( $id, $atts );
	}

	/**
	 * Shortcode to include ad from an ad group in frontend
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string ad group content.
	 */
	public function shortcode_display_ad_group( $atts ) {
		$atts = is_array( $atts ) ? $atts : [];
		$id   = isset( $atts['id'] ) ? (int) $atts['id'] : 0;
		$atts = $this->prepare_shortcode_atts( $atts );

		// use the public available function here.
		return get_ad_group( $id, $atts );
	}

	/**
	 * Shortcode to display content of an ad placement in frontend
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string ad placement content.
	 */
	public function shortcode_display_ad_placement( $atts ) {
		$atts = is_array( $atts ) ? $atts : [];
		$id   = isset( $atts['id'] ) ? (string) $atts['id'] : '';
		$atts = $this->prepare_shortcode_atts( $atts );

		// use the public available function here.
		return get_ad_placement( $id, $atts );
	}

	/**
	 * Prepare shortcode attributes.
	 *
	 * @param array $atts array with strings.
	 *
	 * @return array
	 */
	private function prepare_shortcode_atts( $atts ) {
		$result = [];

		/**
		 * Prepare attributes by converting strings to multi-dimensional array
		 * Example: [ 'output__margin__top' => 1 ]  =>  ['output']['margin']['top'] = 1
		 */
		if ( ! defined( 'ADVANCED_ADS_DISABLE_CHANGE' ) || ! ADVANCED_ADS_DISABLE_CHANGE ) {
			foreach ( $atts as $attr => $data ) {
				$levels = explode( '__', $attr );
				$last   = array_pop( $levels );

				$cur_lvl = &$result;

				foreach ( $levels as $lvl ) {
					if ( ! isset( $cur_lvl[ $lvl ] ) ) {
						$cur_lvl[ $lvl ] = [];
					}

					$cur_lvl = &$cur_lvl[ $lvl ];
				}

				$cur_lvl[ $last ] = $data;
			}

			$result = array_diff_key(
				$result,
				[
					'id'      => false,
					'blog_id' => false,
					'ad_args' => false,
				]
			);
		}

		// Ad type: 'content' and a shortcode inside.
		if ( isset( $atts['ad_args'] ) ) {
			$result = array_merge( $result, json_decode( urldecode( $atts['ad_args'] ), true ) );

		}

		return $result;
	}

	/**
	 * Return plugin options
	 * these are the options updated by the user
	 *
	 * @return array $options
	 */
	public function options() {
		// we can’t store options if WPML String Translations is enabled, or it would not translate the "Ad Label" option.
		if ( ! isset( $this->options ) || class_exists( 'WPML_ST_String' ) ) {
			$this->options = get_option( ADVADS_SLUG, [] );
		}

		// allow to change options dynamically
		$this->options = apply_filters( 'advanced-ads-options', $this->options );

		return $this->options;
	}

	/**
	 * Update plugin options (not for settings page, but if automatic options are needed)
	 *
	 * @param array $options new options.
	 */
	public function update_options( array $options ) {
		// do not allow to clear options.
		if ( [] === $options ) {
			return;
		}

		$this->options = $options;
		update_option( ADVADS_SLUG, $options );
	}

	/**
	 * Return internal plugin options
	 * these are options set by the plugin
	 *
	 * @return array $options
	 */
	public function internal_options() {
		if ( ! isset( $this->internal_options ) ) {
			$defaults               = [
				'version'   => ADVADS_VERSION,
				'installed' => time(), // when was this installed.
			];
			$this->internal_options = get_option( ADVADS_SLUG . '-internal', [] );

			// save defaults.
			if ( [] === $this->internal_options ) {
				$this->internal_options = $defaults;
				$this->update_internal_options( $this->internal_options );

				self::get_instance()->create_capabilities();
			}

			// for versions installed prior to 1.5.3 set installed date for now.
			if ( ! isset( $this->internal_options['installed'] ) ) {
				$this->internal_options['installed'] = time();
				$this->update_internal_options( $this->internal_options );
			}
		}

		return $this->internal_options;
	}

	/**
	 * Update internal plugin options
	 *
	 * @param array $options new internal options.
	 */
	public function update_internal_options( array $options ) {
		// do not allow to clear options.
		if ( [] === $options ) {
			return;
		}

		$this->internal_options = $options;
		update_option( ADVADS_SLUG . '-internal', $options );
	}

	/**
	 * Get prefix used for frontend elements
	 *
	 * @return string
	 */
	public function get_frontend_prefix() {
		if ( isset( $this->frontend_prefix ) ) {
			return $this->frontend_prefix;
		}

		$options = $this->options();

		if ( ! isset( $options['front-prefix'] ) ) {
			if ( isset( $options['id-prefix'] ) ) {
				// deprecated: keeps widgets working that previously received an id based on the front-prefix.
				$frontend_prefix = $options['id-prefix'];
			} else {
				$frontend_prefix = preg_match( '/[A-Za-z][A-Za-z0-9_]{4}/', parse_url( get_home_url(), PHP_URL_HOST ), $result )
					? $result[0] . '-'
					: self::DEFAULT_FRONTEND_PREFIX;
			}
		} else {
			$frontend_prefix = $options['front-prefix'];
		}
		/**
		 * Applying the filter here makes sure that it is the same frontend prefix for all
		 * calls on this page impression
		 *
		 * @param string $frontend_prefix
		 */
		$this->frontend_prefix = (string) apply_filters( 'advanced-ads-frontend-prefix', $frontend_prefix );
		$this->frontend_prefix = $this->sanitize_frontend_prefix( $frontend_prefix );

		return $this->frontend_prefix;
	}

	/**
	 * Sanitize the frontend prefix to result in valid HTML classes.
	 * See https://www.w3.org/TR/selectors-3/#grammar for valid tokens.
	 *
	 * @param string $prefix The HTML class to sanitize.
	 * @param string $fallback The fallback if the class is invalid.
	 *
	 * @return string
	 */
	public function sanitize_frontend_prefix( $prefix, $fallback = '' ) {
		$prefix   = sanitize_html_class( $prefix );
		$nonascii = '[^\0-\177]';
		$unicode  = '\\[0-9a-f]{1,6}(\r\n|[ \n\r\t\f])?';
		$escape   = sprintf( '%s|\\[^\n\r\f0-9a-f]', $unicode );
		$nmstart  = sprintf( '[_a-z]|%s|%s', $nonascii, $escape );
		$nmchar   = sprintf( '[_a-z0-9-]|%s|%s', $nonascii, $escape );

		if ( ! preg_match( sprintf( '/-?(?:%s)(?:%s)*/i', $nmstart, $nmchar ), $prefix, $matches ) ) {
			return $fallback;
		}

		return $matches[0];
	}

	/**
	 * Get priority used for injection inside content
	 */
	public function get_content_injection_priority() {
		$options = $this->options();

		return isset( $options['content-injection-priority'] ) ? (int) $options['content-injection-priority'] : 100;
	}

	/**
	 * Returns the capability needed to perform an action
	 *
	 * @param string $capability a capability to check, can be internal to Advanced Ads.
	 *
	 * @return string $capability a valid WordPress capability.
	 */
	public static function user_cap( $capability = 'manage_options' ) {

		global $advanced_ads_capabilities;

		// admins can do everything.
		// is also a fallback if no option or more specific capability is given.
		if ( current_user_can( 'manage_options' ) ) {
			return 'manage_options';
		}

		return apply_filters( 'advanced-ads-capability', $capability );
	}

	/**
	 * Create roles and capabilities
	 */
	public function create_capabilities() {
		if ( $role = get_role( 'administrator' ) ) {
			$role->add_cap( 'advanced_ads_manage_options' );
			$role->add_cap( 'advanced_ads_see_interface' );
			$role->add_cap( 'advanced_ads_edit_ads' );
			$role->add_cap( 'advanced_ads_manage_placements' );
			$role->add_cap( 'advanced_ads_place_ads' );
		}
	}

	/**
	 * Remove roles and capabilities
	 */
	public function remove_capabilities() {
		if ( $role = get_role( 'administrator' ) ) {
			$role->remove_cap( 'advanced_ads_manage_options' );
			$role->remove_cap( 'advanced_ads_see_interface' );
			$role->remove_cap( 'advanced_ads_edit_ads' );
			$role->remove_cap( 'advanced_ads_manage_placements' );
			$role->remove_cap( 'advanced_ads_place_ads' );
		}
	}

	/**
	 * Fired when the plugin is uninstalled.
	 */
	public static function uninstall() {
		$advads_options = Advanced_Ads::get_instance()->options();

		if ( ! empty( $advads_options['uninstall-delete-data'] ) ) {
			global $wpdb;
			$main_blog_id = $wpdb->blogid;

			// Delete assets (main blog).
			Advanced_Ads_Ad_Blocker_Admin::get_instance()->clear_assets();
			Advanced_Ads::get_instance()->create_post_types();

			if ( ! is_multisite() ) {
				self::get_instance()->uninstall_single();
			} else {
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::get_instance()->uninstall_single();
				}
				switch_to_blog( $main_blog_id );
			}


		}

	}

	/**
	 * Fired for each blog when the plugin is uninstalled.
	 */
	protected function uninstall_single() {
		global $wpdb;

		// Ads.
		$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", Advanced_Ads::POST_TYPE_SLUG ) );

		if ( $post_ids ) {
			$wpdb->delete(
				$wpdb->posts,
				[ 'post_type' => Advanced_Ads::POST_TYPE_SLUG ],
				[ '%s' ]
			);

			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE post_id IN( %s )", implode( ',', $post_ids ) ) );
		}

		// Groups.
		$term_ids = $wpdb->get_col( $wpdb->prepare( "SELECT t.term_id FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s", Advanced_Ads::AD_GROUP_TAXONOMY ) );

		foreach ( $term_ids as $term_id ) {
			wp_delete_term( $term_id, Advanced_Ads::AD_GROUP_TAXONOMY );
		}

		delete_option( 'advanced-ads' );
		delete_option( 'advanced-ads-internal' );
		delete_option( 'advanced-ads-notices' );
		delete_option( 'advads-ad-groups' );
		delete_option( 'advanced_ads_groups_children' );
		delete_option( 'advads-ad-weights' );
		delete_option( 'advanced_ads_ads_txt' );
		delete_option( 'advanced-ads-ad-health-notices' );
		delete_option( 'advanced-ads-adsense' );
		delete_option( 'advanced_ads_adsense_report_domain' );
		delete_option( 'advanced_ads_adsense_report_unit' );
		delete_option( 'advanced-ads-adsense-dashboard-filter' );
		delete_option( 'advanced-ads-adsense-mapi' );
		delete_option( 'advanced-ads-licenses' );
		delete_option( 'advanced-ads-ab-module' );
		delete_option( 'widget_' . Advanced_Ads_Widget::get_base_id() );
		delete_option( 'advads-ads-placements' );

		// User metadata.
		delete_metadata( 'user', null, 'advanced-ads-hide-wizard', '', true );
		delete_metadata( 'user', null, 'advanced-ads-subscribed', '', true );
		delete_metadata( 'user', null, 'advanced-ads-ad-list-screen-options', '', true );
		delete_metadata( 'user', null, 'advanced-ads-admin-settings', '', true );
		delete_metadata( 'user', null, 'advanced-ads-role', '', true );
		delete_metadata( 'user', null, 'edit_advanced_ads_per_page', '', true );
		delete_metadata( 'user', null, 'meta-box-order_advanced_ads', '', true );
		delete_metadata( 'user', null, 'screen_layout_advanced_ads', '', true );
		delete_metadata( 'user', null, 'closedpostboxes_advanced_ads', '', true );
		delete_metadata( 'user', null, 'metaboxhidden_advanced_ads', '', true );

		// Post metadata.
		delete_metadata( 'post', null, '_advads_ad_settings', '', true );

		// Transients.
		delete_transient( 'advanced-ads_add-on-updates-checked' );

		do_action( 'advanced-ads-uninstall' );

		wp_cache_flush();
	}

	/**
	 * Check if any add-on is activated
	 *
	 * @return bool true if there is any add-on activated
	 */
	public static function any_activated_add_on() {
		return ( defined( 'AAP_VERSION' )    // Advanced Ads Pro.
				 || defined( 'AAGAM_VERSION' )    // Google Ad Manager.
				 || defined( 'AASA_VERSION' )    // Selling Ads.
				 || defined( 'AAT_VERSION' )        // Tracking.
				 || defined( 'AASADS_VERSION' )  // Sticky Ads.
				 || defined( 'AAR_VERSION' )        // Responsive Ads.
				 || defined( 'AAPLDS_VERSION' )  // PopUp and Layer Ads.
		);
	}

	/**
	 * Get the correct support URL: wp.org for free users and website for those with any add-on installed
	 *
	 * @param string $utm add UTM parameter to the link leading to https://wpadvancedads.com, if given.
	 *
	 * @return string URL.
	 */
	public static function support_url( $utm = '' ) {

		$utm = empty( $utm ) ? '?utm_source=advanced-ads&utm_medium=link&utm_campaign=support' : $utm;
		if ( self::any_activated_add_on() ) {
			$url = ADVADS_URL . 'support/' . $utm . '-with-addons';
		} else {
			$url = ADVADS_URL . 'support/' . $utm . '-free-user';
		}

		return $url;
	}

	/**
	 * Create a random group
	 *
	 * @param string $url optional parameter.
	 * @param string $ex group.
	 *
	 * @return bool
	 */
	public static function get_group_by_url( $url = '', $ex = 'a' ) {

		$url = self::get_short_url( $url );

		$code = (int)substr( md5( $url ), - 1 );

		switch ( $ex ) {
			case 'b':
				return ( $code & 2 ) >> 1; // returns 1 or 0.
			case 'c':
				return ( $code & 4 ) >> 2; // returns 1 or 0.
			case 'd':
				return ( $code & 8 ) >> 3; // returns 1 or 0.
			default:
				return $code & 1; // returns 1 or 0.
		}
	}

	/**
	 * Check if user started after a given date
	 *
	 * @param integer $timestamp time stamp.
	 *
	 * @return bool true if user is added after timestamp.
	 */
	public static function is_new_user( $timestamp = 0 ) {

		// allow admins to see version for new users in any case.
		if ( current_user_can( self::user_cap( 'advanced_ads_manage_options' ) )
			&& isset( $_REQUEST['advads-ignore-timestamp'] ) ) {
			return true;
		}

		$timestamp = absint( $timestamp );

		$options   = self::get_instance()->internal_options();
		$installed = isset( $options['installed'] ) ? $options['installed'] : 0;

		return ( $installed >= $timestamp );
	}

	/**
	 * Show stuff to new users only.
	 *
	 * @param integer $timestamp time after which to show whatever.
	 * @param string  $group optional group.
	 *
	 * @return bool true if user enabled after given timestamp.
	 */
	public static function show_to_new_users( $timestamp, $group = 'a' ) {

		return ( self::get_group_by_url( null, $group ) && self::is_new_user( $timestamp ) );
	}

	/**
	 * Get short version of home_url()
	 * remove protocol and www
	 * remove slash
	 *
	 * @param string $url URL to be shortened.
	 *
	 * @return string
	 */
	public static function get_short_url( $url = '' ) {

		$url = empty( $url ) ? home_url() : $url;

		// strip protocols.
		if ( preg_match( '/^(\w[\w\d]*:\/\/)?(www\.)?(.*)$/', trim( $url ), $matches ) ) {
			$url = $matches[3];
		}

		// strip slashes.
		$url = trim( $url, '/' );

		return $url;
	}

	/**
	 * Return Advanced Ads logo in base64 format for use in WP Admin menu.
	 *
	 * @return string
	 */
	public static function get_icon_svg() {
		return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE4LjEuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJFYmVuZV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDY0Ljk5MyA2NS4wMjQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDY0Ljk5MyA2NS4wMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIHN0eWxlPSJmaWxsOiNFNEU0RTQ7IiBkPSJNNDYuNTcxLDI3LjY0MXYyMy4xMzNIMTQuMjVWMTguNDUzaDIzLjExOGMtMC45NTYtMi4xODMtMS40OTQtNC41OS0xLjQ5NC03LjEyNg0KCWMwLTIuNTM1LDAuNTM4LTQuOTQyLDEuNDk0LTcuMTI0aC02Ljk1N0gwdjQ5LjQ5M2wxLjYxOCwxLjYxOEwwLDUzLjY5NmMwLDYuMjU2LDUuMDY4LDExLjMyNiwxMS4zMjQsMTEuMzI4djBoMTkuMDg3aDMwLjQxMlYyNy42MTENCgljLTIuMTkxLDAuOTY0LTQuNjA5LDEuNTA5LTcuMTU3LDEuNTA5QzUxLjE0MiwyOS4xMiw0OC43NDYsMjguNTg4LDQ2LjU3MSwyNy42NDF6Ii8+DQo8Y2lyY2xlIHN0eWxlPSJmaWxsOiM5ODk4OTg7IiBjeD0iNTMuNjY2IiBjeT0iMTEuMzI4IiByPSIxMS4zMjgiLz4NCjwvc3ZnPg0K';
	}

	/**
	 * Fires when a post is transitioned from one status to another.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function transition_ad_status( $new_status, $old_status, $post ) {
		if ( ! isset( $post->post_type ) || Advanced_Ads::POST_TYPE_SLUG !== $post->post_type || ! isset( $post->ID ) ) {
			return;
		}

		$ad = \Advanced_Ads\Ad_Repository::get( $post->ID );

		if ( $old_status !== $new_status ) {
			/**
			 * Fires when an ad has transitioned from one status to another.
			 *
			 * @param Advanced_Ads_Ad $ad Ad object.
			 */
			do_action( "advanced-ads-ad-status-{$old_status}-to-{$new_status}", $ad );
		}

		if ( 'publish' === $new_status && 'publish' !== $old_status ) {
			/**
			 * Fires when an ad has transitioned from any other status to `publish`.
			 *
			 * @param Advanced_Ads_Ad $ad Ad object.
			 */
			do_action( 'advanced-ads-ad-status-published', $ad );
		}

		if ( 'publish' === $old_status && 'publish' !== $new_status ) {
			/**
			 * Fires when an ad has transitioned from `publish` to any other status.
			 *
			 * @param Advanced_Ads_Ad $ad Ad object.
			 */
			do_action( 'advanced-ads-ad-status-unpublished', $ad );
		}

		if ( $old_status === 'publish' && $new_status === Advanced_Ads_Ad_Expiration::POST_STATUS ) {
			/**
			 * Fires when an ad is expired.
			 *
			 * @param int             $id
			 * @param Advanced_Ads_Ad $ad
			 */
			do_action( 'advanced-ads-ad-expired', $ad->id, $ad );
		}
	}

}
