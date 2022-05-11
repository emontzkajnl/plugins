<?php
/*
Plugin Name: MegaMenu
Plugin URI: http://mm.stylemixthemes.com
Description: MegaMenu
Author: StylemixThemes
Author URI: http://stylemixthemes.com
Text Domain: stm-megamenu
Version: 1.1.1
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('STM_MM_VER', '1.0');
define('STM_MM_DIR', plugin_dir_path(__FILE__));
define('STM_MM_URL', plugins_url('/', __FILE__));
define('STM_MM_PATH', plugin_basename(__FILE__));

if (!is_textdomain_loaded('mega_menu')) {
    load_plugin_textdomain('mega_menu', false, 'megamenu/languages');
}

if(get_theme_mod('mega_menu', true)){
    require_once(STM_MM_DIR . '/includes/helpers.php');
    require_once(STM_MM_DIR . '/includes/stm-mm-ajax.php');

    if(is_admin()) {
        require_once(STM_MM_DIR . '/admin/includes/helpers.php');
        require_once(STM_MM_DIR . '/admin/includes/xteam/xteam.php');
        require_once(STM_MM_DIR . '/admin/includes/config.php');
        require_once(STM_MM_DIR . '/admin/includes/enqueue.php');
        require_once(STM_MM_DIR . '/admin/includes/fontawesome.php');
    } else {
        require_once(STM_MM_DIR . '/includes/walker.php');
        require_once(STM_MM_DIR . '/includes/enqueue.php');
    }
}