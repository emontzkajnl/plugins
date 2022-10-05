<?php
/**
 * Plugin Name: Poller Master
 * Plugin URI: http://codecanyon.net/user/DJMiMi
 * Description: Create poll to recive your visitors feedback. Create and manage multiple polls at once. Select where and which one will be shown.
 * Version: 1.0
 * Author: DJMiMi
 * Author URI: http://codecanyon.net/user/DJMiMi
 * License: GPL2
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;
 
if ( ! defined( 'POLL_PATH') ){
	define( 'POLL_PATH', str_replace( '\\', '/', dirname( __FILE__ ) ) );
}
if ( ! defined( 'POLL_URL' ) ){
	define( 'POLL_URL', str_replace( str_replace( '\\', '/', WP_CONTENT_DIR ), str_replace( '\\', '/', WP_CONTENT_URL ), POLL_PATH ) );
}

/* poller_master widte*/
require_once( POLL_PATH."/poller_master_widget.php" );

class Poll_It{
	public $default_template = array(
		"poll_bg_color" 				=> "",
		"poll_border_color"				=> "",
		"poll_border_radius"			=> "",
		"answers_color"					=> "#333333",
		"answers_font_size" 			=> "12",		
		"answers_line_height" 			=> "22",
		"answers_font_weight" 			=> "normal",
		"answer_vote_results"			=> "both",
		"in_easing"						=> "easeOutBounce",
		"in_effect"						=> "slide",
		"out_easing"					=> "easeOutBounce",
		"out_effect"					=> "slide",
		"check_radio_style"				=> "flat",
		"check_radio_scheme"			=> "blue",
		"loader"						=> "",
		"counter_poll_show_text"		=> "yes",
		"counter_poll_bg_color"			=> "#5bc0de",
		"counter_poll_border_color"		=> "#46b8da",
		"counter_poll_bg_hvr_color"		=> "#39b3d7",
		"counter_poll_border_hvr_color"	=> "#269abc",
		"counter_poll_font_color"		=> "#ffffff",
		"counter_poll_font"				=> "Fjalla+One",
		"counter_poll_font_size"		=> "22",
		"counter_poll_line_height"		=> "32",
		"counter_poll_border_radius"	=> "",
		"vote_btn_bg_color"				=> "#20a8d8",
		"vote_btn_border_color"			=> "#20a8d8",
		"vote_btn_bg_hvr_color"			=> "#1985ac",
		"vote_btn_hvr_border_color"		=> "#1985ac",
		"vote_btn_font_color"			=> "#ffffff",
		"vote_btn_font_weight"			=> "normal",
		"vote_btn_border_radius"		=> "",
		"vote_btn_width"				=> "",
		"result_btn_bg_color"			=> "#5cb85c",
		"result_btn_border_color"		=> "#4cae4c",
		"result_btn_bg_hvr_color"		=> "#47a447",
		"result_btn_hvr_border_color"	=> "#398439",
		"result_btn_font_color"			=> "#ffffff",
		"result_btn_font_weight"		=> "normal",
		"result_btn_border_radius"		=> "",
		"result_btn_width"				=> "",
		"counter_vote_show_text"		=> "yes",
		"counter_vote_bg_color"			=> "#5bc0de",
		"counter_vote_border_color"		=> "#46b8da",
		"counter_vote_bg_hvr_color"		=> "#39b3d7",
		"counter_vote_border_hvr_color"	=> "#269abc",
		"counter_vote_font_color"		=> "#ffffff",
		"counter_vote_font"				=> "Fjalla+One",
		"counter_vote_font_size"		=> "22",
		"counter_vote_line_height"		=> "32",
		"counter_vote_border_radius"	=> "",
		"results_box_bg_color"			=> "#ffffff",
		"results_box_font_size"			=> "13",
		"results_box_border_color"		=> "#e1e6ef",
		"results_box_font_color"		=> "#374767",
		"results_box_border_radius"		=> "",
		"results_box_opacity"			=> "0.9",
		"results_box_font_weight"		=> "normal",
		"progress_bar_style"			=> "progress-striped active",
		"progress_bar_colors"			=> "#39AFEA, #FF5454, #47A447, #20A8D8, #FABB3D, #E1E6EF, #1985AC, #61A434, #428BCA, #FF2121, #F9AA0B, #D58512, #C0CADD, #374767, #67C2EF, #5cb85c, #F0AD4E, #8A6D3B",
		"progress_bar_height"			=> "10",		
	);
	public $options = array(
		"vote_text" 			=> "Vote",
		"results_text"			=> "Results",
		"vote_success_text"		=> "Your vote has been written. Thank You.",
		"vote_error_text"		=> "There was an error writting your vote. Please try again.",
		"vote_already_text" 	=> "You already voted this poll.",
		"vote_again_text"		=> "You can vote again in:",
		"vote_results_text"		=> "Here are the results ( Total votes: {%TOTAL_VOTES%} ):",
		"poll_closed_text"		=> "This poll has been closed by the administration.",
		"poll_expired_text"		=> "This poll has ended.",
		"error_empty_text"		=> "Please select a vote or two",
		"registered_text"		=> "You need to be logged in to vote.",
		"guests_text"			=> "This poll in ony for the guests.",
		"results_denied_text"	=> "You do not have access to the results, until you vote.",
		"day_text"				=> "DAY",
		"days_text"				=> "DAYS",
		"hours_text"			=> "HOURS",
		"minutes_text"			=> "MINS",
		"seconds_text"			=> "SECS",
		"enqueue_bootstrap"		=> "yes",
		"load_logs"				=> "20",
		"chart_colors"			=> "",
		"templates"				=> array(
			"template_1"	=> array(
				"template_name"	=> "Template 1",
			),
			"template_2"	=> array(
				"template_name"	=> "Template 2",
			),
			"template_3"	=> array(
				"template_name"	=> "Template 3",
			),
			"template_4"	=> array(
				"template_name"	=> "Template 4",
			),
			"template_5"	=> array(
				"template_name"	=> "Template 5",
			),
			"template_6"	=> array(
				"template_name"	=> "Template 6",
			),
			"template_7"	=> array(
				"template_name"	=> "Template 7",
			),
			"template_8"	=> array(
				"template_name"	=> "Template 8",
			),
			"template_9"	=> array(
				"template_name"	=> "Template 9",
			),
			"template_10"	=> array(
				"template_name"	=> "Template 10",
			),
		)
	);
	public $version = "1.0";
	public $activated = "0";
	public $polls_table_name;
	public $logs_table_name;
	public $poller_master_options_page = "poller_master/poller_master_options.php";
	public $poller_master_reset_page = "poller_master/poller_master_reset.php";
	public $poller_master_expimp_page = "poller_master/poller_master_expimp.php";
	
	function __construct(){
		$this->populate_variables();
		$this->get_options();
		$this->check_activation();

		add_action('plugins_loaded', array( $this, 'load_text_domain' ));
		
		/* add small style which will add style for the VC element */
		add_action( 'admin_enqueue_scripts', array( $this, 'vc_icon' ) );
		
		add_action( 'init', array( $this, 'poller_master_tinymce_buttons' ) );
		add_action( 'wp_ajax_reset_poll', array( $this, 'reset_poll' ) );
		add_action( 'wp_ajax_get_stats', array( $this, 'get_stats' ) );
		add_action( 'wp_ajax_load_more_stats', array( $this, 'load_more_stats' ) );
		add_action( 'wp_ajax_update_poll', array( $this, 'update_poll' ) );
		add_action( 'wp_ajax_edit_poll', array( $this, 'get_edit_poll_data' ) );
		add_action( 'wp_ajax_delete_poll', array( $this, 'delete_poll' ) );
		add_action( 'wp_ajax_clone_poll', array( $this, 'clone_poll' ) );
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );
		add_action( 'wp_head', array( $this, 'poller_master_ajax_var' ));		
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ));
		
		add_shortcode( 'poller_master', array( $this, 'poller_master_shortcode' ) );
		
		/* voting and results */
		add_action( 'wp_ajax_vote_the_poll', array( $this, 'vote_the_poll' ) );
		add_action( 'wp_ajax_nopriv_vote_the_poll', array( $this, 'vote_the_poll' ) );
		
		add_action( 'wp_ajax_get_poll_results', array( $this, 'get_poll_results' ) );
		add_action( 'wp_ajax_nopriv_get_poll_results', array( $this, 'get_poll_results' ) );
		
		if( function_exists( 'vc_map' ) ){
			$this->add_to_vc();
		}
	}
	
	/* load text domain */
	function load_text_domain(){
		load_plugin_textdomain( 'poller_master', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	
	}	
	
	/* set VC ison */
	function vc_icon(){
		wp_enqueue_style('poller_master_vc_icon', POLL_URL . '/assets/admin/css/vc_icon_style.css');
	}
	
	/* create image for color scheme preview */
	function get_scheme_preview( $skin, $color ){
		if( $skin !== "polaris" && $skin !== "futurico" ){
			return POLL_URL.'/assets/frontend/css/skins/'.$skin.'/'.$color.'.png';
		}
		else{
			return POLL_URL.'/assets/frontend/css/skins/'.$skin.'/'.$skin.'.png';
		}
	}
	
	/* get image src */
	function get_image_src( $image_id ){
		if( !empty( $image_id ) ){
			$image_data = wp_get_attachment_image_src( $image_id, "thumbnail" );
			return $image_data[0];
		}
		else{
			return '';
		}
	}
	
	/* form loaer thumbnail */
	function get_loader_thumb( $image_id ){
		echo '<img src="'.$this->get_image_src( $image_id ).'" class="poller_master_thumbnail">
			  <a href="javascript:;" class="delete_loader">[X]</a>';
	}
	
	/* create ajax url */
	function poller_master_ajax_var(){
		echo '<script type="text/javascript">var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';</script>';
	}
	/* APPEND BUTTONS TO THE WORDPRESS EDITOR */
	function poller_master_tinymce_buttons(){
		$can_add_select = true;
		if( isset( $_GET['page'] ) ){
			if( strpos( $_GET['page'], 'poller_master' ) !== false ){
				$can_add_select = false;
			}
		}
		if( strpos ( $_SERVER['PHP_SELF'], 'admin-ajax.php' ) === false && $can_add_select === true ){
			add_filter( 'mce_external_plugins', array( $this, 'poller_master_add_buttons' ) );
			add_filter( 'mce_buttons', array( $this, 'poller_master_register_buttons' ) );	
		}
	}	
	function poller_master_add_buttons( $plugin_array ) {
		$wp_version = str_replace( ".", "", get_bloginfo( 'version' ) );
		if( strlen($wp_version) < 3 ){
			$wp_version = intval( $wp_version."0" );
		}
		if( $wp_version < 390 ){
			$plugin_array['poller_master'] = POLL_URL . '/assets/admin/js/mceselect_old.js';
		}
		else{
			$plugin_array['poller_master'] = POLL_URL . '/assets/admin/js/mceselect_new.js';
		}
		return $plugin_array;
	}
	function poller_master_register_buttons( $buttons ) {
		array_push( $buttons, 'poller_master_select' ); 
		$this->script_all_polls();
		return $buttons;
	}
	
	/* get all polls for the listing in the text editor or for the visual compsoer element */
	function get_all_polls(){
		global $wpdb;
		$polls = $wpdb->get_results( "SELECT * FROM {$this->polls_table_name}" );	

		return $polls;
	}
	
	/* gett all polls for editor listbox */
	function script_all_polls(){
		$all_polls = $this->get_all_polls();
		$polls_data =  '<script type="text/javascript"> var polls_array = ';
		$polls_array = array();
		if( !empty( $all_polls ) ){
			foreach( $all_polls as $poll ){
				$polls_array[] = array(
					"key" => $poll->name,
					"value" => $poll->id
				);
			}
		}
		$polls_data .= json_encode( $polls_array );
		$polls_data .= '</script>';
		echo $polls_data;
	}
	/* END FOR BUTTONS IN EDITOR */
	
	/* CHECK FOR INSTALLATION */
	/* check if the plugin has created necessary tables in the database */
	function check_activation(){
		/* first check the version and o upgrade if it is needed */
		if( get_option('poller_master_version') ){
			$version = get_option('poller_master_version');
			if( $version < $this->version ){
				$this->upgrade();
			}
		}
		/* than check for the activartion and if it not activaed than install the necessary databases and set version and activation options */
		$this->activated = get_option('poller_master_activation');
		if( $this->activated == "0" ){
			$this->install_poller_master();
		}
	}
	/* set table names to variables */
	function populate_variables(){
		global $wpdb;
		$this->polls_table_name = $wpdb->prefix . "poller_master_polls";
		$this->logs_table_name = $wpdb->prefix . "poller_master_logs";	
	}
	
	/* create table for storing poll logs */
	function install_poller_master(){
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		/* first create table where the polls will be stored */
		$sql = "CREATE TABLE IF NOT EXISTS {$this->polls_table_name} (
		  id INT(9) NOT NULL AUTO_INCREMENT,
		  created_time VARCHAR(255) NOT NULL,
		  name VARCHAR(255) NOT NULL,
		  question TEXT NOT NULL,
		  answers TEXT NOT NULL,
		  multiple VARCHAR(4) NOT NULL,
		  votes INT(10) NOT NULL,
		  template VARCHAR(20) NOT NULL,
		  frequency VARCHAR(10) NOT NULL,
		  voters VARCHAR(11) NOT NULL,
		  start_date VARCHAR(255) NOT NULL,
		  end_date VARCHAR(255) NOT NULL,
		  countdown VARCHAR(1) NOT NULL,
		  status VARCHAR(1) NOT NULL,
		  zero_vote VARCHAR(1) NOT NULL,
		  PRIMARY KEY (id)
		)CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );		
		/* than create table where the polling logs will be stored */
		$sql = "CREATE TABLE IF NOT EXISTS {$this->logs_table_name} (
		  log_id int(9) NOT NULL AUTO_INCREMENT,
		  vote_time VARCHAR(255) NOT NULL,
		  username VARCHAR(50) NOT NULL,
		  ip VARCHAR(15) NOT NULL,
		  poll_id INT(9) NOT NULL,
		  user_id BIGINT(20) NOT NULL,
		  votes VARCHAR(10) NOT NULL,
		  registered VARCHAR(1) NOT NULL,
		  PRIMARY KEY (log_id),
		  FOREIGN KEY (poll_id) REFERENCES {$this->polls_table_name}(id) ON UPDATE CASCADE ON DELETE CASCADE
		)CHARACTER SET utf8 COLLATE utf8_general_ci;";
		
		dbDelta( $sql );

		update_option( 'poller_master_activation', '1');
		update_option( 'poller_master_version', $this->version);		
	}
	/* END FOR INSTALLATION */
	
	/* retrieve polls data from the database */
	function get_polls(){
		global $wpdb;
		$polls = $wpdb->get_results( "SELECT * FROM {$this->polls_table_name}" );
		return $polls;
	}

	/* retrieve poll by ID */
	function get_poll_by_id( $poll_id ){
		global $wpdb;
		$wpdb->hide_errors();
		$poll = $wpdb->get_results( "SELECT * FROM {$this->polls_table_name} WHERE id={$poll_id}" );
		return array_shift( $poll );
	}
	
	/* format poll time */
	function format_time( $timestamp, $format_type = '1' ){
		if( !empty( $timestamp ) ){
			if( $format_type == '1' ){
				return date( "M jS, Y @ H:i", $timestamp );
			}
			else{
				return date( 'm/d/Y H:i', $timestamp );
			}
		}
		else{
			return '';
		}
	}
	/* add scripts for the frontend */
	function frontend_scripts(){
		wp_enqueue_script('jquery');
		if( $this->options['enqueue_bootstrap'] == "yes"){
			wp_enqueue_style('poller_master_bootstrap_front_css', POLL_URL . '/assets/frontend/css/bootstrap.min.css');
			wp_enqueue_script('poller_master_bootstrap_front_js', POLL_URL . '/assets/frontend/js/bootstrap.min.js', array(), false, true);			
		}
		wp_enqueue_script( 'poller_master_ui_js', trailingslashit( POLL_URL ) . 'assets/admin/js/jquery-ui.min.js', array(), false, true );
		wp_enqueue_script( 'poller_master_kkcountdown_js', trailingslashit( POLL_URL ) . 'assets/frontend/js/kkcountdown.min.js', array(), false, true );

		wp_enqueue_script( 'poller_master_bootstrap-progressbar_js', trailingslashit( POLL_URL ) . 'assets/frontend/js/bootstrap-progressbar.min.js', array(), false, true );
		
		wp_enqueue_style( 'poller_master_icheck_css', trailingslashit( POLL_URL ) . 'assets/frontend/css/skins/all.css');
		wp_enqueue_script( 'poller_master_icheck_js', trailingslashit( POLL_URL ) . 'assets/frontend/js/icheck.min.js', array(), false, true );
		
		wp_enqueue_style('poller_master_style_front', POLL_URL . '/assets/frontend/css/poller_master_style_front.css');
		wp_enqueue_script('poller_master_script_front', POLL_URL . '/assets/frontend/js/poller_master_script_front.js', array(), false, true);
	}
	
	/* append admin scripts */	
	function admin_scripts(){
		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'poller_master_datatable_js', trailingslashit( POLL_URL ) . 'assets/admin/js/jquery.dataTables.js', array(), false, true );
		wp_enqueue_style( 'poller_master_datatable_css', trailingslashit( POLL_URL ) . 'assets/admin/css/jquery.dataTables.css' );
		
		wp_enqueue_style( 'poller_master_morris_css', trailingslashit( POLL_URL ) . 'assets/admin/css/morris.min.css' );
		wp_enqueue_script( 'poller_master_raphael_js', trailingslashit( POLL_URL ) . 'assets/admin/js/raphael-min.js', array(), false, true );
		wp_enqueue_script( 'poller_master_morris_js', trailingslashit( POLL_URL ) . 'assets/admin/js/morris.min.js', array(), false, true );
		
		wp_enqueue_script( 'poller_master_ui_js', trailingslashit( POLL_URL ) . 'assets/admin/js/jquery-ui.min.js', array(), false, true );
		wp_enqueue_style( 'poller_master_ui_css', 'http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		
		wp_enqueue_style('poller_master_timepicker_css', trailingslashit( POLL_URL ) . 'assets/admin/css/jquery-ui-timepicker-addon.min.css' );
		wp_enqueue_script( 'poller_master_timepicker_js', trailingslashit( POLL_URL ) . 'assets/admin/js/jquery-ui-timepicker-addon.min.js', array(), false, true );
		wp_enqueue_script( 'poller_master_lideaccess_js', trailingslashit( POLL_URL ) . 'assets/admin/js/jquery-ui-sliderAccess.js', array(), false, true );		
		
		wp_enqueue_style( 'poller_master_bootstrap_css', trailingslashit( POLL_URL ) . 'assets/admin/css/bootstrap.min.css' );
		wp_enqueue_script( 'poller_master_bootstrap_js', trailingslashit( POLL_URL ) . 'assets/frontend/js/bootstrap.min.js', array(), false, true );
		
		wp_enqueue_style( 'poller_master_style', trailingslashit( POLL_URL ) . 'assets/admin/css/poller_master_style.css' );
		wp_enqueue_script( 'poller_master_script', trailingslashit( POLL_URL ) . 'assets/admin/js/poller_master_script.js', array(), false, true );
	}	
	/* -------------------------------------------------------------MANAGER FUNCTIONS---------------------------------------- */
	/* return poll data */
	function get_edit_poll_data(){
		$poll = $this->get_poll_by_id( $_POST['poll_id'] );
		if( !empty( $poll ) ){
			$response = array(
				"id" 			=> $poll->id,
				"name"			=> $poll->name,
				"question" 		=> html_entity_decode( $poll->question, ENT_COMPAT, 'UTF-8'),
				"created_time"	=> $this->format_time( $poll->created_time ),
				"answers" 		=> array_map('stripslashes_deep', unserialize( $poll->answers )),
				"multiple" 		=> $poll->multiple,
				"template" 		=> $poll->template,
				"frequency" 	=> $poll->frequency,
				"voters" 		=> $poll->voters,
				"start_date" 	=> $this->format_time( $poll->start_date, '2' ),
				"end_date" 		=> $this->format_time( $poll->end_date, '2' ),
				"countdown" 	=> $poll->countdown,
				"status" 		=> $poll->status,
				"zero_vote"		=> $poll->zero_vote
			);
			echo json_encode( $response );
		}
		else{
			echo json_encode( array( "error" => __( 'There is no poll with the provided ID.', 'poller_master' ) ) );
		}
		die();
	}
	/* remove the poll */
	function delete_poll(){
		global $wpdb;
		$poll_id = $_POST['poll_id'];
		$poll = $this->get_poll_by_id( $_POST['poll_id'] );
		if( !empty( $poll ) ){
			$wpdb->hide_errors();
			$info = $wpdb->delete( 
				$this->polls_table_name, 
				array( 'id' => $poll_id ) 
			);
			if( $info !== false ){
				echo json_encode( array( "success" => __( 'Poll has been successfully deleted.', 'poller_master' ) ) );
			}
			else{
				echo json_encode( array( "error" => __( 'Could not delete selected poll. Please try again.', 'poller_master' ) ) );
			}
		}
		else{
			echo json_encode( array( "error" => __( 'An error occured. There is no poll with the provided ID.', 'poller_master' ) ) );
		}
		die();
	}
	
	/* reset poll */
	function reset_poll(){
		global $wpdb;
		$poll_id = $_POST['poll_id'];
		$poll = $this->get_poll_by_id( $poll_id );
		if( !empty( $poll ) ){
			$info = $wpdb->delete(
				$this->logs_table_name,
				array( 'poll_id' => $poll_id )
			);
			if( $info !== false ){
				$info = $wpdb->update(
					$this->polls_table_name,
					array(
						"votes" => 0
					),
					array( 'id' => $poll_id )
				);
				if( $info !== false ){
					$response = array(
						"id"	=> $poll_id,
						"success" => __( 'You have successfully reset the poll.', 'poller_master' )
					);
				
					echo json_encode( $response );
				}
				else{
					echo json_encode( array( "error" => __( 'Could not update votes in polls table.', 'poller_master' ) ) );
				}
			}
		}
		else{
			echo json_encode( array( "error" => __( 'There is no poll with provided id.', 'poller_master' ) ) );
		}
		die();
	}
	
	/* get the stats data */
	function get_stats_logs( $poll_id, $offset ){
		global $wpdb;
		$logs = array();
		$limit = $this->options['load_logs'];
		if( $limit !== "" && $limit !== "all" ){
			$limit = "LIMIT {$limit}";
		}
		else{
			$limit = "";
		}
		$logs = $wpdb->get_results("SELECT * FROM {$this->logs_table_name} WHERE poll_id={$poll_id} {$limit} OFFSET {$offset}");
		return $logs;
	}
	/* format answers for the stats modal */
	function format_answers( $raw_answers ){
		$answer_data = "";
		if( !empty ( $raw_answers ) ){
			for( $i=0; $i<sizeof($raw_answers); $i++ ){
				$answer_data .= "<p>".($i+1).". ".$raw_answers[$i]."</p>";
			}
		}
		
		return $answer_data;
	}
	/* collect and send data for the stats modal */
	function get_stats(){
		$poll_id = $_POST['poll_id'];
		$poll = $this->get_poll_by_id( $poll_id );
		if( !empty( $poll ) ){
			$logs = $this->get_stats_logs( $poll_id, 0 );
			$limit = $this->options['load_logs'];
			if( $limit == "" && $limit == "all"){
				$limit = $logs;
			}
			$response = array(
				"name" 				=> $poll->name,
				"answers"			=> $this->format_answers( array_map('stripslashes_deep', unserialize( $poll->answers )) ),
				"summary_chart" 	=> $this->build_chart( $poll, $poll->multiple, 'all' ),				
				"registered_chart" 	=> "",
				"guests_chart" 		=> "",
				"logs" 				=> $logs,
				"all_logs"			=> sizeof( $logs ) < $limit ? "1" : "0",
				"chart_colors"		=> $this->options['chart_colors']
			);
			if( $poll->voters == "all" || $poll->voters == "registered"){
				$response["registered_chart"] = $this->build_chart( $poll, $poll->multiple, 'registered' );
			}
			if( $poll->voters == "all" || $poll->voters == "gusts"){
				$response["guests_chart"] = $this->build_chart( $poll, $poll->multiple, 'guests' );
			}
			
			echo json_encode( $response );
		}
		else{
			echo json_encode( array( "error" => __( 'There is no poll with the provided id.', 'poller_master' ) ) );
		}
		die();
	}
	/* load more logs in the stats modal */
	function load_more_stats(){
		$poll_id = $_POST['poll_id'];
		if( !empty( $poll_id ) ){
			$poll = $this->get_poll_by_id( $poll_id );
			$offset = !empty( $_POST['offset'] ) ? $_POST['offset'] : 0;
			if( !empty( $poll ) ){
				$logs = $this->get_stats_logs( $poll_id, $offset );
				$limit = $this->options['load_logs'];
				if( $limit == "" && $limit == "all"){
					$limit = $logs;
				}
				echo json_encode( array( 
					"logs" 		=> $logs,
					"all_logs"	=> sizeof( $logs ) <= $limit ? "1" : "0"
				) );
			}
		}
		
		die();
	}
	
	/* add new or edit existing poll */
	function make_timestamp( $raw_time ){
		$timestamp = "";
		if( !empty( $raw_time ) ){
			$tmp = explode( " ", $raw_time );
			$date = explode( "/", $tmp[0] );
			$time = explode( ":", $tmp[1] );
			
			$timestamp = mktime( intval($time[0]), intval($time[1]), 0, intval($date[0]), intval($date[1]), intval($date[2]) );
		}

		return $timestamp;
	}
	
	/* clone the poll */
	function clone_poll(){
		global $wpdb;
		$poll_id = $_POST['poll_id'];
		$poll = $this->get_poll_by_id( $poll_id );
		$response = array();
		if( !empty( $poll ) ){
			$info = $wpdb->insert(
				$this->polls_table_name, 
				array( 
					'id' 			=> '', 
					'created_time'	=> $poll->created_time,
					'name'			=> $poll->name,
					'question' 		=> $poll->question,
					'answers' 		=> $poll->answers,
					'multiple'		=> $poll->multiple,
					'votes'			=> $poll->votes,
					'template' 		=> $poll->template,
					'frequency' 	=> $poll->frequency,
					'voters' 		=> $poll->voters,
					'start_date'	=> $poll->start_date,
					'end_date'		=> $poll->end_date,
					'countdown'		=> $poll->countdown,
					'status'		=> $poll->status,
					'zero_vote'		=> $poll->zero_vote
				)
			);
			
			$response =	array(
				"success" 			=> '',
				"id" 				=> $wpdb->insert_id,
				"name"				=> $poll->name,
				"question" 			=> $poll->question,
				"votes"				=> $poll->votes,
				"created" 			=> isset( $poll->timestamp ) ? $this->format_time( $poll->timestamp ) : "",
				'start_date'		=> isset( $poll->start_date ) ? $this->format_time( $poll->start_date ) : "",
				'end_date'			=> isset( $poll->end_date ) ? $this->format_time( $poll->end_date ) : "",
				"actions" 			=> $this->action_buttons(),
				"status_html"		=> $this->poll_status( $poll ),
				"zero_vote"			=> $poll->zero_vote
			); 			
		}
		else{
			$response['error'] = '';
		}
		
		echo json_encode( $response );
		die();
	}
	
	/* update poll or add a new one */
	function update_poll(){
		global $wpdb;
		$poll_id = $_POST['poll_id'];
		$name = esc_html( stripslashes( $_POST['name'] ) );
		$question = esc_html( stripslashes( $_POST['question'] ) );
		$answers = $_POST['answers'];
		$multiple = $_POST['multiple'];
		$voters = $_POST['voters'];
		$start_date_timestamp = $this->make_timestamp( $_POST['start_date'] );
		$end_date_timestamp = $this->make_timestamp( $_POST['end_date'] );
		$status = $_POST['status'];
		$zero_vote = $_POST['zero_vote'];
		/* if post id iz 0 than it means to add a new poll */
		if( $poll_id == "0" ){
			$timestamp = time();
			$votes = 0;
			$wpdb->hide_errors();
			$info = $wpdb->insert(
				$this->polls_table_name, 
				array( 
					'id' 			=> '', 
					'created_time'	=> $timestamp,
					'name'			=> $name,
					'question' 		=> $question,
					'answers' 		=> serialize( $answers ),
					'multiple'		=> $multiple,
					'votes'			=> $votes,
					'template' 		=> $_POST['template'],
					'frequency' 	=> $_POST['frequency'],
					'voters' 		=> $voters,
					'start_date'	=> $start_date_timestamp,
					'end_date'		=> $end_date_timestamp,
					'countdown'		=> $_POST['countdown'],
					'status'		=> $_POST['status'],
					'zero_vote'		=> $zero_vote
				)
			);	
			if( $info !== false ){
				$new_poll = "1";
				$row_id = $wpdb->insert_id;	
				$success_message = __( 'The poll has been successfully saved.', 'poller_master' );
			}
			else{
				echo json_encode( array("error" => __( 'There was an error saving the poll. Please try again.', 'poller_master' )) );
				die();
			}
		}
		else{
			$poll = $this->get_poll_by_id( $poll_id );
			if( !empty( $poll ) ){
				$votes = $poll->votes;
				$wpdb->hide_errors();
				$info = $wpdb->update(
					$this->polls_table_name, 
					array(
						'name'			=> $name,
						'question' 		=> $question,
						'answers' 		=> serialize( $answers ),
						'multiple'		=> $multiple,
						'template' 		=> $_POST['template'],
						'frequency' 	=> $_POST['frequency'],
						'voters' 		=> $voters,
						'start_date'	=> $start_date_timestamp,
						'end_date'		=> $end_date_timestamp,
						'countdown'		=> $_POST['countdown'],
						'status'		=> $_POST['status'],
						'zero_vote'		=> $zero_vote
					),
					array( 
						'id' => $poll_id 
					)
				);
				if( $info !== false ){
					$row_id = $poll_id;
					$new_poll = "0";
					$success_message = __( 'The poll has been successfully edited.', 'poller_master' );
				}
				else{
					echo json_encode( array("error" => __( 'There was an error updating the poll. Please try again.', 'poller_master' )) );
					die();
				}
			}
			else{
				echo json_encode( array("error" => __( 'There is no poll with the provided ID. Please try again.', 'poller_master' )) );
				die();
			}
		}
		$response =	array(
			"success" 			=> $success_message,
			"id" 				=> $row_id,
			"name"				=> $name,
			"question" 			=> $question,
			"votes"				=> $votes,
			"created" 			=> isset( $timestamp ) ? $this->format_time( $timestamp ) : "",
			'start_date'		=> isset( $start_date_timestamp ) ? $this->format_time( $start_date_timestamp ) : "",
			'end_date'			=> isset( $end_date_timestamp ) ? $this->format_time( $end_date_timestamp ) : "",
			"actions" 			=> $this->action_buttons(),
			"new_poll" 			=> $new_poll,
			"status_html"		=> $this->poll_status( $this->get_poll_by_id( $row_id ) ),
			"zero_vote"			=> $zero_vote
		); 
		echo json_encode( $response );	
		die();		
		
	}
	/* calculate votes for the poll */
	function get_results_from_logs( $poll_id, $multiple, $data_source, $raw_answers ){
		global $wpdb;
		$results = array();
		$filter = "";
		if( $data_source == "registered"){
			$filter = "AND registered='1'";
		}
		else if( $data_source == "guests" ){
			$filter = "AND registered='0'";
		}
		
		if( $multiple == "no" ){
			/* get votes restuls */
			$query = "SELECT votes, COUNT(*) as count FROM {$this->logs_table_name} WHERE poll_id={$poll_id} {$filter} GROUP BY votes";
			$results = $wpdb->get_results( $query );
			$results_answers = array();
			if( !empty( $results ) ){
				foreach( $results as $result ){
					$results_answers[$result->votes] = $result->count;
				}
			}
			$results = $results_answers;			
		}
		else{
			for( $i=0; $i<sizeof( $raw_answers ); $i++ ){
				$data_results = $wpdb->get_results( "SELECT COUNT(*) as count FROM {$this->logs_table_name} WHERE poll_id={$poll_id} {$filter} AND FIND_IN_SET( '{$i}', votes );" );
				$res = array_shift( $data_results );
				$results[$i] = $res->count;
			}
		}
		
		return $results;
	}
	
	/* 
	   build chart
	   $data_source can be all, registered,  guests
	*/
	function build_chart( $poll, $multiple, $data_source ){
		$chart_data = "";
		$raw_answers = array_map('stripslashes_deep', unserialize( $poll->answers ));
		$results = $this->get_results_from_logs( $poll->id, $multiple, $data_source, $raw_answers );
		/* now build input fields for all the answers ad if there is no vote for some answer than add 0 */
		for( $i=0; $i<sizeof( $raw_answers ); $i++ ){
			$chart_data .= '<input type="hidden" value="'.( !empty( $results[$i] ) ? $results[$i] : 0 ).'">';
		}
		
		return $chart_data;
	}
	/* create action buttons in the poll listing */
	function action_buttons(){
		return '
			<a href="#poll" data-toggle="tooltip" data-placement="top" data-original-title="'.__( 'View / Edit Poll', 'poller_master' ).'" class="edit_poll" data-modal_title="'.__( 'Edit Poll', 'poller_master' ).'" data-ready="glyphicon glyphicon-wrench" data-working="glyphicon glyphicon-time"><span class="glyphicon glyphicon-wrench"></span></a>
			&nbsp; | &nbsp;
			<a href="javascript:;" data-toggle="tooltip" data-placement="top" data-original-title="'.__( 'Delete Poll', 'poller_master' ).'" class="delete_poll" data-confirm="'.__( 'Are you sure you want to delete this poll? All data associated with this poll including the log data will be permanently lost.', 'poller_master' ).'" data-ready="glyphicon glyphicon-remove" data-working="glyphicon glyphicon-time"><span class="glyphicon glyphicon-remove"></span></a>
			&nbsp; | &nbsp;
			<a href="javascript:;" data-toggle="tooltip" data-placement="top" data-original-title="'.__( 'Reset Poll', 'poller_master' ).'" class="reset_poll" data-confirm="'.__( 'Are you sure you want to reset this poll? All  log data will be permanently lost.', 'poller_master' ).'" data-ready="glyphicon glyphicon-share-alt" data-working="glyphicon glyphicon-time"><span class="glyphicon glyphicon-share-alt"></span></a>
			&nbsp; | &nbsp;
			<a href="#stats" data-toggle="tooltip" data-placement="top" data-original-title="'.__( 'View Poll Stats', 'poller_master' ).'" class="stats_poll" data-ready="glyphicon glyphicon-stats" data-working="glyphicon glyphicon-time"><span class="glyphicon glyphicon-stats"></span></a>
			&nbsp; | &nbsp;
			<a href="#stats" data-toggle="tooltip" data-placement="top" data-original-title="'.__( 'Clone Poll', 'poller_master' ).'" class="clone_poll" data-ready="glyphicon glyphicon-th-large" data-working="glyphicon glyphicon-time"><span class="glyphicon glyphicon-th-large"></span></a>
		';
	}
	
	function poll_status( $poll ){
		$poll_status = '<span class="poll_open">'.__( 'Open', 'poller_master' ).'</span>';

		if( $poll->status == "0" ){
			$poll_status = '<span class="poll_closed">'.__( 'Closed', 'poller_master' ).'</span>';
		}
		if( $poll->start_date !== "" && time() < $poll->start_date ){
			$poll_status = '<span class="poll_schedule">'.__( 'Schedule', 'poller_master' ).'</span>';
		}		
		if( $poll->end_date !== "" && time() > $poll->end_date ){
			$poll_status = '<span class="poll_ended">'.__( 'Ended', 'poller_master' ).'</span>';
		}

		return $poll_status;
	}
	
	/* create polls list for poll manager */
	function polls_table(){
		$polls_data = "";
		$polls = $this->get_polls();
		if( !empty( $polls ) ){			
			for( $i=0; $i<sizeof($polls); $i++ ){
				/* list polls */
				$poll = $polls[$i];
				$polls_data .= "
					<tr class=\"".( $i % 2 == 0 ? "": "alternate" )."\" data-poll_id=\"".$poll->id."\">
						<td>".$poll->id."</td>
						<td>".$poll->name."</td>
						<td class=\"column-tags\">".$poll->votes."</td>
						<td class=\"column-tags\">".$this->format_time( $poll->start_date )."</td>
						<td class=\"column-tags\">".$this->format_time( $poll->end_date )."</td>
						<td class=\"column-tags\">".$this->poll_status( $poll )."</td>
						<td>".$this->action_buttons()."</td>
					</tr>
				";
			}
		}
		
		return $polls_data;
	}
	
	/* crete array of the templates */
	function form_templates_select(){
		$templates = "";
		foreach( $this->options['templates'] as $template_id => $template_data ){
			$templates .= '<option value="'.$template_id.'">'.$template_data['template_name'].'</option>';
		}
		
		return $templates;
	}
	
	/* -------------------------------------------------------------END MANAGER FUNCTIONS---------------------------------------- */
	
	/* get the saved options */
	function get_options(){
		$templates = $this->options['templates'];
		$saved_options = get_option('poller_master_options');
		if( !is_array( $saved_options ) ){
			$saved_options = array();
		}
		
		/* this is used to add only inputet tempaltes and the removed one will be removed from option in the database after the save button is pressed */
		$this->options = array_merge( $this->options, $saved_options );
		
		foreach( $templates as $key => $data ){
			if( !empty( $this->options['templates'][$key] ) ){
				$templates[$key] = array_merge( $templates[$key], $this->options['templates'][$key] );
			}
		}
		
		$this->options['templates'] = $templates;
	}
	/* save options */
	function save_options(){
		$info = update_option( 'poller_master_options', $this->options );
		return $info;
	}
	/* add a link to the setting in the admin menu */
	function register_options_page(){
		add_menu_page(__('Poller Master', 'poller_master'), __('Poller Master', 'poller_master'), 'manage_options', 'poller_master/poller_master_manager.php', '', plugins_url('poller_master/assets/admin/images/logo.png'));
		add_submenu_page('poller_master/poller_master_manager.php', __('Manage Polls', 'poller_master'), __('Manage Polls', 'poller_master'), 'manage_options', 'poller_master/poller_master_manager.php');
		add_submenu_page('poller_master/poller_master_manager.php', __('Options', 'poller_master'), __('Options', 'poller_master'),  'manage_options', 'poller_master/poller_master_options.php');
		add_submenu_page('poller_master/poller_master_manager.php', __('Reset', 'poller_master'), __('Reset', 'poller_master'), 'manage_options', 'poller_master/poller_master_reset.php');
		add_submenu_page('poller_master/poller_master_manager.php', __('Export / Import', 'poller_master'), __('Export / Import', 'poller_master'), 'manage_options', 'poller_master/poller_master_expimp.php');
	}
	
	/* ------------------------------------------FRONT END -----------------------------------------*/
	/* form the results for the frontend */
	function form_the_results( $poll ){
		$raw_answers = array_map('stripslashes_deep', unserialize( $poll->answers ));
		$results = $this->get_results_from_logs( $poll->id, $poll->multiple, $poll->voters, $raw_answers );		
		
		$template_data = $this->default_template;
		if( !empty( $this->options['templates'][$poll->template] ) ){
			$template_data = array_merge( $this->default_template, $this->options['templates'][$poll->template] );
		}
		
		$bar_colors = explode( ",", $template_data['progress_bar_colors'] );

		$result_data = '<div class="poller_master_res_text">'.str_replace( "{%TOTAL_VOTES%}", $poll->votes, $this->options['vote_results_text'] ).'</div>
						<div class="poller_master_close_res"><button type="button" class="close">&times;</button></div>
						<div class="poller_master_result_percentage">';
						/* now build input fields for all the answers ad if there is no vote for some answer than add 0 */
						for( $i=0; $i<sizeof( $raw_answers ); $i++ ){
							$percentage = 0;
							$votes = 0;
							if( !empty( $results[$i] ) ){
								$percentage = ( $results[$i] / $poll->votes ) * 100;
								$percentage = round( $percentage, 2 );
								$votes = $results[$i];
							}
							$answer_vote_results = '';

							switch( $template_data['answer_vote_results'] ){
								case 'percentage_only' : $answer_vote_results = $percentage.'%'; break;
								case 'votes_only' : $answer_vote_results = $votes; break;
								case 'both' : $answer_vote_results = $votes.' ['.$percentage.'%]'; break;
							}
							
							if( !empty( $bar_colors[$i] ) ){
								$bar_color = 'style="background-color: '.$bar_colors[$i].'"';
							}

							$result_data .= '<div class="poller_master_bar">
												<p>'.$raw_answers[$i].'&nbsp;&nbsp;&nbsp;'.$answer_vote_results.'</p>
												<div class="progress '.$template_data['progress_bar_style'].'">
													<div class="progress-bar progress-bar-info" '.$bar_color.' role="progressbar" aria-valuetransitiongoal="'.$percentage.'"></div>
												</div>
											</div>';
						}
		$result_data .= '</div>';

		return $result_data;
	}
	/* on get results from the frontend */
	function get_poll_results(){
		$poll_id = $_POST['poll_id'];
		if( !empty( $poll_id ) ){
			$poll = $this->get_poll_by_id( $poll_id );
			if( !empty( $poll ) ){
				if( $this->can_see_results( $poll ) ){					
					echo json_encode( array("results_html" => $this->form_the_results( $poll ) ) );
				}
				else{
					echo json_encode( array("error" => $this->options['results_denied_text'] ) );
				}
			}
		}
		die();
	}
	/* voting the poll */
	function vote_the_poll(){
		global $wpdb;
		$poll_id = $_POST['poll_id'];
		if( !empty( $poll_id ) ){
			$poll = $this->get_poll_by_id( $poll_id );
			if( !empty( $poll ) ){
				$user_log = $this->get_user_log( $poll_id );
				if( $this->can_poll_vote( $poll, $user_log ) ){
					$current_user = wp_get_current_user();
					if( $current_user->ID === 0 ){
						$username = "Guest";
						$registered = 0;
						$user_id = $current_user->ID;
					}
					else{
						$username = $current_user->user_login;
						$registered = 1;
						$user_id = $current_user->ID;
					}
				
					$wpdb->hide_errors();
					$new_user_log =	array(
						'vote_time'	=> time(),
						'username' 	=> $username,
						'ip' 		=> $_SERVER['REMOTE_ADDR'],
						'poll_id' 	=> $poll_id,
						'user_id'	=> $current_user->ID,
						'votes' 	=> join( ",", (array)$_POST['votes'] ),
						'registered'=> $registered,
					);
					
					$info = $wpdb->insert(
						$this->logs_table_name,
						$new_user_log
					);				
					
					if( $info !== false  ){
						$poll->votes += sizeof( (array)$_POST['votes'] );
						$info = $wpdb->update(
							$this->polls_table_name, 
							array( 
								'votes' => $poll->votes
							),
							array( 
								'id' => $poll->id
							)
						);
						if( $info !== false ){
							$countown_vote = "";
							if( $poll->frequency === 0 ){
								if( !empty( $poll->end_date ) ){
									$expire = $poll->end_date;
								}
								else{
									$expire = time()+(60*60*24*365);
								}
								setcookie( 'poller_master_voting'.$poll->id, time(), $expire );
							}
							else if( $poll->frequency > 0 ){
								$user_log = json_decode( json_encode($new_user_log), false );
								$countown_vote = $this->check_frequency( $user_log, "", $poll->frequency, $poll->template );
								setcookie( 'poller_master_voting'.$poll->id, time(), time()+(60*60*24*$poll->frequency) );								
							}
							echo json_encode( array(
								"success"		=> $this->options['vote_success_text'],
								"results_html" 	=> $this->form_the_results( $poll ),
								"can_vote"		=> $this->can_poll_vote( $poll, $user_log ),
								"countdown_vote"=> $countown_vote
							) );
						}
						else{							
							echo json_encode( array("error" => $this->options['vote_error_text'] ) );
						}
					}
					else{
						echo json_encode( array("error" => $this->options['vote_error_text'] ) );
					}
				}
				else{
					echo json_encode( array("error" => $this->options['vote_already_text'] ) );
				}
			}
		}
		die();
	}
	/* check if the user can see the answers */
	function can_see_results( $poll ){
		global $wpdb;
		$can_see = true;
		$cookie = $this->get_cookie( $poll->id );
		if( $poll->zero_vote == "0" ){			
			$ip = $_SERVER['REMOTE ADDR'];
			$user_log = $this->get_user_log( $poll->id );
			if( empty( $user_log ) && empty( $cookie ) ){
				$can_see = false;
			}
		}
		
		return $can_see;
	}
	/* check if the countdown untill the end is set for the poll */
	function can_poll_countdown( $poll ){
		$can_countdown = false;		
		if( $poll->end_date !== "" && $poll->countdown == "1" && $poll->status == "1"){
			$can_countdown = true;
		}
		
		return $can_countdown;
	}
	/* check if the poll has expired */
	function is_expire( $end_date ){
		$is_expire = false;
		if( $end_date !== "" ){
			if( time() >= $end_date ){
				$is_expire = true;
			}
		}
		
		return $is_expire;
	}
	/* check if the poll can be shown */
	function is_started( $start_date ){
		$is_started = true;
		if( $start_date !== "" ){
			if( time() < $start_date ){
				$is_started = false;
			}
		}
		
		return $is_started;
	}	
	
	/* check if the user can vote */
	function can_poll_vote( $poll, $user_log ){
		$cookie = $this->get_cookie( $poll->id );
		$can_vote = true;
		$user_status = is_user_logged_in() === true ? "registered" : "guests";
		
		if( $this->is_expire( $poll->end_date ) ){
			$can_vote = false;
		}
		else if( $poll->status ==  "0" ){
			$can_vote = false;
		}
		else if( $poll->voters != $user_status && $poll->voters !== "all" ){
			$can_vote = false;
		}
		else{
			if( $poll->frequency == 0 ){
				if( !empty( $user_log ) || !empty( $cookie ) ){
					$can_vote = false;
				}
			}
			else if( $poll->frequency > 0 ){
				if( !empty( $user_log ) || !empty( $cookie ) ){
					if( !empty( $user_log ) ){
						$vote_time  = $user_log->vote_time;
					}
					else{
						$vote_time = $cookie;
					}
					$daysdiff = (time() - $vote_time) / (60*60*24);
					
					if( $daysdiff < $poll->frequency ){
						$can_vote = false;
					}
				}
			}
		}
		return $can_vote;
	}
	
	/* get last user log */
	function get_user_log( $poll_id ){
		global $wpdb;
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_log = $wpdb->get_results( "SELECT * FROM {$this->logs_table_name} WHERE poll_id={$poll_id} AND ip='{$ip}' ORDER BY log_id DESC LIMIT 1" );
		return array_shift( $user_log );
	}
	
	/* check to see if the countdown for the vote should be displayed */
	function check_frequency( $user_log, $cookie, $poll_frequency, $template_id ){
		$template_data = $this->get_template_data( $template_id );
	
		if( !empty( $user_log ) || !empty( $cookie ) ){			
			$shortcode_data = "";			
			$frequency_timestamp = $poll_frequency*24*60*60;
			if( !empty( $user_log ) ){
				$elapsed_time = time() - $user_log->vote_time;
			}
			else{
				$elapsed_time = time() - $cookie;
			}
			
			if( $elapsed_time < $frequency_timestamp ){
				$vote_in = time() + $frequency_timestamp - $elapsed_time;
				$shortcode_data .= '<div class="alert alert-info top_margin"><span class="glyphicon glyphicon-info-sign"></span>'.$this->options['vote_again_text'].'</div>';
				$shortcode_data .= '
					<div class="poller_master_vote_countdown" 
						data-time="'.$vote_in.'" 
						data-day_text="'.$this->options['day_text'].'" 
						data-days_text="'.$this->options['days_text'].'" 
						data-hours_text="'.$this->options['hours_text'].'" 
						data-minutes_text="'.$this->options['minutes_text'].'" 
						data-seconds_text="'.$this->options['seconds_text'].'"
						data-show_texts="'.$template_data['counter_vote_show_text'].'">
					</div>';
			}
			
			return $shortcode_data;
		}
	}
	
	/*creating stye for the poll*/
	function hex2rgba( $hex ){
		$hex = str_replace("#", "", $hex);

		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
		return $r.", ".$g.", ".$b;
	}	
	
	function create_style( $template_id, $template_data ){
		extract( $template_data );		
		$import_fonts = '';
		$import_fonts .= $counter_poll_font !== "" ? $counter_poll_font.'|' : '';
		$import_fonts .= $counter_vote_font !== "" ? $counter_vote_font.'|' : '';
	
		return '
			<style>
				@import url(http://fonts.googleapis.com/css?family='.$import_fonts.');
				
				.'.$template_id.'{
					'.( $poll_bg_color !== "" ? 'background: '.$poll_bg_color.';' : '' ).'
					'.( $poll_border_color !== "" ? 'border: 1px solid '.$poll_border_color.';' : '' ).'
					'.( $poll_border_radius !== "" ? '
						border-radius: '.$poll_border_radius.';
						-moz-border-radius: '.$poll_border_radius.';
						-webkit-border-radius: '.$poll_border_radius.';
						' : '' ).'
				}
				.'.$template_id.' .poller_master_answers ul{
					list-style: none;
					list-style-type: none;
					margin-left: 0px;
				}				
				.'.$template_id.' .poller_master_answers ul li{
					color: '.$answers_color.';
					font-size: '.$answers_font_size.'px;
					answer_font_weight: '.$answers_font_weight.';
					line-height: '.$answers_line_height.'px;
					list-style: none;
					list-style-type: none;
				}

				.'.$template_id.' .poll_countdown span{
					'.( $counter_poll_font !== "" ? 'font-family: "'.str_replace( "+", " ", $counter_poll_font).'", sans-serif;' : '' ).'
				}
				
				
				.'.$template_id.' .poller_master_poll_countdown .btn-info{					
					background-color: '.$counter_poll_bg_color.';
					border-color: '.$counter_poll_border_color.';
					color: '.$counter_poll_font_color.';
					'.( $counter_poll_border_radius !== "" ? '
						border-radius: '.$counter_poll_border_radius.';
						-moz-border-radius: '.$counter_poll_border_radius.';
						-webkit-border-radius: '.$counter_poll_border_radius.';
						' : '' ).'					
				}
				
				.'.$template_id.' .poller_master_poll_countdown .btn-info:hover{
					background-color: '.$counter_poll_bg_hvr_color.';
					border-color: '.$counter_poll_border_hvr_color.';
				}

				.'.$template_id.' #poller_master_vote{
					background-color: '.$vote_btn_bg_color.';
					border: 1px solid '.$vote_btn_border_color.';
					color: '.$vote_btn_font_color.';
					font-weight: '.$vote_btn_font_weight.';
					'.( $vote_btn_width !== "" ? 'width: '.$vote_btn_width.';' : '' ).'
					'.( $vote_btn_border_radius !== "" ? '
						border-radius: '.$vote_btn_border_radius.';
						-moz-border-radius: '.$vote_btn_border_radius.';
						-webkit-border-radius: '.$vote_btn_border_radius.';
						' : '' ).'					
				}
				
				.'.$template_id.' #poller_master_vote:hover, .'.$template_id.' #poller_master_vote:focus, .'.$template_id.' #poller_master_vote:active{
					padding: 6px 12px;
					background: '.$vote_btn_bg_hvr_color.';
					border: 1px solid '.$vote_btn_hvr_border_color.';				}
				
				.'.$template_id.' #poller_master_results{
					background: '.$result_btn_bg_color.';
					border: 1px solid '.$result_btn_border_color.';
					color: '.$result_btn_font_color.';
					font-weight: '.$result_btn_font_weight.';
					'.( $result_btn_width !== "" ? 'width: '.$result_btn_width.';' : '' ).'
					'.( $result_btn_border_radius !== "" ? '
						border-radius: '.$result_btn_border_radius.';
						-moz-border-radius: '.$result_btn_border_radius.';
						-webkit-border-radius: '.$result_btn_border_radius.';
						' : '' ).'	
				}
				
				.'.$template_id.' #poller_master_results:hover, .'.$template_id.' #poller_master_results:focus, .'.$template_id.' #poller_master_results:active{
					padding: 6px 12px;
					background: '.$result_btn_bg_hvr_color.';
					border: 1px solid '.$result_btn_hvr_border_color.';	
				}

				
				.'.$template_id.' .vote_countdown span{
					'.( $counter_vote_font !== "" ? 'font-family: "'.str_replace( "+", " ", $counter_vote_font).'", sans-serif;' : '' ).'
				}
				
				.'.$template_id.' .poller_master_vote_countdown .btn-info{
					background-color: '.$counter_vote_bg_color.';
					border-color: '.$counter_vote_border_color.';
					color: '.$counter_vote_font_color.';
					'.( $counter_vote_border_radius !== "" ? '
						border-radius: '.$counter_vote_border_radius.';
						-moz-border-radius: '.$counter_vote_border_radius.';
						-webkit-border-radius: '.$counter_vote_border_radius.';
						' : '' ).'
				}
				
				.'.$template_id.' .poller_master_vote_countdown .btn-info:hover{
					background-color: '.$counter_vote_bg_hvr_color.';
					border-color: '.$counter_vote_border_hvr_color.';
				}
				
				.'.$template_id.' .poller_master_results{
					background-color: rgba( '.$this->hex2rgba( $results_box_bg_color ).', '.$results_box_opacity.' );
					border-color: '.$results_box_border_color.';
					color: '.$results_box_font_color.';
					font-weight: '.$results_box_font_weight.';
					font-size: '.$results_box_font_size.'px;
					'.( $results_box_border_radius !== "" ? '
						border-radius: '.$results_box_border_radius.';
						-moz-border-radius: '.$results_box_border_radius.';
						-webkit-border-radius: '.$results_box_border_radius.';
						' : '' ).'					
				}
				
				.'.$template_id.' .poller_master_results *{
					color: '.$results_box_font_color.';
				}
				.'.$template_id.' .poller_master_result_percentage p{
					font-size: '.$results_box_font_size.'px;
					padding-bottom: 0px;
				}
				
				.'.$template_id.' .loading{
					'.( $loader !== "" ? "background-image: url(".$this->get_image_src( $loader ).");" : "" ).'
				}
				
				.'.$template_id.' .poll_countdown .kk_number{
					font-size: '.$counter_poll_font_size.'px;
					line-height: '.$counter_poll_line_height.'px;
				}
				
				.'.$template_id.' .vote_countdown .kk_number{
					font-size: '.$counter_vote_font_size.'px;
					line-height: '.$counter_vote_line_height.'px;
				}
				
				.'.$template_id.' .poller_master_poll .progress{
					height: '.$progress_bar_height.'px;
				}
				
			</style>
		';
	}
	

	/* add shortcode for the poller_master */
	function get_template_data( $template_id ){
		$template_data = $this->default_template;
		if( !empty( $this->options['templates'][$template_id] ) ){
			$template_data = array_merge( $this->default_template,  $this->options['templates'][$template_id] );
		}
		
		return $template_data;
	}
	
	function get_cookie( $id ){
		if( !empty( $_COOKIE['poller_master_voting_'.$id] ) ){
			return $_COOKIE['poller_master_voting_'.$id];
		}
		else{
			return "";
		}
	}
	
	function poller_master_shortcode( $atts ){
		extract( shortcode_atts( array(
			'poll_id' => '',
			'extra_class' => ''
		), $atts ) );
		
		$shortcode_data = "";
		
		if( !empty( $poll_id ) ){
			$poll = $this->get_poll_by_id( $poll_id );
			if( !empty( $poll ) ){
				$is_started = $this->is_started( $poll->start_date );
				
				if( $is_started ){
					$user_log = $this->get_user_log( $poll_id );
					$can_vote = $this->can_poll_vote( $poll, $user_log );
					$can_countdown = $this->can_poll_countdown( $poll );
					$is_expire = $this->is_expire( $poll->end_date );
					$cookie = $this->get_cookie( $poll->id );
					
					$template_data = $this->get_template_data( $poll->template );
					
					
					$user_status = is_user_logged_in() === true ? "registered" : "guests";
					$message = '<div class="poller_master_message">';
					
					if( $is_expire ){
						$message .= '<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>'.$this->options['poll_expired_text'].'</div>';
					}
					else if( $poll->status ==  "0" ){
						$message .= '<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>'.$this->options['poll_closed_text'].'</div>';
					}
					else if( $poll->voters != $user_status && $poll->voters !== "all" ){					
						if( $poll->voters == "registered" ){
							$message .= '<div class="alert alert-notice">'.$this->options['registered_text'].'</div>';
						}
						else{
							$message .= '<div class="alert alert-notice">'.$this->options['guests_text'].'</div>';
						}
					}
					
					$message .= '</div>';
					
					$shortcode_data .= '<div class="row">
											<div class="col-md-12">
												<div class="poller_master_poll '.$poll->template.' '.$extra_class.'" data-poll_id="'.$poll->id.'" data-in_easing="'.$template_data['in_easing'].'" data-in_effect="'.$template_data['in_effect'].'" data-out_easing="'.$template_data['out_easing'].'" data-out_effect="'.$template_data['out_effect'].'">
													'.$message.'	
													
													'.( $can_countdown === true ? 
														'<div class="poller_master_poll_countdown" 
															data-time="'.$poll->end_date.'" 
															data-day_text="'.$this->options['day_text'].'" 
															data-days_text="'.$this->options['days_text'].'" 
															data-hours_text="'.$this->options['hours_text'].'" 
															data-minutes_text="'.$this->options['minutes_text'].'" 
															data-seconds_text="'.$this->options['seconds_text'].'"
															data-show_texts="'.$template_data['counter_poll_show_text'].'">
														</div>' 
														: 
														'' 
													).'
													<div class="poller_master_question_box">
														<div class="poller_master_results"></div>
														<div class="poller_master_question">'.apply_filters( 'the_content', html_entity_decode( $poll->question ) ).'</div>
														
														<div class="poller_master_answers">';	
													
															$answers = array_map('stripslashes_deep', unserialize( $poll->answers ));
															
															$input_type = $poll->multiple == "no" ? "radio" : "checkbox";
															$input_color_scheme = $template_data['check_radio_scheme'] !== "black" ? '-'.$template_data['check_radio_scheme'] : '';
															if( $template_data['check_radio_style'] !== "polaris" && $template_data['check_radio_style'] !== "futurico" ){
																$input_class = 'i'.$input_type.'_'.$template_data['check_radio_style'].$input_color_scheme;
															}
															else{
																$input_class = 'i'.$input_type.'_'.$template_data['check_radio_style'];
															}
															
															$shortcode_data .= '<form class="poller_master_form" data-input_class="'.$input_class.'" data-multiple="'.$poll->multiple.'" data-error_empty_text="'.$this->options['error_empty_text'].'">
																				<ul class="list-unstyled skin-'.$template_data['check_radio_style'].'">';
																				
															for( $i=0; $i<sizeof($answers); $i++ ){
																$shortcode_data .= '																
																	<li>
																		<input name="poll_'.$poll->id.'" id="poll_'.$poll->id.'_'.$i.'" value="'.$i.'" type="'.$input_type.'"/>
																		<label for="poll_'.$poll->id.'_'.$i.'">'.$answers[$i].'</label>
																	</li>
																';
															}
															$shortcode_data .= '</ul></form>
														</div>
														<div class="row">';
											
					if( $can_vote ){
						$shortcode_data .= '<div class="col-md-6"><button type="button" class="btn btn-info pull-left" id="poller_master_vote">'.$this->options['vote_text'].'</button></div>';
					}
					$results_class = 'poller_master_hidden';
					if( !empty($user_log) || $poll->zero_vote == "1" || !empty( $cookie ) ){
						$results_class = '';
					}
					$shortcode_data .= '<div class="col-md-'.( $can_vote === true ? '6' : '12' ).'"><button type="button" class="'.$results_class.' btn btn-success pull-right" id="poller_master_results">'.$this->options['results_text'].'</button></div>';
					$shortcode_data .= '<div class="clearfix"></div></div>';
					$shortcode_data .= '</div>	<!-- closed poller_master_question_box  -->';
					if( $poll->frequency > 0 && !$is_expire ){
						$shortcode_data .= $this->check_frequency( $user_log, $cookie, $poll->frequency, $poll->template );
					}
					$shortcode_data .= '</div>	<!-- closed poller_master_poll  -->
										</div>	<!-- closed col  -->
										</div>	<!-- closed row  -->';
										
					/* add style just above the html and it will not be overwritten */
					$shortcode_data = $this->create_style( $poll->template, $template_data  ) . $shortcode_data;
				}
			}
		}
	
		
		return $shortcode_data;
	}
	
	function add_to_vc(){
		$all_polls = $this->get_all_polls();
		$polls = array();
		if( !empty( $all_polls ) ){
			foreach( $all_polls as $poll ){
				$polls[$poll->name] = $poll->id;
			}
		}
		vc_map( array(
		   "name" => __( 'Poller Master', 'poller_master' ),
		   "base" => "poller_master",
		   "class" => "",
		   "icon" => 'poller_master_icon',
		   "category" => __( 'Content', 'poller_master' ),
		   "params" => array(  	  
			  array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( 'Poll', 'poller_master' ),
				"param_name" => "poll_id",
				"value" => $polls,
				"description" => __( 'Select which poll to display', 'poller_master' )
			  ),
			  array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __( 'Extra class', 'poller_master' ),
				 "param_name" => "extra_class",
				 "value" => "",
				 "description" => __( 'Poll extra class name', 'poller_master' )
			  ),  
		   )
		) );	
	}
	/* ------------------------------------------END FRONT END -----------------------------------------*/
		
	/* throw an info */
	function throw_info( $message, $type, $echo = true ){
		$class = "";
		switch( $type ){
			case 'success'  : $class = "updated"; break;
			case 'notice'	: $class = "update-nag"; break;
			case 'error'	: $class = "error"; break;
			default			: $class = "updated";
		}
		
		$message = '<div id="message" class="'.$class.'"><p>'.$message.'</p></div>';
		if( $echo === true ){
			echo $message;
		}
		else{
			return $message;
		}
	}
	
	/* ---------------------- EXPORT IMPORT ----------------------- */
	function export_options_and_polls(){
		global $wpdb;
		$export_data = array(
			'polls' => $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}poller_master_polls", ARRAY_A ),
			'logs'	=> $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}poller_master_logs", ARRAY_A ),
			'options' => get_option('poller_master_options')
		);
		
		return htmlentities( json_encode( $export_data ) );
	}
	
	function import_options_and_polls( $json_string ){
		global $wpdb;
		
		$import_data = json_decode( stripslashes( $json_string ) , true );
		/* first reset it */
		poller_master_reset_tables();
		update_option( 'poller_master_activation', '1');
		update_option( 'poller_master_version', $this->version);	
		
		$info = update_option( 'poller_master_options', $import_data['options'] );
		if( $info !== false ){
			if( !empty( $import_data['polls'] ) ){
				foreach( $import_data['polls'] as $poll ){
					$wpdb->insert(
						$this->polls_table_name,
						$poll
					);
				}
			}
			
			if( !empty( $import_data['logs'] ) ){
				foreach( $import_data['logs'] as $log ){
					$wpdb->insert(
						$this->logs_table_name,
						$log
					);
				}
			}
			
			return true;
		}
		else{
			return __( 'Could not save the options', 'poller_master' );
		}
	}
	
}

function poller_master_form_select( $source, $selected ){
	switch( $source ){
		case 'font' 	: $source = poller_master_all_google_fonts(); break;
		case 'easing'	: $source = poller_master_easings(); break;
		case 'effect'	: $source = poller_master_effects(); break;
	}
	$options = "";
	foreach( $source as $key => $value ){
		$options .= '<option value="'.$key.'" '.( $key == $selected ? 'selected="selected"' : '').'>'.$value.'</option>';
	}
	
	return $options;
}

function poller_master_effects(){
	return array(
		'none' => __( 'Default', 'poller_master' ),
		'slide' => __( 'Slide', 'poller_master'),
		'fade'	=> __( 'Fade', 'pollit_master' )
	);
}

function poller_master_easings(){
	return array(
		'easeInQuad' => 'easeInQuad',
		'easeOutQuad' => 'easeOutQuad',
		'easeInOutQuad' => 'easeInOutQuad',
		'easeInCubic' => 'easeInCubic',
		'easeOutCubic' => 'easeOutCubic',
		'easeInOutCubic' => 'easeInOutCubic',
		'easeInQuart' => 'easeInQuart',
		'easeOutQuart' => 'easeOutQuart',
		'easeInOutQuart' => 'easeInOutQuart',
		'easeInSine' => 'easeInSine',
		'easeOutSine' => 'easeOutSine',
		'easeInOutSine' => 'easeInOutSine',
		'easeInExpo' => 'easeInExpo',
		'easeOutExpo' => 'easeOutExpo',
		'easeInOutExpo' => 'easeInOutExpo',
		'easeInQuint' => 'easeInQuint',
		'easeOutQuint' => 'easeOutQuint',
		'easeInOutQuint' => 'easeInOutQuint',
		'easeInCirc' => 'easeInCirc',
		'easeOutCirc' => 'easeOutCirc',
		'easeInOutCirc' => 'easeInOutCirc',
		'easeInElastic' => 'easeInElastic',
		'easeOutElastic' => 'easeOutElastic',
		'easeInOutElastic' => 'easeInOutElastic',
		'easeInBack' => 'easeInBack',
		'easeOutBack' => 'easeOutBack',
		'easeInOutBack' => 'easeInOutBack',
		'easeInBounce' => 'easeInBounce',
		'easeOutBounce' => 'easeOutBounce',
		'easeInOutBounce' => 'easeInOutBounce',	
	);
}

function poller_master_all_google_fonts(){
	return array(
	'' => 'Inherit From Themes Style',
	'ABeeZee' => 'ABeeZee',
	'Abel' => 'Abel',
	'Abril+Fatface' => 'Abril Fatface',
	'Aclonica' => 'Aclonica',
	'Acme' => 'Acme',
	'Actor' => 'Actor',
	'Adamina' => 'Adamina',
	'Advent+Pro' => 'Advent Pro',
	'Aguafina+Script' => 'Aguafina Script',
	'Akronim' => 'Akronim',
	'Aladin' => 'Aladin',
	'Aldrich' => 'Aldrich',
	'Alef' => 'Alef',
	'Alegreya' => 'Alegreya',
	'Alegreya+SC' => 'Alegreya SC',
	'Alegreya+Sans' => 'Alegreya Sans',
	'Alegreya+Sans+SC' => 'Alegreya Sans SC',
	'Alex+Brush' => 'Alex Brush',
	'Alfa+Slab+One' => 'Alfa Slab One',
	'Alice' => 'Alice',
	'Alike' => 'Alike',
	'Alike+Angular' => 'Alike Angular',
	'Allan' => 'Allan',
	'Allerta' => 'Allerta',
	'Allerta+Stencil' => 'Allerta Stencil',
	'Allura' => 'Allura',
	'Almendra' => 'Almendra',
	'Almendra+Display' => 'Almendra Display',
	'Almendra+SC' => 'Almendra SC',
	'Amarante' => 'Amarante',
	'Amaranth' => 'Amaranth',
	'Amatic+SC' => 'Amatic SC',
	'Amethysta' => 'Amethysta',
	'Anaheim' => 'Anaheim',
	'Andada' => 'Andada',
	'Andika' => 'Andika',
	'Angkor' => 'Angkor',
	'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
	'Anonymous+Pro' => 'Anonymous Pro',
	'Antic' => 'Antic',
	'Antic+Didone' => 'Antic Didone',
	'Antic+Slab' => 'Antic Slab',
	'Anton' => 'Anton',
	'Arapey' => 'Arapey',
	'Arbutus' => 'Arbutus',
	'Arbutus+Slab' => 'Arbutus Slab',
	'Architects+Daughter' => 'Architects Daughter',
	'Archivo+Black' => 'Archivo Black',
	'Archivo+Narrow' => 'Archivo Narrow',
	'Arimo' => 'Arimo',
	'Arizonia' => 'Arizonia',
	'Armata' => 'Armata',
	'Artifika' => 'Artifika',
	'Arvo' => 'Arvo',
	'Asap' => 'Asap',
	'Asset' => 'Asset',
	'Astloch' => 'Astloch',
	'Asul' => 'Asul',
	'Atomic+Age' => 'Atomic Age',
	'Aubrey' => 'Aubrey',
	'Audiowide' => 'Audiowide',
	'Autour+One' => 'Autour One',
	'Average' => 'Average',
	'Average+Sans' => 'Average Sans',
	'Averia+Gruesa+Libre' => 'Averia Gruesa Libre',
	'Averia+Libre' => 'Averia Libre',
	'Averia+Sans+Libre' => 'Averia Sans Libre',
	'Averia+Serif+Libre' => 'Averia Serif Libre',
	'Bad+Script' => 'Bad Script',
	'Balthazar' => 'Balthazar',
	'Bangers' => 'Bangers',
	'Basic' => 'Basic',
	'Battambang' => 'Battambang',
	'Baumans' => 'Baumans',
	'Bayon' => 'Bayon',
	'Belgrano' => 'Belgrano',
	'Belleza' => 'Belleza',
	'BenchNine' => 'BenchNine',
	'Bentham' => 'Bentham',
	'Berkshire+Swash' => 'Berkshire Swash',
	'Bevan' => 'Bevan',
	'Bigelow+Rules' => 'Bigelow Rules',
	'Bigshot+One' => 'Bigshot One',
	'Bilbo' => 'Bilbo',
	'Bilbo+Swash+Caps' => 'Bilbo Swash Caps',
	'Bitter' => 'Bitter',
	'Black+Ops+One' => 'Black Ops One',
	'Bokor' => 'Bokor',
	'Bonbon' => 'Bonbon',
	'Boogaloo' => 'Boogaloo',
	'Bowlby+One' => 'Bowlby One',
	'Bowlby+One+SC' => 'Bowlby One SC',
	'Brawler' => 'Brawler',
	'Bree+Serif' => 'Bree Serif',
	'Bubblegum+Sans' => 'Bubblegum Sans',
	'Bubbler+One' => 'Bubbler One',
	'Buda' => 'Buda',
	'Buenard' => 'Buenard',
	'Butcherman' => 'Butcherman',
	'Butterfly+Kids' => 'Butterfly Kids',
	'Cabin' => 'Cabin',
	'Cabin+Condensed' => 'Cabin Condensed',
	'Cabin+Sketch' => 'Cabin Sketch',
	'Caesar+Dressing' => 'Caesar Dressing',
	'Cagliostro' => 'Cagliostro',
	'Calligraffitti' => 'Calligraffitti',
	'Cambo' => 'Cambo',
	'Candal' => 'Candal',
	'Cantarell' => 'Cantarell',
	'Cantata+One' => 'Cantata One',
	'Cantora+One' => 'Cantora One',
	'Capriola' => 'Capriola',
	'Cardo' => 'Cardo',
	'Carme' => 'Carme',
	'Carrois+Gothic' => 'Carrois Gothic',
	'Carrois+Gothic+SC' => 'Carrois Gothic SC',
	'Carter+One' => 'Carter One',
	'Caudex' => 'Caudex',
	'Cedarville+Cursive' => 'Cedarville Cursive',
	'Ceviche+One' => 'Ceviche One',
	'Changa+One' => 'Changa One',
	'Chango' => 'Chango',
	'Chau+Philomene+One' => 'Chau Philomene One',
	'Chela+One' => 'Chela One',
	'Chelsea+Market' => 'Chelsea Market',
	'Chenla' => 'Chenla',
	'Cherry+Cream+Soda' => 'Cherry Cream Soda',
	'Cherry+Swash' => 'Cherry Swash',
	'Chewy' => 'Chewy',
	'Chicle' => 'Chicle',
	'Chivo' => 'Chivo',
	'Cinzel' => 'Cinzel',
	'Cinzel+Decorative' => 'Cinzel Decorative',
	'Clicker+Script' => 'Clicker Script',
	'Coda' => 'Coda',
	'Coda+Caption' => 'Coda Caption',
	'Codystar' => 'Codystar',
	'Combo' => 'Combo',
	'Comfortaa' => 'Comfortaa',
	'Coming+Soon' => 'Coming Soon',
	'Concert+One' => 'Concert One',
	'Condiment' => 'Condiment',
	'Content' => 'Content',
	'Contrail+One' => 'Contrail One',
	'Convergence' => 'Convergence',
	'Cookie' => 'Cookie',
	'Copse' => 'Copse',
	'Corben' => 'Corben',
	'Courgette' => 'Courgette',
	'Cousine' => 'Cousine',
	'Coustard' => 'Coustard',
	'Covered+By+Your+Grace' => 'Covered By Your Grace',
	'Crafty+Girls' => 'Crafty Girls',
	'Creepster' => 'Creepster',
	'Crete+Round' => 'Crete Round',
	'Crimson+Text' => 'Crimson Text',
	'Croissant+One' => 'Croissant One',
	'Crushed' => 'Crushed',
	'Cuprum' => 'Cuprum',
	'Cutive' => 'Cutive',
	'Cutive+Mono' => 'Cutive Mono',
	'Damion' => 'Damion',
	'Dancing+Script' => 'Dancing Script',
	'Dangrek' => 'Dangrek',
	'Dawning+of+a+New+Day' => 'Dawning of a New Day',
	'Days+One' => 'Days One',
	'Delius' => 'Delius',
	'Delius+Swash+Caps' => 'Delius Swash Caps',
	'Delius+Unicase' => 'Delius Unicase',
	'Della+Respira' => 'Della Respira',
	'Denk+One' => 'Denk One',
	'Devonshire' => 'Devonshire',
	'Didact+Gothic' => 'Didact Gothic',
	'Diplomata' => 'Diplomata',
	'Diplomata+SC' => 'Diplomata SC',
	'Domine' => 'Domine',
	'Donegal+One' => 'Donegal One',
	'Doppio+One' => 'Doppio One',
	'Dorsa' => 'Dorsa',
	'Dosis' => 'Dosis',
	'Dr+Sugiyama' => 'Dr Sugiyama',
	'Droid+Sans' => 'Droid Sans',
	'Droid+Sans+Mono' => 'Droid Sans Mono',
	'Droid+Serif' => 'Droid Serif',
	'Duru+Sans' => 'Duru Sans',
	'Dynalight' => 'Dynalight',
	'EB+Garamond' => 'EB Garamond',
	'Eagle+Lake' => 'Eagle Lake',
	'Eater' => 'Eater',
	'Economica' => 'Economica',
	'Electrolize' => 'Electrolize',
	'Elsie' => 'Elsie',
	'Elsie+Swash+Caps' => 'Elsie Swash Caps',
	'Emblema+One' => 'Emblema One',
	'Emilys+Candy' => 'Emilys Candy',
	'Engagement' => 'Engagement',
	'Englebert' => 'Englebert',
	'Enriqueta' => 'Enriqueta',
	'Erica+One' => 'Erica One',
	'Esteban' => 'Esteban',
	'Euphoria+Script' => 'Euphoria Script',
	'Ewert' => 'Ewert',
	'Exo' => 'Exo',
	'Exo+2' => 'Exo 2',
	'Expletus+Sans' => 'Expletus Sans',
	'Fanwood+Text' => 'Fanwood Text',
	'Fascinate' => 'Fascinate',
	'Fascinate+Inline' => 'Fascinate Inline',
	'Faster+One' => 'Faster One',
	'Fasthand' => 'Fasthand',
	'Fauna+One' => 'Fauna One',
	'Federant' => 'Federant',
	'Federo' => 'Federo',
	'Felipa' => 'Felipa',
	'Fenix' => 'Fenix',
	'Finger+Paint' => 'Finger Paint',
	'Fjalla+One' => 'Fjalla One',
	'Fjord+One' => 'Fjord One',
	'Flamenco' => 'Flamenco',
	'Flavors' => 'Flavors',
	'Fondamento' => 'Fondamento',
	'Fontdiner+Swanky' => 'Fontdiner Swanky',
	'Forum' => 'Forum',
	'Francois+One' => 'Francois One',
	'Freckle+Face' => 'Freckle Face',
	'Fredericka+the+Great' => 'Fredericka the Great',
	'Fredoka+One' => 'Fredoka One',
	'Freehand' => 'Freehand',
	'Fresca' => 'Fresca',
	'Frijole' => 'Frijole',
	'Fruktur' => 'Fruktur',
	'Fugaz+One' => 'Fugaz One',
	'GFS+Didot' => 'GFS Didot',
	'GFS+Neohellenic' => 'GFS Neohellenic',
	'Gabriela' => 'Gabriela',
	'Gafata' => 'Gafata',
	'Galdeano' => 'Galdeano',
	'Galindo' => 'Galindo',
	'Gentium+Basic' => 'Gentium Basic',
	'Gentium+Book+Basic' => 'Gentium Book Basic',
	'Geo' => 'Geo',
	'Geostar' => 'Geostar',
	'Geostar+Fill' => 'Geostar Fill',
	'Germania+One' => 'Germania One',
	'Gilda+Display' => 'Gilda Display',
	'Give+You+Glory' => 'Give You Glory',
	'Glass+Antiqua' => 'Glass Antiqua',
	'Glegoo' => 'Glegoo',
	'Gloria+Hallelujah' => 'Gloria Hallelujah',
	'Goblin+One' => 'Goblin One',
	'Gochi+Hand' => 'Gochi Hand',
	'Gorditas' => 'Gorditas',
	'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911',
	'Graduate' => 'Graduate',
	'Grand+Hotel' => 'Grand Hotel',
	'Gravitas+One' => 'Gravitas One',
	'Great+Vibes' => 'Great Vibes',
	'Griffy' => 'Griffy',
	'Gruppo' => 'Gruppo',
	'Gudea' => 'Gudea',
	'Habibi' => 'Habibi',
	'Hammersmith+One' => 'Hammersmith One',
	'Hanalei' => 'Hanalei',
	'Hanalei+Fill' => 'Hanalei Fill',
	'Handlee' => 'Handlee',
	'Hanuman' => 'Hanuman',
	'Happy+Monkey' => 'Happy Monkey',
	'Headland+One' => 'Headland One',
	'Henny+Penny' => 'Henny Penny',
	'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff',
	'Holtwood+One+SC' => 'Holtwood One SC',
	'Homemade+Apple' => 'Homemade Apple',
	'Homenaje' => 'Homenaje',
	'IM+Fell+DW+Pica' => 'IM Fell DW Pica',
	'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC',
	'IM+Fell+Double+Pica' => 'IM Fell Double Pica',
	'IM+Fell+Double+Pica+SC' => 'IM Fell Double Pica SC',
	'IM+Fell+English' => 'IM Fell English',
	'IM+Fell+English+SC' => 'IM Fell English SC',
	'IM+Fell+French+Canon' => 'IM Fell French Canon',
	'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC',
	'IM+Fell+Great+Primer' => 'IM Fell Great Primer',
	'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC',
	'Iceberg' => 'Iceberg',
	'Iceland' => 'Iceland',
	'Imprima' => 'Imprima',
	'Inconsolata' => 'Inconsolata',
	'Inder' => 'Inder',
	'Indie+Flower' => 'Indie Flower',
	'Inika' => 'Inika',
	'Irish+Grover' => 'Irish Grover',
	'Istok+Web' => 'Istok Web',
	'Italiana' => 'Italiana',
	'Italianno' => 'Italianno',
	'Jacques+Francois' => 'Jacques Francois',
	'Jacques+Francois+Shadow' => 'Jacques Francois Shadow',
	'Jim+Nightshade' => 'Jim Nightshade',
	'Jockey+One' => 'Jockey One',
	'Jolly+Lodger' => 'Jolly Lodger',
	'Josefin+Sans' => 'Josefin Sans',
	'Josefin+Slab' => 'Josefin Slab',
	'Joti+One' => 'Joti One',
	'Judson' => 'Judson',
	'Julee' => 'Julee',
	'Julius+Sans+One' => 'Julius Sans One',
	'Junge' => 'Junge',
	'Jura' => 'Jura',
	'Just+Another+Hand' => 'Just Another Hand',
	'Just+Me+Again+Down+Here' => 'Just Me Again Down Here',
	'Kameron' => 'Kameron',
	'Kantumruy' => 'Kantumruy',
	'Karla' => 'Karla',
	'Kaushan+Script' => 'Kaushan Script',
	'Kavoon' => 'Kavoon',
	'Kdam+Thmor' => 'Kdam Thmor',
	'Keania+One' => 'Keania One',
	'Kelly+Slab' => 'Kelly Slab',
	'Kenia' => 'Kenia',
	'Khmer' => 'Khmer',
	'Kite+One' => 'Kite One',
	'Knewave' => 'Knewave',
	'Kotta+One' => 'Kotta One',
	'Koulen' => 'Koulen',
	'Kranky' => 'Kranky',
	'Kreon' => 'Kreon',
	'Kristi' => 'Kristi',
	'Krona+One' => 'Krona One',
	'La+Belle+Aurore' => 'La Belle Aurore',
	'Lancelot' => 'Lancelot',
	'Lato' => 'Lato',
	'League+Script' => 'League Script',
	'Leckerli+One' => 'Leckerli One',
	'Ledger' => 'Ledger',
	'Lekton' => 'Lekton',
	'Lemon' => 'Lemon',
	'Libre+Baskerville' => 'Libre Baskerville',
	'Life+Savers' => 'Life Savers',
	'Lilita+One' => 'Lilita One',
	'Lily+Script+One' => 'Lily Script One',
	'Limelight' => 'Limelight',
	'Linden+Hill' => 'Linden Hill',
	'Lobster' => 'Lobster',
	'Lobster+Two' => 'Lobster Two',
	'Londrina+Outline' => 'Londrina Outline',
	'Londrina+Shadow' => 'Londrina Shadow',
	'Londrina+Sketch' => 'Londrina Sketch',
	'Londrina+Solid' => 'Londrina Solid',
	'Lora' => 'Lora',
	'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister',
	'Loved+by+the+King' => 'Loved by the King',
	'Lovers+Quarrel' => 'Lovers Quarrel',
	'Luckiest+Guy' => 'Luckiest Guy',
	'Lusitana' => 'Lusitana',
	'Lustria' => 'Lustria',
	'Macondo' => 'Macondo',
	'Macondo+Swash+Caps' => 'Macondo Swash Caps',
	'Magra' => 'Magra',
	'Maiden+Orange' => 'Maiden Orange',
	'Mako' => 'Mako',
	'Marcellus' => 'Marcellus',
	'Marcellus+SC' => 'Marcellus SC',
	'Marck+Script' => 'Marck Script',
	'Margarine' => 'Margarine',
	'Marko+One' => 'Marko One',
	'Marmelad' => 'Marmelad',
	'Marvel' => 'Marvel',
	'Mate' => 'Mate',
	'Mate+SC' => 'Mate SC',
	'Maven+Pro' => 'Maven Pro',
	'McLaren' => 'McLaren',
	'Meddon' => 'Meddon',
	'MedievalSharp' => 'MedievalSharp',
	'Medula+One' => 'Medula One',
	'Megrim' => 'Megrim',
	'Meie+Script' => 'Meie Script',
	'Merienda' => 'Merienda',
	'Merienda+One' => 'Merienda One',
	'Merriweather' => 'Merriweather',
	'Merriweather+Sans' => 'Merriweather Sans',
	'Metal' => 'Metal',
	'Metal+Mania' => 'Metal Mania',
	'Metamorphous' => 'Metamorphous',
	'Metrophobic' => 'Metrophobic',
	'Michroma' => 'Michroma',
	'Milonga' => 'Milonga',
	'Miltonian' => 'Miltonian',
	'Miltonian+Tattoo' => 'Miltonian Tattoo',
	'Miniver' => 'Miniver',
	'Miss+Fajardose' => 'Miss Fajardose',
	'Modern+Antiqua' => 'Modern Antiqua',
	'Molengo' => 'Molengo',
	'Molle' => 'Molle',
	'Monda' => 'Monda',
	'Monofett' => 'Monofett',
	'Monoton' => 'Monoton',
	'Monsieur+La+Doulaise' => 'Monsieur La Doulaise',
	'Montaga' => 'Montaga',
	'Montez' => 'Montez',
	'Montserrat' => 'Montserrat',
	'Montserrat+Alternates' => 'Montserrat Alternates',
	'Montserrat+Subrayada' => 'Montserrat Subrayada',
	'Moul' => 'Moul',
	'Moulpali' => 'Moulpali',
	'Mountains+of+Christmas' => 'Mountains of Christmas',
	'Mouse+Memoirs' => 'Mouse Memoirs',
	'Mr+Bedfort' => 'Mr Bedfort',
	'Mr+Dafoe' => 'Mr Dafoe',
	'Mr+De+Haviland' => 'Mr De Haviland',
	'Mrs+Saint+Delafield' => 'Mrs Saint Delafield',
	'Mrs+Sheppards' => 'Mrs Sheppards',
	'Muli' => 'Muli',
	'Mystery+Quest' => 'Mystery Quest',
	'Neucha' => 'Neucha',
	'Neuton' => 'Neuton',
	'New+Rocker' => 'New Rocker',
	'News+Cycle' => 'News Cycle',
	'Niconne' => 'Niconne',
	'Nixie+One' => 'Nixie One',
	'Nobile' => 'Nobile',
	'Nokora' => 'Nokora',
	'Norican' => 'Norican',
	'Nosifer' => 'Nosifer',
	'Nothing+You+Could+Do' => 'Nothing You Could Do',
	'Noticia+Text' => 'Noticia Text',
	'Noto+Sans' => 'Noto Sans',
	'Noto+Serif' => 'Noto Serif',
	'Nova+Cut' => 'Nova Cut',
	'Nova+Flat' => 'Nova Flat',
	'Nova+Mono' => 'Nova Mono',
	'Nova+Oval' => 'Nova Oval',
	'Nova+Round' => 'Nova Round',
	'Nova+Script' => 'Nova Script',
	'Nova+Slim' => 'Nova Slim',
	'Nova+Square' => 'Nova Square',
	'Numans' => 'Numans',
	'Nunito' => 'Nunito',
	'Odor+Mean+Chey' => 'Odor Mean Chey',
	'Offside' => 'Offside',
	'Old+Standard+TT' => 'Old Standard TT',
	'Oldenburg' => 'Oldenburg',
	'Oleo+Script' => 'Oleo Script',
	'Oleo+Script+Swash+Caps' => 'Oleo Script Swash Caps',
	'Open+Sans' => 'Open Sans',
	'Open+Sans+Condensed' => 'Open Sans Condensed',
	'Oranienbaum' => 'Oranienbaum',
	'Orbitron' => 'Orbitron',
	'Oregano' => 'Oregano',
	'Orienta' => 'Orienta',
	'Original+Surfer' => 'Original Surfer',
	'Oswald' => 'Oswald',
	'Over+the+Rainbow' => 'Over the Rainbow',
	'Overlock' => 'Overlock',
	'Overlock+SC' => 'Overlock SC',
	'Ovo' => 'Ovo',
	'Oxygen' => 'Oxygen',
	'Oxygen+Mono' => 'Oxygen Mono',
	'PT+Mono' => 'PT Mono',
	'PT+Sans' => 'PT Sans',
	'PT+Sans+Caption' => 'PT Sans Caption',
	'PT+Sans+Narrow' => 'PT Sans Narrow',
	'PT+Serif' => 'PT Serif',
	'PT+Serif+Caption' => 'PT Serif Caption',
	'Pacifico' => 'Pacifico',
	'Paprika' => 'Paprika',
	'Parisienne' => 'Parisienne',
	'Passero+One' => 'Passero One',
	'Passion+One' => 'Passion One',
	'Pathway+Gothic+One' => 'Pathway Gothic One',
	'Patrick+Hand' => 'Patrick Hand',
	'Patrick+Hand+SC' => 'Patrick Hand SC',
	'Patua+One' => 'Patua One',
	'Paytone+One' => 'Paytone One',
	'Peralta' => 'Peralta',
	'Permanent+Marker' => 'Permanent Marker',
	'Petit+Formal+Script' => 'Petit Formal Script',
	'Petrona' => 'Petrona',
	'Philosopher' => 'Philosopher',
	'Piedra' => 'Piedra',
	'Pinyon+Script' => 'Pinyon Script',
	'Pirata+One' => 'Pirata One',
	'Plaster' => 'Plaster',
	'Play' => 'Play',
	'Playball' => 'Playball',
	'Playfair+Display' => 'Playfair Display',
	'Playfair+Display+SC' => 'Playfair Display SC',
	'Podkova' => 'Podkova',
	'Poiret+One' => 'Poiret One',
	'Poller+One' => 'Poller One',
	'Poly' => 'Poly',
	'Pompiere' => 'Pompiere',
	'Pontano+Sans' => 'Pontano Sans',
	'Port+Lligat+Sans' => 'Port Lligat Sans',
	'Port+Lligat+Slab' => 'Port Lligat Slab',
	'Prata' => 'Prata',
	'Preahvihear' => 'Preahvihear',
	'Press+Start+2P' => 'Press Start 2P',
	'Princess+Sofia' => 'Princess Sofia',
	'Prociono' => 'Prociono',
	'Prosto+One' => 'Prosto One',
	'Puritan' => 'Puritan',
	'Purple+Purse' => 'Purple Purse',
	'Quando' => 'Quando',
	'Quantico' => 'Quantico',
	'Quattrocento' => 'Quattrocento',
	'Quattrocento+Sans' => 'Quattrocento Sans',
	'Questrial' => 'Questrial',
	'Quicksand' => 'Quicksand',
	'Quintessential' => 'Quintessential',
	'Qwigley' => 'Qwigley',
	'Racing+Sans+One' => 'Racing Sans One',
	'Radley' => 'Radley',
	'Raleway' => 'Raleway',
	'Raleway+Dots' => 'Raleway Dots',
	'Rambla' => 'Rambla',
	'Rammetto+One' => 'Rammetto One',
	'Ranchers' => 'Ranchers',
	'Rancho' => 'Rancho',
	'Rationale' => 'Rationale',
	'Redressed' => 'Redressed',
	'Reenie+Beanie' => 'Reenie Beanie',
	'Revalia' => 'Revalia',
	'Ribeye' => 'Ribeye',
	'Ribeye+Marrow' => 'Ribeye Marrow',
	'Righteous' => 'Righteous',
	'Risque' => 'Risque',
	'Roboto' => 'Roboto',
	'Roboto+Condensed' => 'Roboto Condensed',
	'Roboto+Slab' => 'Roboto Slab',
	'Rochester' => 'Rochester',
	'Rock+Salt' => 'Rock Salt',
	'Rokkitt' => 'Rokkitt',
	'Romanesco' => 'Romanesco',
	'Ropa+Sans' => 'Ropa Sans',
	'Rosario' => 'Rosario',
	'Rosarivo' => 'Rosarivo',
	'Rouge+Script' => 'Rouge Script',
	'Ruda' => 'Ruda',
	'Rufina' => 'Rufina',
	'Ruge+Boogie' => 'Ruge Boogie',
	'Ruluko' => 'Ruluko',
	'Rum+Raisin' => 'Rum Raisin',
	'Ruslan+Display' => 'Ruslan Display',
	'Russo+One' => 'Russo One',
	'Ruthie' => 'Ruthie',
	'Rye' => 'Rye',
	'Sacramento' => 'Sacramento',
	'Sail' => 'Sail',
	'Salsa' => 'Salsa',
	'Sanchez' => 'Sanchez',
	'Sancreek' => 'Sancreek',
	'Sansita+One' => 'Sansita One',
	'Sarina' => 'Sarina',
	'Satisfy' => 'Satisfy',
	'Scada' => 'Scada',
	'Schoolbell' => 'Schoolbell',
	'Seaweed+Script' => 'Seaweed Script',
	'Sevillana' => 'Sevillana',
	'Seymour+One' => 'Seymour One',
	'Shadows+Into+Light' => 'Shadows Into Light',
	'Shadows+Into+Light+Two' => 'Shadows Into Light Two',
	'Shanti' => 'Shanti',
	'Share' => 'Share',
	'Share+Tech' => 'Share Tech',
	'Share+Tech+Mono' => 'Share Tech Mono',
	'Shojumaru' => 'Shojumaru',
	'Short+Stack' => 'Short Stack',
	'Siemreap' => 'Siemreap',
	'Sigmar+One' => 'Sigmar One',
	'Signika' => 'Signika',
	'Signika+Negative' => 'Signika Negative',
	'Simonetta' => 'Simonetta',
	'Sintony' => 'Sintony',
	'Sirin+Stencil' => 'Sirin Stencil',
	'Six+Caps' => 'Six Caps',
	'Skranji' => 'Skranji',
	'Slackey' => 'Slackey',
	'Smokum' => 'Smokum',
	'Smythe' => 'Smythe',
	'Sniglet' => 'Sniglet',
	'Snippet' => 'Snippet',
	'Snowburst+One' => 'Snowburst One',
	'Sofadi+One' => 'Sofadi One',
	'Sofia' => 'Sofia',
	'Sonsie+One' => 'Sonsie One',
	'Sorts+Mill+Goudy' => 'Sorts Mill Goudy',
	'Source+Code+Pro' => 'Source Code Pro',
	'Source+Sans+Pro' => 'Source Sans Pro',
	'Special+Elite' => 'Special Elite',
	'Spicy+Rice' => 'Spicy Rice',
	'Spinnaker' => 'Spinnaker',
	'Spirax' => 'Spirax',
	'Squada+One' => 'Squada One',
	'Stalemate' => 'Stalemate',
	'Stalinist+One' => 'Stalinist One',
	'Stardos+Stencil' => 'Stardos Stencil',
	'Stint+Ultra+Condensed' => 'Stint Ultra Condensed',
	'Stint+Ultra+Expanded' => 'Stint Ultra Expanded',
	'Stoke' => 'Stoke',
	'Strait' => 'Strait',
	'Sue+Ellen+Francisco' => 'Sue Ellen Francisco',
	'Sunshiney' => 'Sunshiney',
	'Supermercado+One' => 'Supermercado One',
	'Suwannaphum' => 'Suwannaphum',
	'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',
	'Syncopate' => 'Syncopate',
	'Tangerine' => 'Tangerine',
	'Taprom' => 'Taprom',
	'Tauri' => 'Tauri',
	'Telex' => 'Telex',
	'Tenor+Sans' => 'Tenor Sans',
	'Text+Me+One' => 'Text Me One',
	'The+Girl+Next+Door' => 'The Girl Next Door',
	'Tienne' => 'Tienne',
	'Tinos' => 'Tinos',
	'Titan+One' => 'Titan One',
	'Titillium+Web' => 'Titillium Web',
	'Trade+Winds' => 'Trade Winds',
	'Trocchi' => 'Trocchi',
	'Trochut' => 'Trochut',
	'Trykker' => 'Trykker',
	'Tulpen+One' => 'Tulpen One',
	'Ubuntu' => 'Ubuntu',
	'Ubuntu+Condensed' => 'Ubuntu Condensed',
	'Ubuntu+Mono' => 'Ubuntu Mono',
	'Ultra' => 'Ultra',
	'Uncial+Antiqua' => 'Uncial Antiqua',
	'Underdog' => 'Underdog',
	'Unica+One' => 'Unica One',
	'UnifrakturCook' => 'UnifrakturCook',
	'UnifrakturMaguntia' => 'UnifrakturMaguntia',
	'Unkempt' => 'Unkempt',
	'Unlock' => 'Unlock',
	'Unna' => 'Unna',
	'VT323' => 'VT323',
	'Vampiro+One' => 'Vampiro One',
	'Varela' => 'Varela',
	'Varela+Round' => 'Varela Round',
	'Vast+Shadow' => 'Vast Shadow',
	'Vibur' => 'Vibur',
	'Vidaloka' => 'Vidaloka',
	'Viga' => 'Viga',
	'Voces' => 'Voces',
	'Volkhov' => 'Volkhov',
	'Vollkorn' => 'Vollkorn',
	'Voltaire' => 'Voltaire',
	'Waiting+for+the+Sunrise' => 'Waiting for the Sunrise',
	'Wallpoet' => 'Wallpoet',
	'Walter+Turncoat' => 'Walter Turncoat',
	'Warnes' => 'Warnes',
	'Wellfleet' => 'Wellfleet',
	'Wendy+One' => 'Wendy One',
	'Wire+One' => 'Wire One',
	'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz',
	'Yellowtail' => 'Yellowtail',
	'Yeseva+One' => 'Yeseva One',
	'Yesteryear' => 'Yesteryear',
	'Zeyada' => 'Zeyada');
}

$poll = new Poll_It();
/* global function for usage in theme files */
function pm_poll( $poll_id, $extra_class = "" ){
	return $poll->poller_master_shortcode( array(
		"poll_id" => $poll_id,
		"extra_class" => $extra_class
	) );
}

/* use this function when uninstalling the plugin */
function poller_master_delete_options(){
	$poller_master_options = array(
		"poller_master_activation",
		"poller_master_version",
		"poller_master_options"
	);
	foreach( $poller_master_options as $option ){
		delete_option( $option );
	}
}

function poller_master_reset_tables(){
	global $wpdb;
	poller_master_delete_options();
	/* clean the data */
	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}poller_master_logs" );
	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}poller_master_polls" );	
}

function poller_master_uninstall(){
	global $wpdb;
	poller_master_delete_options();
	/* delete tables */
	$wpdb->query( "DROP TABLE {$wpdb->prefix}poller_master_logs" );
	$wpdb->query( "DROP TABLE {$wpdb->prefix}poller_master_polls" );
}
register_uninstall_hook(__FILE__, 'poller_master_uninstall' );

?>