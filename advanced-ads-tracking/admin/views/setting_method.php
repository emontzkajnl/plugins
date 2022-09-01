<?php
/**
 * Tracking method settings.
 *
 * @var string $method                  The currently selected method.
 * @var bool   $show_tcf_warning        Whether there is a conflict between TCF and Tracking method settings.
 * @var string $tcf_warning             The error/warning message.
 * @var bool   $missing_scripts_warning Show warning if tracking scripts are disabled by filter.
 * @var bool   $is_amp_nossl            True when amp plugin is installed and missing ssl encryption.
 */
?>
	<label>
		<input name="<?php echo esc_attr( $this->plugin->options_slug ); ?>[method]" type="radio" value="frontend" <?php checked( 'frontend', $method ); ?>/>
		<?php esc_attr_e( 'Frontend', 'advanced-ads-tracking' ); ?>
	</label>
	<p class="description">
		<?php esc_html_e( 'Track impressions after the ad was loaded in the frontend.', 'advanced-ads-tracking' ); ?>
	</p>

	<label><input name="<?php echo esc_attr( $this->plugin->options_slug ); ?>[method]" type="radio" value="ga" <?php checked( 'ga', $method ); ?>/><?php esc_html_e( 'Google Analytics', 'advanced-ads-tracking' ); ?></label>
	<p class="description"><?php esc_html_e( 'Track impressions and clicks in Google Analytics.', 'advanced-ads-tracking' ); ?>
		<a href="<?php echo esc_url( ADVADS_URL ); ?>manual/ad-tracking-with-google-analytics/" target="_blank"><?php esc_html_e( 'Manual', 'advanced-ads-tracking' ); ?></a></p>
<?php if ( $method !== 'ga' && isset( $options['ga-UID'] ) && ! $this->plugin->is_forced_analytics() ) : ?>
	<input type="hidden" name="<?php echo esc_attr( $this->plugin->options_slug ); ?>[ga-UID]" value="<?php echo esc_attr( $options['ga-UID'] ); ?>"/>
	<?php
endif;
?>
	<label>
		<input name="<?php echo esc_attr( $this->plugin->options_slug ); ?>[method]" type="radio" value="onrequest" <?php checked( 'onrequest', $method ); ?>/>
		<?php esc_attr_e( 'Database', 'advanced-ads-tracking' ); ?> (<?php esc_attr_e( 'experienced users', 'advanced-ads-tracking' ); ?>)
	</label>
	<p class="description">
		<?php esc_html_e( 'Track impressions when the ad is requested from the database.', 'advanced-ads-tracking' ); ?>
		<?php
		printf(
			wp_kses(
				// translators: %1$s is a starting a tag, %2$s the closing one.
				__( 'Should only be used by experienced users after reading the %1$smanual%2$s.', 'advanced-ads-tracking' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			'<a href="' . esc_url( ADVADS_URL ) . 'manual/tracking-documentation#Tracking_Methods" target="_blank">',
			'</a>'
		);
		?>
	</p>

<?php if ( $is_amp_nossl ) : ?>
	<p class="description advads-amp-warning advads-error-message <?php echo $is_amp_nossl ? 'advads-amp-nossl' : ''; ?>" data-method="<?php echo esc_attr( $method ); ?>">
		<?php esc_html_e( 'Impression tracking is not working on AMP sites without ssl encryption.', 'advanced-ads-tracking' ); ?>
	</p>
<?php endif; ?>

<?php

if ( $show_tcf_warning ) :
	?>
	<p class="description advads-error-message">
		<?php echo esc_html( $tcf_warning ); ?>
		<?php esc_html_e( 'Please choose either the Frontend or Google Analytics method.', 'advanced-ads-tracking' ); ?>
	</p>
	<?php
endif;

if ( $missing_scripts_warning ) :
	?>
	<p class="description advads-error-message">
		<?php esc_html_e( 'The advanced-ads-tracking-load-header-scripts filter is set to false which removes any tracking scripts in the frontend. Only the Database tracking method is working now.', 'advanced-ads-tracking' ); ?>
	</p>
	<?php
endif;
