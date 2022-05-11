<?php
/*
Plugin Name: STM Gutenberg MFC
Plugin URI: http://stylemixthemes.com/
Description: STM Gutenberg
Author: Stylemix Themes
Author URI: http://stylemixthemes.com/
Text Domain: stm-gutenberg
Version: 1.1.2
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('STM_GUTENBERG_VER', '1.0');
define('STM_GUTENBERG_DIR', plugin_dir_path(__FILE__));
define('STM_GUTENBERG_BASENAME', plugin_basename(__FILE__));
define('STM_GUTENBERG_URL', plugins_url('/', __FILE__));
define('STM_GUTENBERG_INC_PATH', STM_GUTENBERG_DIR . 'includes/');


require_once (STM_GUTENBERG_INC_PATH . 'functions.php');
require_once (STM_GUTENBERG_INC_PATH . 'shortcodes.php');
require_once (STM_GUTENBERG_INC_PATH . 'share/shares.php');
require_once (STM_GUTENBERG_INC_PATH . 'mailchimp/main.php');
require_once (STM_GUTENBERG_INC_PATH . 'widgets/widget_init.php');

/* Gutenberg */
require_once ( STM_GUTENBERG_INC_PATH . 'scripts_style.php' );
if(class_exists('WP_Block_Type')) require_once ( STM_GUTENBERG_DIR . 'stm_gutenmag.php' );