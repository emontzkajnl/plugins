<?php
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript

if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! isset( $ad_id ) ) {
	die;
}
$period     = ( isset( $_GET['period'] ) && ! empty( $_GET['period'] ) ) ? stripslashes( $_GET['period'] ) : 'last30days';
$ad         = new Advanced_Ads_Ad( $ad_id );
$ad_options = $ad->options();
$ad_name    = ( isset( $ad_options['tracking']['public-name'] ) && ! empty( $ad_options['tracking']['public-name'] ) ) ? $ad_options['tracking']['public-name'] : $ad->title;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php echo get_option( 'blog_charset' ); ?>">
	<title><?php echo esc_url( get_bloginfo( 'name' ) ); ?>|<?php esc_html_e( 'Ad Statistics', 'advanced-ads-tracking' ); ?></title>
	<meta name="robots" content="noindex, nofollow"/>
	<script type="text/javascript">
		var AAT_SPINNER_URL = '<?php echo esc_url( AAT_BASE_URL . 'public/assets/images/spinner-2x.gif' ); ?>';
	</script>
	<script type="text/javascript" src="<?php echo includes_url( '/js/jquery/jquery.js' ); ?>"></script>
	<script type="text/javascript" src="<?php echo includes_url( '/js/jquery/jquery-migrate.min.js' ); ?>"></script>
	<script type="text/javascript"
			src="<?php echo AAT_BASE_URL . 'admin/assets/jqplot/jquery.jqplot.min.js'; ?>"></script>
	<script type="text/javascript"
			src="<?php echo AAT_BASE_URL . 'admin/assets/jqplot/plugins/jqplot.dateAxisRenderer.min.js'; ?>"></script>
	<script type="text/javascript"
			src="<?php echo AAT_BASE_URL . 'admin/assets/jqplot/plugins/jqplot.highlighter.min.js'; ?>"></script>
	<script type="text/javascript"
			src="<?php echo AAT_BASE_URL . 'admin/assets/jqplot/plugins/jqplot.cursor.min.js'; ?>"></script>
	<?php
	printf(
		'<script src="%s"></script>',
		esc_url( AAT_BASE_URL . 'public/assets/js' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '/src/public-stats.js' : '/dist/public-stats.min.js' ) )
	);
	?>
	<link rel="stylesheet" href="<?php echo AAT_BASE_URL . 'admin/assets/jqplot/jquery.jqplot.min.css'; ?>"/>
	<link rel="stylesheet" href="<?php echo AAT_BASE_URL . 'public/assets/css/public-stats.css'; ?>"/>
	<?php do_action( 'advanced-ads-public-stats-head' ); ?>
</head>
<body>
<div id="stats-head">
	<h1 id="stats-title"><?php echo get_bloginfo( 'name' ); ?></h1>
	<div id="stats-period">
		<table style="width: 100%;">
			<tbody>
			<tr>
				<td style="width:50%;text-align:center;">
					<h3 id="ad-title"><?php printf( __( 'Statistics for %s', 'advanced-ads-tracking' ), $ad_name ); ?></h3>
				</td>
				<td style="width:50%;text-align:center;">
					<form method="get" id="period-form">
						<label><?php _e( 'Period', 'advanced-ads-tracking' ); ?>:&nbsp;</label>
						<select name="period">
							<option value="last30days" <?php selected( 'last30days', $period ); ?>><?php _e( 'last 30 days', 'advanced-ads-tracking' ); ?></option>
							<option value="lastmonth" <?php selected( 'lastmonth', $period ); ?>><?php _e( 'last month', 'advanced-ads-tracking' ); ?></option>
							<option value="last12months" <?php selected( 'last12months', $period ); ?>><?php _e( 'last 12 months', 'advanced-ads-tracking' ); ?></option>
						</select>
						<input type="submit" class="button button-primary"
							   value="<?php echo esc_attr( __( 'Load', 'advanced-ads-tracking' ) ); ?>"/>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<?php
$wptz  = Advanced_Ads_Tracking_Util::get_wp_timezone();
$today = date_create( 'now', $wptz );

$admin_class = new Advanced_Ads_Tracking_Admin();
$args        = array(
	'ad_id'       => array( $ad_id ), // actually no effect
	'period'      => 'lastmonth',
	'groupby'     => 'day',
	'groupFormat' => 'Y-m-d',
	'from'        => null,
	'to'          => null,
);

if ( $period === 'last30days' ) {
	$start_ts = (int) $today->format( 'U' );
	// unlike with emails, send the current day, then the last 30 days stops at ( today - 29 days )
	$start_ts = $start_ts - ( 29 * 24 * 60 * 60 );

	$start = date_create( '@' . $start_ts, $wptz );

	$args['period'] = 'custom';
	$args['from']   = $start->format( 'm/d/Y' );

	$end_ts = (int) $today->format( 'U' );
	$end    = date_create( '@' . $end_ts, $wptz );

	$args['to'] = $end->format( 'm/d/Y' );
}

if ( $period === 'last12months' ) {
	$current_year  = (int) $today->format( 'Y' );
	$current_month = (int) $today->format( 'm' );
	$past_year     = $current_year - 1;

	$args['period']  = 'custom';
	$args['groupby'] = 'month';

	$args['from'] = $today->format( 'm/01/' . $past_year );
	$args['to']   = $today->format( 'm/d/Y' );
}

$impr_stats   = $admin_class->load_stats( $args, $this->impressions_table );
$clicks_stats = $admin_class->load_stats( $args, $this->clicks_table );

// If clicks only are present, fill impressions with zero.
if ( ! $impr_stats && is_array( $clicks_stats ) ) {
	$impr_stats = $clicks_stats;
	foreach ( $impr_stats as $date => $_stats ) {
		foreach ( $_stats as $key => $value ) {
			$impr_stats[ $date ][ $key ] = 0;
		}
	}
}

$impr_series   = array();
$clicks_series = array();
$first_date    = false;
$max_clicks    = 0;
$max_impr      = 0;

if ( isset( $impr_stats ) && is_array( $impr_stats ) ) {
	foreach ( $impr_stats as $date => $impressions ) {
		if ( ! $first_date ) {
			$first_date = $date;
		}
		$impr = 0;
		if ( isset( $impressions[ $ad_id ] ) ) {
			$impr_series[] = array( $date, $impressions[ $ad_id ] );
			$impr          = $impressions[ $ad_id ];
		} else {
			$impr_series[] = array( $date, 0 );
		}
		$clicks = 0;
		if ( isset( $clicks_stats[ $date ] ) && isset( $clicks_stats[ $date ][ $ad_id ] ) ) {
			$clicks_series[] = array( $date, $clicks_stats[ $date ][ $ad_id ] );
			$clicks          = $clicks_stats[ $date ][ $ad_id ];
		} else {
			$clicks_series[] = array( $date, 0 );
		}
		if ( $impr > $max_impr ) {
			$max_impr = $impr;
		}
		if ( $clicks > $max_clicks ) {
			$max_clicks = $clicks;
		}
	}
}
$lines = array( $impr_series, $clicks_series );
?>
<div id="stats-content">
	<script type="text/javascript">
		var statsGraphOptions = {
			axes:        {
				xaxis:  {
					renderer:     null,
					<?php if ( $period === 'last12months' ) : ?>
					tickOptions:  {formatString: '%b %Y'},
					<?php else : ?>
					tickOptions:  {formatString: '%b%d'},
					<?php endif; ?>
					tickInterval: '1 <?php echo $args['groupby']; ?>',
					min:          '<?php echo $first_date; ?>'
				},
				yaxis:  {
					min:         0,
					max: <?php echo ( (int) ( $max_impr * 1.1 / 10 ) + 1 ) * 10; ?>,
					label:       '<?php _e( 'impressions', 'advanced-ads-tracking' ); ?>',
					tickOptions: {formatString: '%\'.0f'}
				},
				y2axis: {
					min:         0,
					max: <?php echo ( (int) ( $max_clicks * 1.3 / 10 ) + 1 ) * 10; ?>,
					label:       '<?php _e( 'clicks', 'advanced-ads-tracking' ); ?>',
					tickOptions: {formatString: '%\'.0f'}
				}
			},
			grid: {
				background: '#ffffff',
				borderWidth: 2.0,
				shadow: false,
				gridLineColor: '#e5e5e5',
				drawBorder: false
			},
			highlighter: {
				show: true
			},
			cursor:      {
				show: false
			},
			title:       {
				show: true
			},
			seriesDefaults: {
				rendererOptions: {
					smooth: true
				}
			},
			series:      [
				{
					highlighter:   {
						formatString: "%s: %'.0f <?php _e( 'impressions', 'advanced-ads-tracking' ); ?>"
					},
					lineWidth:     3,
					markerOptions: {style: 'circle', size: 5},
					color:         '#1B183A',
					label:         '<?php _e( 'impressions', 'advanced-ads-tracking' ); ?>'
				}, // impressions
				{
					yaxis:         'y2axis',
					highlighter:   {
						formatString: "%s: %'.0f <?php _e( 'clicks', 'advanced-ads-tracking' ); ?>"
					},
					lineWidth:     3,
					linePattern:   'dashed',
					markerOptions: {style: 'filledSquare', size: 5},
					color:         '#0474A2',
					label:         '<?php _e( 'clicks', 'advanced-ads-tracking' ); ?>'
				} // clicks
			]
		};
		var lines             = <?php echo json_encode( $lines ); ?>;
	</script>
	<div id="public-stat-graph"></div>
	<div id="graph-legend">
		<div class="legend-item">
			<div id="impr-legend"></div>
			<span class="legend-text"><?php _e( 'impressions', 'advanced-ads-tracking' ); ?></span>
		</div>
		<div class="legend-item">
			<div id="click-legend"></div>
			<span class="legend-text"><?php _e( 'clicks', 'advanced-ads-tracking' ); ?></span>
		</div>
	</div>
	<hr/>
	<table id="public-stat-table">
		<thead>
		<th><?php _e( 'date', 'advanced-ads-tracking' ); ?></th>
		<th><?php _e( 'impressions', 'advanced-ads-tracking' ); ?></th>
		<th><?php _e( 'clicks', 'advanced-ads-tracking' ); ?></th>
		<th><?php _e( 'ctr', 'advanced-ads-tracking' ); ?></th>
		</thead>
		<tbody>
		<?php
		if ( isset( $impr_stats ) && is_array( $impr_stats ) ) :
			$impr_stats = array_reverse( $impr_stats );
			$impr_sum   = 0;
			$click_sum  = 0;
			foreach ( $impr_stats as $date => $all ) :
				?>
				<tr>
					<td>
						<?php
						if ( $period === 'last12months' ) {
							echo date_i18n( 'M Y', strtotime( $date ) );
						} else {
							echo date_i18n( get_option( 'date_format' ), strtotime( $date ) );
						}
						?>
					</td>
					<td>
						<?php
						$impr      = ( isset( $all[ $ad_id ] ) ) ? $all[ $ad_id ] : 0;
						$impr_sum += $impr;
						echo number_format_i18n( $impr );
						?>
					</td>
					<td>
						<?php
						$click      = ( isset( $clicks_stats[ $date ] ) && isset( $clicks_stats[ $date ][ $ad_id ] ) ) ? $clicks_stats[ $date ][ $ad_id ] : 0;
						$click_sum += $click;
						echo number_format_i18n( $click );
						?>
					</td>
					<td>
						<?php
						$ctr = 0;
						if ( $impr !== 0 ) {
							$ctr = $click / $impr * 100;
						}
						echo number_format( $ctr, 2 ) . ' %';
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr style="background-color:#f0f0f0;color:#222;font-weight:bold;">
				<td><?php _e( 'Total', 'advanced-ads-tracking' ); ?></td>
				<td><?php echo number_format_i18n( $impr_sum ); ?></td>
				<td><?php echo number_format_i18n( $click_sum ); ?></td>
				<td><?php echo ( $click_sum === 0 ) ? number_format_i18n( 0, 2 ) . ' %' : number_format_i18n( 100 * $click_sum / $impr_sum, 2 ) . ' %'; ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<hr/>
</div>
</body>
</html>
