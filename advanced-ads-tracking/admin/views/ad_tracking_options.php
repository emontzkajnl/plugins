<?php
/**
 * Single ad tracking settings.
 *
 * @var bool $sponsored value for sponsored rel attribute.
 * @var Advanced_Ads_Ad $ad the current ad.
 * @var bool $cloaking_enabled Is link cloaking enabled for this ad.
 * @var bool $cloaking_cb_disabled Is link cloaking filtered and checkbox does not have effect.
 */

$options = $this->plugin->options();
?>
<div class="advads-option-list">
	<span class="label"><?php esc_html_e( 'tracking', 'advanced-ads-tracking' ); ?></span>
	<div>
		<select name="advanced_ad[tracking][enabled]">
			<?php foreach ( $tracking_choices as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $enabled, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description">
			<?php
			printf(
				wp_kses(
					// translators: %$s is the plugin url.
					__( 'Please visit the %1$smanual%2$s to learn more about click tracking.', 'advanced-ads-tracking' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				'<a href="' . esc_url( ADVADS_URL ) . 'manual/tracking-documentation" target="_blank">',
				'</a>'
			);
			?>
		</p>
	</div>
	<hr/>
	<?php
	if ( $ad->type === 'group' || $ad->type === 'adsense' ) {
		echo '</div>';

		return;
	}
	?>
	<label for="advads-url" class="label"><?php _e( 'url', 'advanced-ads-tracking' ); ?></label>
	<div>
		<?php if ( $link && strpos( $ad->content, '%link%' ) === false && ( strpos( $ad->content, 'href=' ) !== false || strpos( $ad->content, '<script' ) !== false || strpos( $ad->content, '<iframe' ) !== false ) ) : ?>
			<p class="description advads-metabox-notices ">
				<span class="error">
					<?php esc_html_e( 'Based on your configuration it seems that you do not need to specify an external link. You can leave the url field empty.', 'advanced-ads' ); ?>
				</span>
			</p>
		<?php endif; ?>


		<input type="url" name="advanced_ad[url]" id="advads-url" class="advads-ad-url" style="width:60%;"
			   value="<?php echo $link; ?>"
			   placeholder="<?php esc_html_e( 'https://www.example.com/', 'advanced-ads' ); ?>"/>
		<p class="description">
			<?php esc_html_e( 'Link to target site including http(s)', 'advanced-ads' ); ?>
		</p>
		<p class="description">
			<?php
			echo wp_kses(
				__( 'Don’t use this field on JavaScript ad tags (like from Google AdSense). If you are using your own <code>&lt;a&gt;</code> tag, use <code>href="%link%"</code> to insert the tracking link.', 'advanced-ads-tracking' ),
				array(
					'code' => array(),
				)
			);
			?>
		</p>
		<?php
		$supported_placeholder_array = array(
			'[POST_ID]'   => __( 'post ID', 'advanced-ads-tracking' ),
			'[POST_SLUG]' => __( 'post slug', 'advanced-ads-tracking' ),
			'[CAT_SLUG]'  => __( 'a comma-separated list of category slugs', 'advanced-ads-tracking' ),
			'[AD_ID]'     => __( 'ID of the ad', 'advanced-ads-tracking' ),
		);
		$supported_placeholders      = implode( '</code>, <code>', array_keys( $supported_placeholder_array ) );
		$supported_placeholder_texts = implode( ', ', $supported_placeholder_array );

		?>
		<p class="description">
			<?php
			printf(
				wp_kses(
						// translators: %1$s is a list of placeholder like [POST_ID] and %2$s the appropriate names like "post ID"
					__( 'You can use %1$s in the url to insert %2$s into the url.', 'advanced-ads-tracking' ),
					array(
						'code' => array(),
					)
				),
				wp_kses(
					'<code>' . $supported_placeholders . '</code>',
					array(
						'code' => array(),
					)
				),
				esc_html( $supported_placeholder_texts )
			);
			?>
		</p>
		<p>
			<label for="advads-cloaking">
				<input type="checkbox" name="advanced_ad[tracking][cloaking]" id="advads-cloaking" <?php checked( $cloaking_enabled ); ?> <?php disabled( $cloaking_cb_disabled ); ?>/>
				<?php
				printf(
				/* translators: %s is the URL displayed in the frontend, wrapped in <code> tags. */
					esc_html__( 'Cloak your link. The link will be displayed as %s.', 'advanced-ads-tracking' ),
					sprintf( '<code>%s</code>', esc_url( Advanced_Ads_Tracking::build_click_tracking_url( $ad ) ) )
				);
				?>
			</label>
			<?php
			// show a notice if link cloaking universally filtered.
			if ( $cloaking_cb_disabled ) :
				?>
				<br/>
				<span class="advads-message-warning">
					<?php
					/* Translators: %s is the filter name wrapped in <code> tags. */
					printf( esc_html__( 'The value for link cloaking is defined for all ads by the %s filter.', 'advanced-ads-tracking' ), '<code>advanced-ads-ad-option-tracking.cloaking</code>' );
					?>
				</span>
			<?php endif; ?>
		</p>
	</div>
	<hr/>
	<span class="label"><?php esc_html_e( 'target window', 'advanced-ads-tracking' ); ?></span>
	<div>
		<label><input name="advanced_ad[tracking][target]" type="radio"
					  value="default" <?php checked( $target, 'default' ); ?>/><?php esc_html_e( 'default', 'advanced-ads-tracking' ); ?>
		</label>
		<label><input name="advanced_ad[tracking][target]" type="radio"
					  value="same" <?php checked( $target, 'same' ); ?>/><?php esc_html_e( 'same window', 'advanced-ads-tracking' ); ?>
		</label>
		<label><input name="advanced_ad[tracking][target]" type="radio"
					  value="new" <?php checked( $target, 'new' ); ?>/><?php esc_html_e( 'new window', 'advanced-ads-tracking' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Where to open the link (if present).', 'advanced-ads-tracking' ); ?></p>
	</div>
	<hr/>
	<span class="label"><?php esc_html_e( 'Add “nofollow”', 'advanced-ads-tracking' ); ?></span>
	<div>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio"
					  value="default" <?php checked( $nofollow, 'default' ); ?>/><?php esc_html_e( 'default', 'advanced-ads-tracking' ); ?>
		</label>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio"
					  value="1" <?php checked( $nofollow, 1 ); ?>/><?php esc_html_e( 'yes', 'advanced-ads-tracking' ); ?>
		</label>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio"
					  value="0" <?php checked( $nofollow, 0 ); ?>/><?php esc_html_e( 'no', 'advanced-ads-tracking' ); ?>
		</label>
		<p class="description"><?php echo wp_kses( __( 'Add <code>rel="nofollow"</code> to tracking links.', 'advanced-ads-tracking' ), array( 'code' => array() ) ); ?></p>
	</div>
	<hr/>
	<span class="label"><?php esc_attr_e( 'Add “sponsored”', 'advanced-ads-tracking' ); ?></span>
	<div>
		<label>
			<input name="advanced_ad[tracking][sponsored]" type="radio" value="default" <?php checked( $sponsored, 'default' ); ?>/>
			<?php esc_attr_e( 'default', 'advanced-ads-tracking' ); ?>
		</label>
		<label>
			<input name="advanced_ad[tracking][sponsored]" type="radio" value="1" <?php checked( $sponsored, '1' ); ?>/>
			<?php esc_attr_e( 'yes', 'advanced-ads-tracking' ); ?>
		</label>
		<label>
			<input name="advanced_ad[tracking][sponsored]" type="radio" value="0" <?php checked( $sponsored, '0' ); ?>/>
			<?php esc_attr_e( 'no', 'advanced-ads-tracking' ); ?>
		</label>
		<p class="description">
			<?php
			/* Translators: %s <code>rel="sponsored"</code> */
			printf( esc_html__( 'Add %s to tracking links.', 'advanced-ads-tracking' ), '<code>rel="sponsored"</code>' );
			?>
		</p>
	</div>
	<hr/>
</div>
