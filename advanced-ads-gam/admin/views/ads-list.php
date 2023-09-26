<?php
/**
 * Ad unit list in the ad parameters meta box
 */

global $post;
$network      = Advanced_Ads_Network_Gam::get_instance();
$ads_list     = $network->get_external_ad_units();
$gam_option   = Advanced_Ads_Network_Gam::get_option();
$ad_unit_data = [];

if ( $post && $post->post_content ) {
	$ad_unit_data = $network->post_content_to_adunit( $post->post_content );
}

$disabled = Advanced_Ads_Gam_Admin::has_valid_license() ? '' : 'disabled';

?>

<?php if ( ! $network->is_account_connected() ) : ?>
	<?php if ( ! empty( $ad_unit_data ) ) : ?>
		<table class="widefat" id="advads-gam-notconnected-table">
			<thead>
				<th><?php esc_html_e( 'Name', 'advanced-ads-gam' ); ?></th>
				<th><?php esc_html_e( 'Description', 'advanced-ads-gam' ); ?></th>
				<th><?php esc_html_e( 'Ad Unit Code', 'advanced-ads-gam' ); ?></th>
			</thead>
			<tbody>
				<tr>
					<td><?php echo esc_html( $ad_unit_data['name'] ); ?></td>
					<td><p class="description"><?php echo esc_html( $ad_unit_data['description'] ); ?></p></td>
					<td><p class="description"><?php echo esc_html( $ad_unit_data['adUnitCode'] ); ?></p></td>
				</tr>
			</tbody>
		</table>
		<div class="card advads-notice-block advads-error">
			<p>
				<?php esc_html_e( 'You need to connect to a Google Ad Manager account', 'advanced-ads-gam' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=advanced-ads-settings#top#gam' ) ); ?>" class="button-primary"><?php esc_html_e( 'Connect', 'advamced-ads-gam' ); ?></a>
			</p>
		</div>
	<?php else : ?>
		<div class="card advads-notice-block advads-error">
			<p>
				<?php esc_html_e( 'You need to connect to a Google Ad Manager account', 'advanced-ads-gam' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=advanced-ads-settings#top#gam' ) ); ?>" class="button-primary"><?php esc_html_e( 'Connect', 'advamced-ads-gam' ); ?></a>
			</p>
		</div>
	<?php endif; ?>
<?php else : ?>
	<div id="advads-gam-table-wrap">
		<table id="advads-gam-table" class="widefat striped" data-nonce="<?php echo esc_attr( wp_create_nonce( 'gam-selector' ) ); ?>" data-adcount="<?php echo count( $ads_list ); ?>">
			<thead>
				<th><?php esc_html_e( 'Name', 'advanced-ads-gam' ); ?></th>
				<th><?php esc_html_e( 'Description', 'advanced-ads-gam' ); ?></th>
				<th><?php esc_html_e( 'Ad Unit Code', 'advanced-ads-gam' ); ?></th>
				<th>
					<a href="#modal-gam-ad-search" class="search-unit button alignright advads-gam-secondary-search-button <?php echo esc_attr( $disabled ); ?>">
						<i class="dashicons dashicons-search"></i><span><?php esc_html_e( 'Search ad units', 'advanced-ads-gam' ); ?></span>
					</a>
				</th>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<div id="advads-gam-current-unit-updated" class="card advads-notice-block advads-card-full-width advads-error hidden">
		<p><?php esc_html_e( 'The selected ad unit has changed in your GAM account. Please re-save this ad to apply the new changes.', 'advanced-ads-gam' ); ?></p>
		<p>
			<button class="button-primary"><?php esc_html_e( 'Update ad', 'advanced-ads-gam' ); ?></button>
		</p>
	</div>
	<?php if ( isset( $ad_unit_data['networkCode'] ) && $ad_unit_data['networkCode'] != $gam_option['account']['networkCode'] ) : ?>
		<div class="card advads-notice-block advads-idea" id="advads-gam-netcode-mismatch">
			<h3><?php esc_html_e( 'The selected ad is not from the currently connected account. You can still use it though.', 'advanced-ads-gam' ); ?></h3>
			<p>
				<strong><?php esc_html_e( 'Network code', 'advanced-ads-gam' ); ?>:</strong>&nbsp;<code><?php echo esc_html( $ad_unit_data['networkCode'] ); ?></code>
				<strong><?php esc_html_e( 'Ad unit name', 'advanced-ads-gam' ); ?>:</strong>&nbsp;<code><?php echo esc_html( $ad_unit_data['name'] ); ?></code>
			</p>
		</div>
	<?php endif; ?>
	<div id="advads-gam-ads-list-overlay"><div><div><div><img alt="loading" src="<?php echo esc_url( AAGAM_BASE_URL . 'admin/img/loader.gif' ); ?>" /></div></div></div></div>
<?php endif; ?>
