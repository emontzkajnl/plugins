<?php

/**
 * Class Advanced_Ads_Tracking_Util
 */
final class Advanced_Ads_Tracking_Util {

	// Mod filter for db format timestamps.
	const MOD_HOUR  = 100;
	const MOD_DAY   = 10000;
	const MOD_WEEK  = 1000000;
	const MOD_MONTH = 100000000;

	const DB_VERSION            = '1.5';
	const TABLE_BASENAME        = 'advads_impressions';
	const TABLE_CLICKS_BASENAME = 'advads_clicks';

	const FIXED_HOUR = '06';

	/**
	 * Name of the impressions table
	 *
	 * @var string
	 */
	private $impressions_table;

	/**
	 * Name of the clicks table
	 *
	 * @var string
	 */
	private $clicks_table;

	/**
	 * Tracking Plugin class.
	 *
	 * @var Advanced_Ads_Tracking_Plugin
	 */
	protected $plugin;

	/**
	 *  Tracking related data for each blog where ads come from
	 *
	 * @var array
	 */
	protected $blog_data = array(
		'ajaxurls'         => array(),
		'gaUIDs'           => array(),
		'gaAnonymIP'       => array(),
		'methods'          => array(),
		'linkbases'        => array(),
		'allads'           => array(),
		'parallelTracking' => array(),
	);

	/**
	 * Array of bots that should get ads displayed but not trigger ad impressions/clicks.
	 * Gets added to bots list from main plugin.
	 *
	 * @var array
	 */
	protected $bots = array( 'AspiegelBot', 'BingPreview', 'bingbot', 'datanyze', 'ecosia', 'Googlebot', 'Google-AMPHTML', 'GoogleAdSenseInfeed', 'Hexometer', 'mediapartners', '^Mozilla\/5\.0$', 'Barkrowler', 'Seekport Crawler', 'Sogou web spider', 'WP Rocket', 'FlyingPress' );

	/**
	 * Singleton instance.
	 *
	 * @var Advanced_Ads_Tracking_Util
	 */
	private static $instance;

	/**
	 * Global wpdb instance.
	 *
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * Advanced_Ads_Tracking_Util constructor.
	 */
	private function __construct() {
		$this->wpdb              = $GLOBALS['wpdb'];
		$this->impressions_table = $this->get_impression_table();
		$this->clicks_table      = $this->get_click_table();
		$this->plugin            = Advanced_Ads_Tracking_Plugin::get_instance();
	}

	/**
	 * Return the expired post status if it exists or an empty string otherwise.
	 *
	 * @return string
	 */
	public static function get_expired_post_status() {
		static $post_status;
		if ( $post_status === null && class_exists( 'Advanced_Ads_Ad_Expiration' ) ) {
			$post_status = Advanced_Ads_Ad_Expiration::POST_STATUS;
		} else {
			$post_status = '';
		}

		return $post_status;
	}

	/**
	 * Get timezone from WordPress settings.
	 *
	 * @return DateTimeZone
	 */
	public static function get_wp_timezone() {
		if ( ! method_exists( 'Advanced_Ads_Utils', 'get_timezone' ) ) {
			return Advanced_Ads_Admin::get_wp_timezone();
		}

		return Advanced_Ads_Utils::get_wp_timezone();
	}

	/**
	 * Get the name of the current WordPress timezone.
	 *
	 * @return string
	 */
	public static function get_timezone_name() {
		if ( ! method_exists( 'Advanced_Ads_Utils', 'get_timezone_name' ) ) {
			return Advanced_Ads_Admin::timezone_get_name( Advanced_Ads_Admin::get_wp_timezone() );
		}

		return Advanced_Ads_Utils::get_timezone_name();
	}

	/**
	 *  Collect data on blog from which ads have been picked
	 */
	public function collect_blog_data() {
		$bid = get_current_blog_id();
		$ajax_handler = new Advanced_Ads_Tracking_Installer();
		if ( ! isset( $this->blog_data['ajaxurls'][ $bid ] ) ) {
			$handler = $ajax_handler->get_handler_url();
			if ( $this->plugin->is_legacy_ajax() || ! $ajax_handler->handler_exists() ) {
				$handler = admin_url( 'admin-ajax.php' );
			}
			if ( ! Advanced_Ads_Tracking_Plugin::get_instance()->ignore_logged_in_user() ) {
				$this->blog_data['ajaxurls'][ $bid ] = $handler;
			}
		}
		$options = get_option( $this->plugin->options_slug, array() );
		if ( ! isset( $this->blog_data['gaUIDs'][ $bid ] ) ) {
			$this->blog_data['gaUIDs'][ $bid ] = isset( $options['ga-UID'] ) ? $options['ga-UID'] : '';
		}

		if ( ! isset( $this->blog_data['gaAnonymIP'][ $bid ] ) ) {
			$this->blog_data['gaAnonymIP'][ $bid ] = isset( $options['ga-anonym-IP'] ) && $options['ga-anonym-IP'] === 'on';
		}

		if ( ! isset( $this->blog_data['methods'][ $bid ] ) ) {
			$this->blog_data['methods'][ $bid ] = $this->plugin->get_tracking_method( $options['method'] );
		}

		$this->blog_data['parallelTracking'][ $bid ] = $this->plugin->is_forced_analytics();

		if ( ! isset( $this->blog_data['linkbases'][ $bid ] ) ) {
			$permalink = get_option( 'permalink_structure' );
			$linkbase  = isset( $options['linkbase'] ) ? $options['linkbase'] : Advanced_Ads_Tracking::CLICKLINKBASE;
			$base      = apply_filters( 'advanced-ads-tracking-click-url-base', $linkbase, false );

			if ( empty( $permalink ) ) {
				$linkbase = $base;
			} else {
				$linkbase = home_url( '/' . $base . '/' );
			}

			$this->blog_data['linkbases'][ $bid ] = $linkbase;
		}
		if ( ! isset( $this->blog_data['allads'][ $bid ] ) ) {
			$ads = Advanced_Ads::get_instance()->get_model()->get_ads( array(
				'post_status' => array( 'publish', 'future', 'draft', 'pending' ),
			) );
			foreach ( $ads as $ad ) {
				$ad_object                                             = new Advanced_Ads_Ad( $ad->ID );
				$tracking_plugin                                       = Advanced_Ads_Tracking_Plugin::get_instance();
				$this->blog_data['allads'][ $bid ][ (string) $ad->ID ] = array(
					'title'      => $ad->post_title,
					'target'     => $this->get_target_url( $ad_object ),
					'impression' => $tracking_plugin->check_ad_tracking_enabled( $ad_object ),
					'click'      => $tracking_plugin->check_ad_tracking_enabled( $ad_object, 'click' ),
				);
			}
		}
	}

	/**
	 * Get the link target URL for an ad.
	 *
	 * @param Advanced_Ads_Ad $ad The Advanced_Ads_Ad object.
	 *
	 * @return string The link target URL.
	 */
	private function get_target_url( Advanced_Ads_Ad $ad ) {
		$ad_options = $ad->options();
		if ( isset( $ad_options['tracking']['link'] ) && ! empty( $ad_options['tracking']['link'] ) ) {
			return $ad_options['tracking']['link'];
		}

		if ( isset( $ad_options['url'] ) && ! empty( $ad_options['url'] ) ) {
			return $ad_options['url'];
		}

		return '';
	}

	/**
	 *  Return blog data
	 */
	public function get_blog_data() {
		return $this->blog_data;
	}

	/**
	 * Singleton class instance.
	 *
	 * @return Advanced_Ads_Tracking_Util
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Adds test data for given ad ids
	 *
	 * @param array $ids      Array of ad ids.
	 * @param int   $max_days number of days to create test data beginning from today.
	 * @param int   $i        base number for test data.
	 * @param int   $runs     run multiple times if you want more test data.
	 *
	 * @noinspection PhpUnused
	 */
	public function create_test_data( $ids, $max_days = 600, $i = 1000, $runs = 1 ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$max_hours = $max_days * 24 - 1;
		$variance  = 4;
		$num_ids   = (int) max( 2, count( $ids ) / $variance );
		$base_time = time();
		$base_time -= $base_time % 3600;

		// Run $runs times.
		for ( $y = 0; $y < $runs; $y ++ ) {
			// Define arrays to save data.
			$values        = array();
			$values_clicks = array();
			$place_holders = array();
			for ( $n = $i; $n > 0; $n -- ) {

				// Create random date in given period.
				$ts = $base_time - 3600 * wp_rand( 0, $max_hours / $variance / 10 ) * wp_rand( 1, $variance * 10 );
				$ts = $this->get_timestamp( $ts, true );

				// Get random ad ids but minimum 2.
				$sub_ids = array_rand( $ids, wp_rand( 2, $num_ids ) );
				foreach ( $sub_ids as $sub_id ) {
					$sub_id = $ids[ $sub_id ];

					// Create random data.
					array_push( $values, $ts, $sub_id, ( ( wp_rand( 0, 10 ) + wp_rand( 0, 10 ) ) * wp_rand( 1, 5 ) ) );
					array_push( $values_clicks, $ts, $sub_id, ( ( wp_rand( 0, 2 ) + wp_rand( 0, 3 ) ) * wp_rand( 1, 3 ) ) );
					$place_holders[] = "('%d', '%d', '%d')";
				}
			}

			// Fire both queries.
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- we can't add table names as placeholders
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared -- place_holders has placeholders.
			// phpcs:disable WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- see comment above.
			$this->wpdb->query( $this->wpdb->prepare( 'INSERT INTO ' . $this->impressions_table . ' (timestamp, ad_id, count) VALUES ' . implode( ', ', $place_holders ) . ' ON DUPLICATE KEY UPDATE count=(count + VALUES(count)) / 2', $values ) );
			$this->wpdb->query( $this->wpdb->prepare( 'INSERT INTO ' . $this->clicks_table . ' (timestamp, ad_id, count) VALUES ' . implode( ', ', $place_holders ) . ' ON DUPLICATE KEY UPDATE count=(count + VALUES(count)) / 2', $values_clicks ) );
			// phpcs:enable
		}
	}

	/**
	 * Get the impressions table name of the current blog on normal site or a multi site
	 * When loading ads from another blog of a multi site, we get the updated value.
	 *
	 * @return string
	 */
	public function get_impression_table() {
		if ( ! empty( $this->impressions_table ) ) {
			return $this->impressions_table;
		}

		return $this->wpdb->get_blog_prefix() . self::TABLE_BASENAME;
	}

	/**
	 * Get the clicks table name of the current blog on normal site or a multi site
	 * When loading ads from another blog of a multi site, we get the updated value.
	 *
	 * @return string
	 */
	public function get_click_table() {
		if ( ! empty( $this->clicks_table ) ) {
			return $this->clicks_table;
		}

		return $this->wpdb->get_blog_prefix() . self::TABLE_CLICKS_BASENAME;
	}

	/**
	 * Get the timestamp in format YmWdH (H defaults to 06 to only have one timestamp per day).
	 *
	 * @param int|null $timestamp reference time (default: now; server time).
	 * @param bool     $fixed     whether to return a fixed hour (on stat per day per ad).
	 *
	 * @return int  db formatted timestamp in WordPress local time
	 * @since 1.0.0
	 */
	public function get_timestamp( $timestamp = null, $fixed = false ) {
		$time = gmdate( 'c', (int) $timestamp < 1 ? time() : $timestamp );

		// Default timestamp.
		$ts = get_date_from_gmt( $time, 'ymWdH' );

		// Check for week/month inconsistencies.
		$week  = (int) get_date_from_gmt( $time, 'W' );
		$month = (int) get_date_from_gmt( $time, 'm' );

		if ( 52 <= $week && 1 === $month ) {
			// Still week 52 but already in January.
			$ts = get_date_from_gmt( $time, 'ym01dH' );
		} elseif ( 12 === $month && $week > 52 ) {
			// Still in December but week 53.
			$ts = get_date_from_gmt( $time, 'ym52dH' );
		}

		if ( $fixed ) {
			$ts = substr( $ts, 0, - 2 );
			$ts .= self::FIXED_HOUR;
		}

		return $ts;
	}

	/**
	 * Format original date as stored in db for display.
	 *
	 * @param int    $db_time db time.
	 * @param string $format  date format.
	 *
	 * @return string
	 */
	public function get_date_from_db( $db_time, $format ) {
		$date = array_combine( array( 'year', 'month', 'week', 'day', 'hour' ), str_split( $db_time, 2 ) );
		// -TODO since month and day have special meaning when `0` this was hot-fixed
		$time = mktime( (int) $date['hour'], 0, 0, max( $date['month'], 1 ), max( $date['day'], 1 ), (int) $date['year'] );

		return date( $format, $time );
	}

	/**
	 * Add impression to database.
	 *
	 * @param int      $ad_id      Arguments for tracking call.
	 * @param int|null $start_time Start time of tracking request, used for metrics.
	 *
	 * @since 1.0.0
	 */
	public function track_impression( $ad_id, $start_time = null ) {
		// check for old format where single ad_id got passed as array.
		if ( is_array( $ad_id ) && array_key_exists( 'ad_id', $ad_id ) ) {
			$ad_id = $ad_id['ad_id'];
		}

		$this->track_impressions( array( $ad_id ), $start_time );
	}

	/**
	 * Track multiple ad impressions to database.
	 *
	 * @param int[] $ad_ids     Array with ad ids as values.
	 * @param int   $start_time Timestamp when tracking started, gets passed to log.
	 */
	public function track_impressions( array $ad_ids, $start_time ) {
		$ad_ids = array_filter( array_map( 'intval', $ad_ids ), function( $ad_id ) {
			return $this->is_tracking_allowed( $ad_id, $this->impressions_table );
		} );
		if ( empty( $ad_ids ) ) {
			return;
		}

		foreach ( array_count_values( $ad_ids ) as $ad_id => $count ) {
			$this->persist( $ad_id, $count, $this->impressions_table, $start_time );
		}
	}

	/**
	 * Add click to database.
	 *
	 * @param int      $ad_id      The ad id to track.
	 * @param int|null $start_time Start time of tracking request, used for metrics.
	 *
	 * @since 1.1.0
	 */
	public function track_click( $ad_id, $start_time = null ) {
		// check for old format where single ad_id got passed as array.
		if ( is_array( $ad_id ) && array_key_exists( 'ad_id', $ad_id ) ) {
			$ad_id = $ad_id['ad_id'];
		}
		$ad_id = (int) $ad_id;
		if ( ! $this->is_tracking_allowed( $ad_id, $this->clicks_table ) ) {
			return;
		}
		$this->persist( $ad_id, 1, $this->clicks_table, $start_time );
	}

	/**
	 * Determine whether this impression/click should be tracked.
	 *
	 * @param int    $ad_id    The ad id to track.
	 * @param string $db_table The database table to track into.
	 *
	 * @return bool
	 */
	private function is_tracking_allowed( $ad_id, $db_table ) {
		// Do not track impressions on 404 pages generated by missing css.map or js.map files.
		if ( isset( $_SERVER['REQUEST_URI'] ) && strlen( $_SERVER['REQUEST_URI'] ) - 4 === strpos( $_SERVER['REQUEST_URI'], '.map' ) ) {
			return false;
		}

		$the_ad = new Advanced_Ads_Ad( $ad_id );
		if ( $the_ad->is_ad ) {
			$ad_options = $the_ad->options();
			if ( $ad_options['expiry_date'] && time() > $ad_options['expiry_date'] ) {
				// Do not track expired ads click.
				return false;
			}
		}

		/**
		 * Do not track click for bots if the options is not active.
		 * never track cache bots though
		 *
		 * @todo remove optional bot tracking unless we find a good reason that activity by some bots should be tracked
		 */
		$main_plugin = Advanced_Ads::get_instance();
		// Add bots that should see ads but not track them to the bots list.
		add_filter( 'advanced-ads-bots', array( $this, 'add_bots_triggering_ajax' ) );
		$is_bot = $main_plugin->is_bot();
		// Remove the filter to have a clean bots list again.
		remove_filter( 'advanced-ads-bots', array( $this, 'add_bots_triggering_ajax' ) );

		if (
			$main_plugin->is_cache_bot()
			|| $this->plugin->ignore_logged_in_user()
			|| ( $is_bot && ! isset( $this->plugin->options()['track-bots'] ) )
		) {
			return false;
		}

		/**
		 * Allow to disable tracking something into the database.
		 *
		 * @param int    $ad_id    The ad id to track.
		 * @param string $db_table The database table to track into.
		 */
		return (bool) apply_filters( 'advanced-ads-tracking-do-tracking', true, $ad_id, $db_table );
	}

	/**
	 * Temporarily add bots that trigger AJAX calls to the bots list to filter them.
	 *
	 * @param array $bots Array of bots.
	 *
	 * @return array
	 */
	public function add_bots_triggering_ajax( array $bots ) {
		return array_merge( $bots, $this->bots );
	}

	/**
	 * Write impression/click track into db.
	 *
	 * @param int    $id         The ad id.
	 * @param int    $count      Number of impressions (always 1 for clicks).
	 * @param string $table      The table name to track into (including wpdb_prefix).
	 * @param null   $start_time The starting time, used in debug log.
	 */
	protected function persist( $id, $count, $table, $start_time = null ) {
		$timestamp = $this->get_timestamp( null, true );

		// phpcs:ignore WordPress.DB.PreparedSQL -- we can't add table names as placeholders.
		$success = $this->wpdb->query( $this->wpdb->prepare( "INSERT INTO {$table} (`ad_id`, `timestamp`, `count`) VALUES (%d, %d, %d) ON DUPLICATE KEY UPDATE `count` = `count` + %d", $id, $timestamp, $count, $count ) );

		/**
		 * Add custom logging if ADVANCED_ADS_TRACKING_DEBUG is enabled
		 * writes events into wp-content/advanced-ads-tracking.csv
		 */
		if ( Advanced_Ads_Tracking_Debugger::debugging_enabled( $id ) ) {
			Advanced_Ads_Tracking_Debugger::log( $id, $table, ! is_null( $start_time ) ? round( ( microtime( true ) - $start_time ) * 1000 ) : - 1 );
		}

		/**
		 * Allow to perform your own action when tracking was performed locally
		 *
		 * @param int    $id        The ad id tracked.
		 * @param string $table     name of the table, normally {prefix_}advads_impressions or {prefix_}advads_clicks.
		 * @param int    $timestamp The timestamp of the save.
		 * @param bool   $success   If written into db.
		 */
		do_action( 'advanced-ads-tracking-after-writing-into-db', $id, $table, $timestamp, $success );
	}

	/**
	 * Load sums of impressions and clicks.
	 *
	 * @return array with impressions and clicks by ad id.
	 * @since 1.2.6
	 */
	public function get_sums() {
		static $sums = array(
			'impressions' => array(),
			'clicks'      => array(),
		);

		// we've already queried the database, short-circuit return sums.
		if ( ! empty( array_filter( $sums ) ) ) {
			return $sums;
		}

		foreach ( array( 'clicks', 'impressions' ) as $metric ) {
			$table = $this->{"${metric}_table"};
			// check for the presence of table.
			if ( ! $this->wpdb->query( "SHOW TABLES LIKE '${table}'" ) ) {
				continue;
			}
			if ( $this->wpdb->query( "SELECT SQL_NO_CACHE `ad_id`, SUM(`count`) as `count` FROM  ${table} GROUP BY `ad_id`" ) ) {
				foreach ( $this->wpdb->last_result as $row ) {
					$sums[ $metric ][ $row->ad_id ] = $row->count;
				}
			}
		}

		return $sums;
	}

	/**
	 * Get the sums for an ad from the db, not the cached value.
	 *
	 * @param int  $ad_id      The ad id.
	 * @param bool $use_clicks Whether to get stats for clicks.
	 *
	 * @return array
	 */
	public function get_sums_for_ad( $ad_id, $use_clicks = false ) {
		$sums = array(
			'impressions' => 0,
			'clicks'      => 0,
		);

		// phpcs:disable WordPress.DB.PreparedSQL -- we can't prepare the table names.
		$sums['impressions'] = (int) $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT SQL_NO_CACHE SUM(`count`) FROM {$this->get_impression_table()} WHERE ad_id = %d;",
				$ad_id
			)
		);

		if ( $use_clicks ) {
			$sums['clicks'] = (int) $this->wpdb->get_var(
				$this->wpdb->prepare(
					"SELECT SQL_NO_CACHE SUM(`count`) FROM {$this->get_click_table()} WHERE ad_id = %d;",
					$ad_id
				)
			);
		}

		// phpcs:enable

		return $sums;
	}

	/**
	 * Create the email content of ads reports.
	 *
	 * @param string[] $report_args array with possible values for period and ad_id.
	 *
	 * @return string
	 */
	private function get_email_report_content( $report_args = array() ) {
		$period = isset( $report_args['period'] ) ? $report_args['period'] : '';
		$ad_id  = isset( $report_args['ads'] ) ? $report_args['ads'] : 'all';

		if ( 'all' !== $ad_id ) {
			$ad_id = absint( $ad_id );
		}

		$valid_period = array( 'last30days', 'last12months', 'lastmonth' );
		if ( ! in_array( $period, $valid_period, true ) ) {
			$period = 'last30days';
		}

		$textual_period = array(
			'last30days'   => __( ' the last 30 days', 'advanced-ads-tracking' ),
			'lastmonth'    => __( ' the last month', 'advanced-ads-tracking' ),
			'last12months' => __( ' the last 12 months', 'advanced-ads-tracking' ),
		);

		$admin_class = new Advanced_Ads_Tracking_Admin();

		$today = date_create( 'now', self::get_wp_timezone() );
		$args  = array(
			'ad_id'       => array(),
			'period'      => 'lastmonth',
			'groupby'     => 'day',
			'groupFormat' => 'Y-m-d',
			'from'        => null,
			'to'          => null,
		);

		if ( $period === 'last30days' ) {
			$start_ts = (int) $today->format( 'U' );
			$start_ts -= ( 30 * 24 * 60 * 60 );

			$start = date_create( '@' . $start_ts, new DateTimeZone( 'UTC' ) );

			$args['period'] = 'custom';
			$args['from']   = $start->format( 'm/d/Y' );

			$end_ts = (int) $today->format( 'U' );
			$end    = date_create( '@' . $end_ts, new DateTimeZone( 'UTC' ) );

			$args['to'] = $end->format( 'm/d/Y' );
		}

		if ( $period === 'last12months' ) {
			$start_ts = (int) $today->format( 'U' );
			$start_ts -= ( 365 * 24 * 60 * 60 );

			$start = date_create( '@' . $start_ts, new DateTimeZone( 'UTC' ) );

			$args['period']  = 'custom';
			$args['groupby'] = 'month';
			$args['from']    = $start->format( 'm/' ) . '1' . $start->format( '/Y' );

			// Fix potential time zone gap.
			$end_ts = (int) $today->format( 'U' );
			$end_ts += ( 24 * 60 * 60 );
			$end    = date_create( '@' . $end_ts, new DateTimeZone( 'UTC' ) );

			$args['to'] = $end->format( 'm/d/Y' );
		}

		$impr_stats   = $admin_class->load_stats( $args, $this->impressions_table );
		$clicks_stats = $admin_class->load_stats( $args, $this->clicks_table );

		$ad_name      = false;
		$public_stats = false;

		/**
		 *  Filter ad ids to allow correct display if no stats for the corresponding ad
		 */
		if ( $ad_id !== 'all' ) {
			$__imprs  = array();
			$__clicks = array();
			foreach ( $impr_stats as $date => $impression ) {
				$key = (string) $ad_id;
				if ( array_key_exists( $key, $impression ) ) {
					$__imprs[ $date ] = array( $key => $impression[ $key ] );
				} else {
					$__imprs[ $date ] = array( $key => 0 );
				}
				if ( isset( $clicks_stats[ $date ] ) ) {
					if ( array_key_exists( $key, $clicks_stats[ $date ] ) ) {
						$__clicks[ $date ] = array( $key => absint( $clicks_stats[ $date ][ $key ] ) );
					} else {
						$__clicks[ $date ] = array( $key => 0 );
					}
				} else {
					$__clicks[ $date ] = array( $key => 0 );
				}
			}
			$impr_stats   = $__imprs;
			$clicks_stats = $__clicks;
			$public       = new Advanced_Ads_Tracking_Public_Stats( $ad_id );
			$ad_name      = $public->get_name( true );
			$public_stats = $public->get_url();
		}

		$cell_style   = 'style="padding: 0.6em;text-align:right;border:1px solid;"';
		$header_style = 'style="padding: 0.8em;text-align:center;font-size:1.1em;font-weight:bold;"';

		$impr_sum  = 0;
		$click_sum = 0;
		ob_start();

		?>
		<div style="margin-top:0.4em;margin-bottom:0.4em;margin-right:auto;margin-left:auto;position:relative;width:420px;overflow:visible;">
			<h3 style="font-size:1.3em;"><?php echo bloginfo( 'name' ); ?></h3>
			<?php if ( $ad_name ) : ?>
				<?php // translators: %1$s is the ad name; %2$s a period string. ?>
				<h4 style="font-size:1.2em;"><?php printf( __( '%1$s statistics for %2$s', 'advanced-ads-tracking' ), '<strong><em>' . $ad_name . '</em></strong>', $textual_period[ $period ] ); ?></h4>
			<?php else : ?>
				<?php // translators: %s is a period string. ?>
				<h4 style="font-size:1.2em;"><?php printf( __( 'Ads statistics for %s', 'advanced-ads-tracking' ), $textual_period[ $period ] ); ?></h4>
			<?php endif; ?>
			<?php do_action( 'advanced-ads-tracking-email-report-below-headline' ); ?>
			<?php
			if ( ! $impr_stats ) : // No impression stats found.
				?>
				<p style="font-size:1.1em;"><em><?php _e( 'There is no data for the given period, yet.', 'advanced-ads-tracking' ); ?></em></p>
			<?php
			else : // There are some stats.
				?>
				<table style="border:1px solid;border-collapse:collapse;">
					<thead>
					<th <?php echo $header_style; ?>><?php _e( 'date', 'advanced-ads-tracking' ); ?></th>
					<th <?php echo $header_style; ?>><?php _e( 'impressions', 'advanced-ads-tracking' ); ?></th>
					<th <?php echo $header_style; ?>><?php _e( 'clicks', 'advanced-ads-tracking' ); ?></th>
					<th <?php echo $header_style; ?>>
						<span title="<?php echo esc_attr( __( 'click through rate', 'advanced-ads-tracking' ) ); ?>" style="cursor:help;"><?php _e( 'CTR', 'advanced-ads-tracking' ); ?></span>
					</th>
					</thead>
					<tbody>
					<?php $impr_stats = array_reverse( $impr_stats ); ?>
					<?php foreach ( $impr_stats as $date => $impr ) : ?>
						<?php
						$total_impr   = ( is_array( $impr ) ) ? array_sum( $impr ) : 0;
						$total_clicks = ( isset( $clicks_stats[ $date ] ) && is_array( $clicks_stats[ $date ] ) ) ? array_sum( $clicks_stats[ $date ] ) : 0;
						$ctr          = ( $total_impr !== 0 ) ? number_format( 100 * $total_clicks / $total_impr, 2 ) . '%' : '0.00%';
						/**
						 *  Avoid sending the partial stats (if any at the moment the email is sent) for the current day for the "last 30 days".
						 */
						if ( $period === 'last30days' && $date === $today->format( 'Y-m-d' ) ) {
							continue;
						}
						/**
						 *  Avoid printing the 13th month (the current month) for last 12 months
						 */
						if ( $period === 'last12months' && $date === $today->format( 'Y-m-01' ) ) {
							continue;
						}
						$impr_sum  += $total_impr;
						$click_sum += $total_clicks
						?>
						<tr>
							<td <?php echo $cell_style; ?>>
								<?php
								if ( $period === 'last12months' ) {
									echo date_i18n( 'F Y', strtotime( $date ) );
								} else {
									echo date_i18n( get_option( 'date_format' ), strtotime( $date ) );
								}
								?>
							</td>
							<td <?php echo $cell_style; ?>><?php echo $total_impr; ?></td>
							<td <?php echo $cell_style; ?>><?php echo $total_clicks; ?></td>
							<td <?php echo $cell_style; ?>><?php echo $ctr; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr style="font-weight:600;">
						<td <?php echo $cell_style; ?>><?php _e( 'Total', 'advanced-ads-tracking' ); ?></td>
						<td <?php echo $cell_style; ?>><?php echo $impr_sum; ?></td>
						<td <?php echo $cell_style; ?>><?php echo $click_sum; ?></td>
						<td <?php echo $cell_style; ?>><?php echo ( $click_sum === 0 ) ? '0.00 %' : number_format( 100 * $click_sum / $impr_sum, 2 ) . ' %'; ?></td>
					</tr>
					</tbody>
				</table>
				<?php if ( $ad_name ) : ?>
				<p><a href="<?php echo esc_url( $public_stats ); ?>" target="_blank" style="font-size:1.1em;color:#1fa1d0;text-decoration:none;font-weight:bold;"><?php _e( 'View the live statistics', 'advanced-ads-tracking' ); ?></a></p>
			<?php endif; ?>
			<?php
			endif;
			do_action( 'advanced-ads-tracking-email-report-below-content' );
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Send individual ad report
	 *
	 * @param array $params Array with email parameters, Keys subject, to, id, and period must exist.
	 *
	 * @return array array with email success and error if any.
	 */
	public function send_individual_ad_report( array $params ) {
		if ( ! isset( $params['subject'], $params['to'], $params['id'], $params['period'] ) ) {
			return array(
				'status' => false,
				'error'  => '',
			);
		}

		$bcc = explode( ',', $params['to'] );
		$to  = array_shift( $bcc );

		$options = $this->plugin->options();
		$sender  = isset( $options['email-sender-name'] ) ? $options['email-sender-name'] : 'Advanced Ads';
		$from    = isset( $options['email-sender-address'] ) ? $options['email-sender-address'] : 'noreply@' . wp_parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $sender . ' <' . $from . '>',
		);
		if ( ! empty( $bcc ) ) {
			$headers[] = 'Bcc: ' . implode( ',', $bcc );
		}

		ob_start();

		$content = $this->get_email_report_content( array(
			'period' => $params['period'],
			'ads'    => $params['id'],
		) );

		$result = wp_mail( $to, $params['subject'], $content, $headers );
		$error  = ob_get_clean();

		return array(
			'status' => $result,
			'error'  => $error,
		);
	}

	/**
	 * Send ads reports to admin email
	 *
	 * @return array
	 */
	public function send_email_report() {
		$options = $this->plugin->options();
		if ( empty( $options['email-addresses'] ) ) {
			return array(
				'status' => false,
				'error'  => '',
			);
		}
		$period  = $options['email-stats-period'];
		$content = $this->get_email_report_content( array( 'period' => $period ) );
		if ( ! $content ) {
			return array(
				'status' => false,
				'error'  => '',
			);
		}

		$bcc     = explode( ',', $options['email-addresses'] );
		$to      = array_shift( $bcc );
		$subject = $options['email-subject'];
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $options['email-sender-name'] . ' <' . $options['email-sender-address'] . '>',
		);

		if ( ! empty( $bcc ) ) {
			$headers[] = 'Bcc: ' . implode( ',', $bcc );
		}

		ob_start();
		$result = wp_mail( $to, $subject, $content, $headers );
		$error  = ob_get_clean();

		return array(
			'status' => $result,
			'error'  => $error,
		);
	}

	/**
	 * Get the target link
	 *
	 * @param Advanced_Ads_Ad|int $ad ID of the ad or the ad object.
	 *
	 * @return string link if given or empty string
	 */
	public static function get_link( $ad ) {
		if ( ! $ad instanceof Advanced_Ads_Ad ) {
			$ad = new Advanced_Ads_Ad( $ad );
		}

		$options    = $ad->options();
		$ad_options = isset( $options['tracking'] ) ? $options['tracking'] : array();

		// Get url.
		if ( ! empty( $ad_options['link'] ) ) {
			return $ad_options['link'];
		}

		if ( ! empty( $options['url'] ) ) {
			return $options['url'];
		}

		return '';
	}

	/**
	 * Get the target attribute for the link, e.g. ` target="_blank"`
	 *
	 * @param Advanced_Ads_Ad $ad         ID of the ad or the ad object.
	 * @param bool            $value_only If true only return value, if false return fall attribute as string.
	 *
	 * @return string whole target attribute with value
	 */
	public static function get_target( $ad, $value_only = false ) {
		$ad_options      = $ad->options();
		$options         = Advanced_Ads_Tracking_Plugin::get_instance()->options();
		$general_options = Advanced_Ads::get_instance()->options();

		/**
		 * Second line is needed for backward compatibility with Tracking 1.7.2 and below when the general target-blank option was still in this add-on and not in basic
		 * and below when the general target-blank option was still in this add-on and not in basic
		 */
		$general_target_blank = ( isset( $general_options['target-blank'] ) && $general_options['target-blank'] === '1' )
								|| ( isset( $options['target'] ) && $options['target'] === '1' );
		if (
			( $general_target_blank && ( ! isset( $ad_options['tracking']['target'] ) || $ad_options['tracking']['target'] !== 'same' ) )
			|| ( isset( $ad_options['tracking']['target'] ) && $ad_options['tracking']['target'] === 'new' )
		) {
			return $value_only ? '_blank' : ' target="_blank"';
		}

		return '';
	}
}
