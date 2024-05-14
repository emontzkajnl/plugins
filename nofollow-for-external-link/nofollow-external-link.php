<?php
/*
Plugin Name: Nofollow for external link
Plugin URI: http://www.cybernetikz.com
Description: Just simple, if you activate this plugins, <code>rel=&quot;nofollow&quot;</code> and <code>target=&quot;_blank&quot;</code> will be added automatically, for all the external links of your website <strong>posts</strong> or <strong>pages</strong>. Also you can <strong>exclude domains</strong>, not to add <code>rel=&quot;nofollow&quot;</code> for the selected external links.
Version: 1.2.4
Author: CyberNetikz
Author URI: http://www.cybernetikz.com
License: GPL2
*/

if( !defined('ABSPATH') ) die('-1');

function cn_nf_install() {
	add_option( 'cn_nf_exclude_domains', '');
	add_option( 'cn_nf_apply_to_menu', '1');
}
register_activation_hook(__FILE__,'cn_nf_install');

function cn_nf_uninstall() {
	delete_option( 'cn_nf_apply_to_menu' );
}
register_deactivation_hook(__FILE__,'cn_nf_uninstall');

function cn_nf_admin_sidebar() {

	$banners = array(
		array(
			'url' => 'http://www.cybernetikz.com/wordpress-magento-plugins/wordpress-plugins/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-1.jpg',
			'alt' => 'Banner 1',
		),
		array(
			'url' => 'http://www.cybernetikz.com/portfolio/web-development/wordpress-website/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-2.jpg',
			'alt' => 'Banner 2',
		),
		array(
			'url' => 'http://www.cybernetikz.com/seo-consultancy/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-3.jpg',
			'alt' => 'Banner 3',
		),
	);
	//shuffle( $banners );

	$i = 0;
	echo '<div class="cn_admin_banner">';
	foreach ( $banners as $banner ) {
		echo '<a target="_blank" href="' . esc_url( $banner['url'] ) . '"><img width="261" height="190" src="' . plugins_url( 'images/' . $banner['img'], __FILE__ ) . '" alt="' . esc_attr( $banner['alt'] ) . '"/></a><br/><br/>';
		$i ++;
	}
	echo '</div>';

}

function cn_nf_admin_style() {
	global $pluginsURI;
	wp_register_style( 'cn_nf_admin_css', plugins_url( 'nofollow-for-external-link/css/admin-style.css' ) , false, '1.0' );
	wp_enqueue_style( 'cn_nf_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'cn_nf_admin_style' );

function register_cn_nf_settings() {
	register_setting( 'cn-nf-settings-group', 'cn_nf_exclude_domains' );
	register_setting( 'cn-nf-settings-group', 'cn_nf_apply_to_menu' );
}
add_action( 'admin_init', 'register_cn_nf_settings' );

function cn_nf_plugin_menu() {
	add_options_page('Nofollow for external link', 'NoFollow ExtLink', 'manage_options', 'cn_nf_option_page', 'cn_nf_option_page_fn');
}
add_action( 'admin_menu', 'cn_nf_plugin_menu');

function cn_nf_option_page_fn() {
	$cn_nf_exclude_domains = get_option('cn_nf_exclude_domains');
	$cn_nf_apply_to_menu = get_option('cn_nf_apply_to_menu');
	include_once ('nofollow-option-page.php');
}

function cn_nf_is_internal_link($url)
{
	// bypass #more type internal link
	$result = preg_match('/href(\s)*=(\s)*"[#|\/]*[a-zA-Z0-9-_\/]+"/', $url);

	if ($result) {
		return true;
	}

	$pos = strpos($url, cn_nf_get_domain());
	if ($pos !== false) {
		return true;
	}

	return false;
}

function cn_nf_is_link_available($content='')
{
	if ($content=='') {
		return null;
	}

	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";

	if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
		return $matches;
	}

	return null;
}

function cn_nf_get_domain()
{
	// return get_option('home');
	return $_SERVER['HTTP_HOST'];
}

function cn_nf_get_exclude_domains_list()
{
	$exclude_domains_list = array();

	if(get_option('cn_nf_exclude_domains')!='') {
		$exclude_domains_list = explode(",", get_option('cn_nf_exclude_domains'));
	}

	return $exclude_domains_list;
}

function cn_nf_is_domain_not_excluded($url)
{
	$domain_check_flag = true;

	$exclude_domains_list = cn_nf_get_exclude_domains_list();

	if(!count($exclude_domains_list)) {
		return $domain_check_flag;
	}

	$exclude_domains_list = array_filter($exclude_domains_list);

	foreach($exclude_domains_list as $domain) {

		$domain = trim($domain);

		if($domain=='') {
			continue;
		}

		$pos = strpos($url, $domain);

		if($pos === false) {
			continue;
		} else {
			$domain_check_flag = false;
			break;
		}
	}

	return $domain_check_flag;
}

function cn_nf_add_target_blank($url, $tag)
{
	$no_follow = '';
	$pattern = '/target\s*=\s*"\s*_(blank|parent|self|top)\s*"/';

	if (preg_match($pattern, $url) === 0) {
	 	$no_follow .= ' target="_blank"';
	}

	if ($no_follow) {
		$tag = cn_nf_update_close_tag($tag, $no_follow);
	}

	return $tag;
}

function cn_nf_add_rel_nofollow($url, $tag)
{
	$no_follow = '';
	// $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
	// $pattern = '/rel\s*=\s*\"[a-zA-Z0-9_\s]*[n|d]ofollow[a-zA-Z0-9_\s]*\"/';
	$pattern = '/rel\s*=\s*\"[a-zA-Z0-9_\s]*\"/';

	$result = preg_match($pattern, $url, $match);

	if ($result === 0) {
		$no_follow .= ' rel="nofollow"';
	} else {
		if (strpos($match[0], 'nofollow') === false &&
			strpos($match[0], 'dofollow') === false) {
			$temp = $match[0];
			$temp = substr_replace($temp, ' nofollow"', -1);
			$tag = str_replace($match[0], $temp, $tag);
		}
	}

	if ($no_follow) {
		$tag = cn_nf_update_close_tag($tag, $no_follow);
	}

	return $tag;
}

function cn_nf_update_close_tag($tag, $no_follow)
{
	return substr_replace($tag, $no_follow.'>', -1);
}

function cn_nf_url_parse( $content )
{

	$matches = cn_nf_is_link_available($content);

	if ($matches === null) {
		return $content;
	}

	// loop through each links
	for ($i=0; $i < count($matches); $i++)
	{
		$tag  = $matches[$i][0];
		$url  = $matches[$i][0];

		if(cn_nf_is_internal_link($url)) {
			continue;
		}

		$tag = cn_nf_add_target_blank($url, $tag);

		//exclude domain or add nofollow
		if(cn_nf_is_domain_not_excluded($url)) {
			$tag = cn_nf_add_rel_nofollow($url, $tag);
		}

		$content = str_replace($url, $tag, $content);

	} // end for loop

	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

add_filter( 'the_content', 'cn_nf_url_parse');

if( get_option('cn_nf_apply_to_menu') ) {
	add_filter( 'wp_nav_menu_items', 'cn_nf_url_parse' );
}
