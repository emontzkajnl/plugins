<?php

/**
 * Helper class to achieve compatibility with third party plugins.
 */
class Advanced_Ads_Tracking_Compatibility {
	/**
	 * Advanced_Ads_Tracking_Compatibility constructor.
	 */
	public function __construct() {
		add_filter( 'advanced-ads-compatibility-critical-inline-js', array( self::class, 'critical_inline_js' ), 10, 2 );
	}

	/**
	 * Add advads-tracking to array not be optimized by WP Rocket, Complianz et al.
	 *
	 * @param array  $inline_js       Array with unique strings (IDs), identifying inline JavaScript.
	 * @param string $frontend_prefix The frontend_prefix option setting.
	 *
	 * @return array
	 */
	public static function critical_inline_js( $inline_js, $frontend_prefix ) {
		$inline_js[] = sprintf( 'id="%stracking"', $frontend_prefix );

		return $inline_js;
	}
}
