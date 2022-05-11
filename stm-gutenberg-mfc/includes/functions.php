<?php
function stm_gutenberg_get_assets_path()
{
    $assets = array();
    $url = STM_GUTENBERG_URL;
    $assets['css'] = $url . 'assets/css/';
    $assets['js'] = $url . 'assets/js/';
    $assets['js_gb'] = $url . 'gutenberg/js/';
    $assets['v'] = WP_DEBUG ? time() : STM_GUTENBERG_VER;

    return apply_filters('stm_gutenberg_get_assets_path', $assets);
}

function stmt_gutenmag_single_post_counter()
{
    if (is_singular('post')) {
        //Views

        $cookies = '';

        if (empty($_COOKIE['stm_post_watched'])) {
            $cookies = get_the_ID();
            setcookie('stm_post_watched', $cookies, time() + (86400 * 30), '/');
            stmt_gutenmag_increase_views(get_the_ID());
        }

        if (!empty($_COOKIE['stm_post_watched'])) {
            $cookies = $_COOKIE['stm_post_watched'];
            $cookies = explode(',', $cookies);

            if (!in_array(get_the_ID(), $cookies)) {
                $cookies[] = get_the_ID();

                $cookies = implode(',', $cookies);

                stmt_gutenmag_increase_views(get_the_ID());
                setcookie('stm_post_watched', $cookies, time() + (86400 * 30), '/');
            }
        }

        if (!empty($_COOKIE['stm_post_watched'])) {
            $watched = explode(',', $_COOKIE['stm_post_watched']);
        }
    }
}

function stmt_gutenmag_increase_views($post_id)
{

    $keys = array(
        'stm_post_views',
        'stm_day_' . date('j'),
        'stm_month_' . date('m')
    );

    foreach ($keys as $key) {

        $current_views = intval(get_post_meta($post_id, $key, true));

        $new_views = (!empty($current_views)) ? $current_views + 1 : 1;

        update_post_meta($post_id, $key, $new_views);
    }

}

add_action('wp', 'stmt_gutenmag_single_post_counter', 100, 1);

if(!function_exists('stmt_gutenmag_get_post_view_count')) {
    function stmt_gutenmag_get_post_view_count($postId) {

        $postViews = get_post_meta($postId, 'stm_post_views', true);

        return ($postViews) ? $postViews: 0;
    }
}

if( !function_exists('stmt_gutenmag_generateWrapStyle') ) {
    function stmt_gutenmag_generateWrapStyle ($attributes) {
        $cssBox = array();
        if(isset($attributes['margin_top']) && !empty($attributes['margin_top'])) $cssBox['margin-top'] = $attributes['margin_top'] . 'px !important; ';
        if(isset($attributes['margin_left']) && !empty($attributes['margin_left'])) $cssBox['margin-left'] = $attributes['margin_left'] . 'px !important; ';
        if(isset($attributes['margin_right']) && !empty($attributes['margin_right'])) $cssBox['margin-right'] = $attributes['margin_right'] . 'px !important; ';
        if(isset($attributes['margin_bottom']) && !empty($attributes['margin_bottom'])) $cssBox['margin-bottom'] = $attributes['margin_bottom'] . 'px !important; ';

        if(isset($attributes['padding_top']) && !empty($attributes['padding_top'])) $cssBox['padding-top'] = $attributes['padding_top'] . 'px !important; ';
        if(isset($attributes['padding_left']) && !empty($attributes['padding_left'])) $cssBox['padding-left'] = $attributes['padding_left'] . 'px !important; ';
        if(isset($attributes['padding_right']) && !empty($attributes['padding_right'])) $cssBox['padding-right'] = $attributes['padding_right'] . 'px !important; ';
        if(isset($attributes['padding_bottom']) && !empty($attributes['padding_bottom'])) $cssBox['padding-bottom'] = $attributes['padding_bottom'] . 'px !important; ';

        if(!stripos($_SERVER['REQUEST_URI'], 'wp-json/wp/v2')) {
            return (count($cssBox) > 0) ? str_replace(array('"', ',', '{', '}'), '', json_encode($cssBox)) : '';
        }

        return '';
    }
}

if( !function_exists('stmt_gutenmag_get_socials' ) ) {
    function stmt_gutenmag_get_socials ($class = '') {
        $to = get_option('stmt_to_settings', array());
        if(isset($to['socials']) && !empty($to['socials'])) {
            $soc = json_decode($to['socials']);
            $output = '<ul class="widget_socials list-unstyled clearfix ' . $class . ' ">';

            foreach ( $soc as $key => $val ):

                $icoKey = $key;
                if($key == 'facebook') $icoKey = $key . "-f";

                $output .= '<li class="' . $key . '">';
                $output .= '<a href="' . esc_url( $val ) . '" target="_blank">';
                $output .= '<i class="fab fa-' . esc_attr( $icoKey ) . '"></i>';
                $output .= '</a>';
                $output .= '</li>';
            endforeach;

            $output .= '</ul>';
            return $output;
        }
        return '';
    }
}

function stmt_gutenmag_v() {
	return (WP_DEBUG) ? time() : '1.0';
}