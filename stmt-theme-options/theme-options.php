<?php
/*
Plugin Name: STM Theme Options
Plugin URI: http://stylemixthemes.com
Description: Theme options.
Author: StylemixThemes
Author URI: http://stylemixthemes.com
Version: 1.1.1
 Text Domain: stmt_theme_options
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('STMT_TO_DIR', plugin_dir_path(__FILE__));
define('STMT_TO_URL', plugins_url('/', __FILE__));
define('STMT_TO_PATH', plugin_basename(__FILE__));

if (!is_textdomain_loaded('stmt_theme_options')) {
    load_plugin_textdomain('stmt_theme_options', false, 'stmt-theme-options/languages');
}

require_once STMT_TO_DIR . '/post_type/taxonomy_meta/metaboxes.php';
require_once STMT_TO_DIR . '/post_type/metaboxes/metabox.php';
require_once STMT_TO_DIR . '/settings/google-fonts.php';
require_once STMT_TO_DIR . '/settings/settings.php';
require_once STMT_TO_DIR . '/helpers/STMT_TO_Helpers.php';

function stmt_to_wp_head()
{
    ?>
    <script type="text/javascript">
        var stmt_to_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    </script>
    <?php
}

add_action('wp_head', 'stmt_to_wp_head');
add_action('admin_head', 'stmt_to_wp_head');