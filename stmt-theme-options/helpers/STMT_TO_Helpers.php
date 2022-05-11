<?php
class STMT_TO_Helpers {
    public function __construct()
    {
        add_action('stmt_set_custom_color', 'STMT_TO_Helpers::custom_color');
    }

    public static function custom_color () {

        $to = get_option('stmt_to_settings', array());

        if(isset($to['primary_color']) && !empty($to['primary_color'])) {
            $primaryColor = $to['primary_color'];
            $secondaryColor = '';

            global $wp_filesystem;

            if (empty($wp_filesystem)) {
                require_once ABSPATH . '/wp-admin/includes/file.php';
                WP_Filesystem();
            }

            $theme_path = get_template_directory_uri() . '/assets/';

            $custom_style_css = $wp_filesystem->get_contents(get_template_directory() . '/assets/css/app.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/blocks_color_scheme.css');

            $layout = get_option('current_layout', 'default');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/' . $layout . '.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/cryptoticker.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/cryptotable.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/cryptoconverter.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/mailchimp.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/scroll_top.css');
            $custom_style_css .= $wp_filesystem->get_contents(get_template_directory() . '/assets/css/responsive.css');

            $custom_style_css = str_replace(
                array(
                    '#4fbe6e',
                    '#ff1f59',
                    'rgba(255, 31, 89, 0.1)',
                    '../../',
            ),
                array(
                    $primaryColor,
                    $primaryColor,
                    str_replace(',1)', ', 0.1)', $primaryColor), //4
                    $theme_path,
                ),
                $custom_style_css
            );

            $upload_dir = wp_upload_dir();

            if (!$wp_filesystem->is_dir($upload_dir['basedir'] . '/stm_uploads')) {
                $wp_filesystem->mkdir($upload_dir['basedir'] . '/stm_uploads', FS_CHMOD_DIR);
            }

            if ($custom_style_css) {
                $css_to_filter = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $custom_style_css);
                $css_to_filter = str_replace(array(
                    "\r\n",
                    "\r",
                    "\n",
                    "\t",
                    '  ',
                    '    ',
                    '    '
                ), '', $css_to_filter);

                $custom_style_file = $upload_dir['basedir'] . '/stm_uploads/skin-custom.css';

                $wp_filesystem->put_contents($custom_style_file, $css_to_filter, FS_CHMOD_FILE);

                update_option('stm_custom_style', 'custom_style');
            }
        } else {
            update_option('stm_custom_style', '');
        }
    }

    public static function stm_to_hex2rgb($colour) {
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return $r . ',' . $g . ',' . $b;
    }
}

new STMT_TO_Helpers();