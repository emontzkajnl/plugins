<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Generate_Rest_Api_Url
 * @subpackage Generate_Rest_Api_Url/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Generate_Rest_Api_Url
 * @subpackage Generate_Rest_Api_Url/includes
 * @author     kinjal dalwadi <kinjal.dalwadi@yahoo.com>
 */
class Generate_Rest_Api_Url_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'generate-rest-api-url',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
