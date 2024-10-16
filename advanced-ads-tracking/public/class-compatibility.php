<?php
/**
 * Handle compatibility with third parties' code
 *
 * @package AdvancedAds
 * @author  Advanced Ads <info@wpadvancedads.com>
 */

namespace AdvancedAdsTracking;

use AdvancedAds\Framework\Utilities\Params;
use AdvancedAds\Framework\Utilities\Str;

/**
 * Compatibility class
 */
class Compatibility {
	/**
	 * Front end tracking class instance
	 *
	 * @var \Advanced_Ads_Tracking
	 */
	private $front_tracking;

	/**
	 * Constructor
	 *
	 * @param \Advanced_Ads_Tracking $front_tracking front end tracking class instance.
	 */
	public function __construct( $front_tracking ) {
		$this->front_tracking = $front_tracking;

		// Adjust Peepso placement output.
		if ( Str::contains( 'peepsoajax', Params::server( 'REQUEST_URI' ) ) && strtolower( Params::server( 'REQUEST_METHOD' ) ) === 'post' ) {
			add_filter( 'advanced-ads-output-final', [ $this, 'peepso_output' ], 10, 2 );
			add_filter( 'advanced-ads-output-wrapper-options', [ $this->front_tracking, 'add_wrapper' ], 10, 2 );
		}
	}

	/**
	 * Place markers on the Peepso placement output
	 *
	 * @param string           $output the ad output.
	 * @param \Advanced_Ads_Ad $ad     the ad object.
	 *
	 * @return string
	 */
	public function peepso_output( $output, $ad ) {
		ob_start();
		?>
		<script>
			document.dispatchEvent(
				new CustomEvent(
					'advads_track_async',
					{
						detail: {
							ad: <?php echo esc_js( $ad->id ); ?>,
							bid: <?php echo esc_js( get_current_blog_id() ); ?>,
						}
					}
				)
			);
		</script>
		<?php
		$output .= ob_get_clean();

		return $output;
	}
}
