<?php

use AdvancedAds\Utilities\WordPress;

class Advanced_Ads_Responsive_Admin {

	/**
	 * stores the settings page hook
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	protected $settings_page_hook = '';

	/**
	 * link to plugin page
	 *
	 * @since	1.3
	 * @const
	 */
	const PLUGIN_LINK = 'https://wpadvancedads.com/add-ons/responsive-ads/';

	/**
	 * holds base class
	 *
	 * @var Advanced_Ads_Responsive_Plugin
	 * @since 1.2.0
	 */
	protected $plugin;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->plugin = Advanced_Ads_Responsive_Plugin::get_instance();

		add_action( 'plugins_loaded', array( $this, 'wp_admin_plugins_loaded' ) );
		add_filter( 'advanced-ads-notices', [ $this, 'add_notices' ] );
	}

	/**
	 * load actions and filters
	 */
	public function wp_admin_plugins_loaded(){

		if( ! class_exists( 'Advanced_Ads_Admin', false ) ) {
			// show admin notice
			add_action( 'admin_notices', array( $this, 'missing_plugin_notice' ) );

			return;
		}

		if ( ! defined( 'AAP_VERSION' ) || 1 !== version_compare( AAP_VERSION, '2.24.2' ) ) {
			$notices = get_option('advanced-ads-notices');
			if ( ! array_key_exists( 'pro_responsive_migration', $notices['closed'] ?? [] ) ) {
				Advanced_Ads_Admin_Notices::get_instance()->add_to_queue( 'pro_responsive_migration' );
			}
		}



		add_action('advanced-ads-settings-init', array($this, 'settings_init'), 10, 1);
	}

	/**
	 * show warning if Advanced Ads js is not activated
	 */
	public function missing_plugin_notice(){
		$plugins = get_plugins();
		if( isset( $plugins['advanced-ads/advanced-ads.php'] ) ){ // is installed, but not active
			$link = '<a class="button button-primary" href="' . wp_nonce_url( 'plugins.php?action=activate&amp;plugin=advanced-ads/advanced-ads.php&amp', 'activate-plugin_advanced-ads/advanced-ads.php' ) . '">'. __('Activate Now', 'advanced-ads-responsive') .'</a>';
		} else {
			$link = '<a class="button button-primary" href="' . wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . 'advanced-ads'), 'install-plugin_' . 'advanced-ads') . '">'. __('Install Now', 'advanced-ads-responsive') .'</a>';
		}
		echo '
		<div class="error">
		  <p>'.sprintf(__('<strong>%s</strong> requires the <strong><a href="https://wpadvancedads.com" target="_blank">Advanced Ads</a></strong> plugin to be installed and activated on your site.', 'advanced-ads-responsive'), 'Advanced Ads Responsive') .
			 '&nbsp;' . $link . '</p></div>';
	}

	/**
	 * Add potential warning to global array of notices.
	 *
	 * @param array $notices existing notices.
	 *
	 * @return mixed
	 */
	public function add_notices( $notices ) {
		$message                             = wp_kses(
			sprintf(
			/* translators: 1 is the opening link to the Advanced Ads pge, 2 the closing link */
				__(
					'We have renamed the Responsive Ads add-on to ‘Advanced Ads AMP Ads’. With this change, the Browser Width visitor condition moved from that add-on into Advanced Ads Pro. You can deactivate ‘Advanced Ads AMP Ads’ if you don’t utilize AMP ads or the custom sizes feature for responsive AdSense ad units. %1$sRead more%2$s.',
					'advanced-ads-pro'
				),
				'<a href="https://wpadvancedads.com/responsive-ads-add-on-becomes-amp-ads" target="_blank" class="advads-manual-link">',
				'</a>'
			),
			[
				'a' => [
					'href'   => true,
					'target' => true,
					'class'  => true,
				],
			]
		);
		$notices['pro_responsive_migration'] = [
			'type'   => 'info',
			'text'   => $message,
			'global' => true,
		];

		return $notices;
	}

	/**
	 * render license key section
	 *
	 * @since 1.2.0
	 */
	public function render_settings_license_callback(){
		$licenses = get_option(ADVADS_SLUG . '-licenses', array());
		$license_key = isset($licenses['responsive']) ? $licenses['responsive'] : '';
		$license_status = get_option($this->plugin->options_slug . '-license-status', false);
		$index = 'responsive';
		$plugin_name = AAR_PLUGIN_NAME;
		$options_slug = $this->plugin->options_slug;
		$plugin_url = self::PLUGIN_LINK;

		// template in main plugin
		include ADVADS_BASE_PATH . 'admin/views/setting-license.php';
	}

	/**
	 * add settings to settings page
	 *
	 * @param string $hook settings page hook
	 */
	public function settings_init( $hook ) {
		// don’t initiate if main plugin not loaded
		if ( ! class_exists( 'Advanced_Ads_Admin' ) ) return;

		// add license key field to license section
		add_settings_field(
			'responsive-license',
			__( 'AMP Ads', 'advanced-ads-responsive' ),
			[ $this, 'render_settings_license_callback' ],
			'advanced-ads-settings-license-page',
			'advanced_ads_settings_license_section'
		);
	}
}
