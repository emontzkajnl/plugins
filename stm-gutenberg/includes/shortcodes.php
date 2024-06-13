<?php
function stmt_gutenmag_cryptos( $atts ) {
	$atts = shortcode_atts( array(
		'coins' => '',
		'limit' => 5
	), $atts, 'stm_gutenmag_coins' );

	ob_start();
	require_once get_template_directory() . '/template-parts/crypto_table/simple_table.php';

	return ob_get_clean();
}

add_shortcode( 'stm_gutenmag_coins', 'stmt_gutenmag_cryptos' );

function stmt_gutenmag_crypto_converter( $atts ) {
	$atts = shortcode_atts( array(
		'from' => 'bitcoin',
		'to' => 'ethereum',
	), $atts, 'stmt_gutenmag_crypto_converter' );

	ob_start();
	require_once get_template_directory() . '/template-parts/crypto_converter/converter.php';

	return ob_get_clean();
}
add_shortcode( 'stmt_gutenmag_crypto_converter', 'stmt_gutenmag_crypto_converter' );

function stmt_gutenmag_socials( $atts ) {
	$atts = shortcode_atts( array(
		'socials' => '',
	), $atts, 'stm_socials' );

	ob_start();
	require_once get_template_directory() . '/template-parts/global/socials.php';

	return ob_get_clean();
}

add_shortcode( 'stm_socials', 'stmt_gutenmag_socials' );

function stmt_gutenmag_mailchimp() {
	ob_start();
	the_widget('Stm_Mailchimp_Widget');
	return ob_get_clean();
}

add_shortcode( 'stm_mailchimp', 'stmt_gutenmag_mailchimp' );