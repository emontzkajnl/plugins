<?php
namespace sgpb;
use \SGPBConfigDataHelper;

class Ajax
{
	private $postData;

	public function __construct()
	{
		$this->actions();
	}

	public function setPostData($postData)
	{
		$this->postData = $postData;
	}

	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * Return ajax param form post data by key
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 *
	 * @return string $value
	 */
	public function getValueFromPost($key)
	{
		$postData = $this->getPostData();
		$value = '';

		if(!empty($postData[$key])) {
			$value = $postData[$key];
		}

		return $value;
	}

	public function actions()
	{
		add_action('wp_ajax_sgpb_send_to_open_counter', array($this, 'addToCounter'));
		add_action('wp_ajax_nopriv_sgpb_send_to_open_counter', array($this, 'addToCounter'));

		add_action('wp_ajax_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));
		add_action('wp_ajax_nopriv_sgpb_process_after_submission', array($this, 'sgpbSubsciptionFormSubmittedAction'));

		add_action('wp_ajax_sgpb_subscription_submission', array($this, 'subscriptionSubmission'));
		add_action('wp_ajax_nopriv_sgpb_subscription_submission', array($this, 'subscriptionSubmission'));

		$allowToAction = AdminHelper::userCanAccessTo();

		if($allowToAction) {
			add_action('wp_ajax_add_condition_group_row', array($this, 'addConditionGroupRow'));
			add_action('wp_ajax_add_condition_rule_row', array($this, 'addConditionRuleRow'));
			add_action('wp_ajax_change_condition_rule_row', array($this, 'changeConditionRuleRow'));
			add_action('wp_ajax_select2_search_data', array($this, 'select2SearchData'));
			add_action('wp_ajax_change_popup_status', array($this, 'changePopupStatus'));
			// proStartGold
			add_action('wp_ajax_check_same_origin', array($this, 'checkSameOrigin'));
			// proEndGold
			add_action('wp_ajax_sgpb_subscribers_delete', array($this, 'deleteSubscribers'));
			add_action('wp_ajax_sgpb_add_subscribers', array($this, 'addSubscribers'));
			add_action('wp_ajax_sgpb_import_subscribers', array($this, 'importSubscribers'));
			add_action('wp_ajax_sgpb_import_settings', array($this, 'importSettings'));
			add_action('wp_ajax_sgpb_save_imported_subscribers', array($this, 'saveImportedSubscribers'));
			add_action('wp_ajax_sgpb_send_newsletter', array($this, 'sendNewsletter'));
			add_action('wp_ajax_sgpb_change_review_popup_show_period', array($this, 'changeReviewPopupPeriod'));
			add_action('wp_ajax_sgpb_dont_show_review_popup', array($this, 'dontShowReviewPopup'));
			add_action('wp_ajax_sgpb_close_banner', array($this, 'closeMainRateUsBanner'));
			add_action('wp_ajax_sgpb_close_license_notice', array($this, 'closeLicenseNoticeBanner'));
			add_action('wp_ajax_sgpb_hide_ask_review_popup', array($this, 'dontShowAskReviewBanner'));
			add_action('wp_ajax_sgpb_reset_popup_opening_count', array($this, 'resetPopupOpeningCount'));
			/*Extension notification panel*/
			add_action('wp_ajax_sgpb_dont_show_extension_panel', array($this, 'extensionNotificationPanel'));
			add_action('wp_ajax_sgpb_dont_show_problem_alert', array($this, 'dontShowProblemAlert'));
			// autosave
			add_action('wp_ajax_sgpb_autosave', array($this, 'sgpbAutosave'));

			add_action('wp_ajax_sgpb_set_upload_dir', array($this, 'setUploadDir'));

			add_action('wp_ajax_sgpb_reset_upload_dir', array($this, 'resetUploadDir'));
		}
	}

	public function setUploadDir()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 			
		
		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to do this action!', 'popup-builder'));
			}
		}

		//get current user id
		if( is_user_logged_in() ) {
  			$current_user = get_current_user_id();
  			//update current user meta
  			update_user_meta( $current_user , 'sgpb_save_subcribers_custom' , 1);
 		}
	}
	public function resetUploadDir()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 			
		
		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to do this action!', 'popup-builder'));
			}
		}
		//get current user id
		if( is_user_logged_in() ) {
  			$current_user = get_current_user_id();
  			//update current user meta
  			delete_user_meta( $current_user, 'sgpb_save_subcribers_custom' );
 		}
	}
	public function sgpbAutosave()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 			
		
		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to do this action!', 'popup-builder'));
			}
		}
		
		if (!isset($_POST['post_ID'])){
			wp_die(0);
		}
		$popupId = (int)sanitize_text_field( wp_unslash( $_POST['post_ID'] ) );
		$postStatus = get_post_status($popupId);
		if($postStatus == 'publish') {
			wp_die('');
		}

		if(!isset($_POST['allPopupData'])) {
			wp_die(true);
		}
		// we will use array_walk_recursive method for sanitizing current data because we can receive an multidimensional array!
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$allPopupData = $_POST['allPopupData'];
		array_walk_recursive($allPopupData, function(&$item){
			$item = sanitize_text_field( wp_unslash( $item ) );
		});
		
		$popupData = SGPopup::parsePopupDataFromData($allPopupData);
		do_action('save_post_popupbuilder');
		
		$popupType = $popupData['sgpb-type'];
		$popupClassName = SGPopup::getPopupClassNameFormType($popupType);
		$popupClassPath = SGPopup::getPopupTypeClassPath($popupType);		
		
		if(file_exists($popupClassPath.$popupClassName.'.php')) {
			require_once($popupClassPath.$popupClassName.'.php');
			$popupClassName = __NAMESPACE__.'\\'.$popupClassName;
			$popupClassName::create($popupData, '_preview', 1);
		}

		wp_die();
	}

	public function dontShowReviewPopup()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('SGPBCloseReviewPopup-notification', true);
		do_action('sgpbGetNotifications');
		wp_die();
	}

	public function changeReviewPopupPeriod()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}

		$messageType = isset($_POST['messageType']) ? sanitize_text_field( wp_unslash( $_POST['messageType'] ) ) : '';

		if($messageType == 'count') {
			$maxPopupCount = get_option('SGPBMaxOpenCount');
			if(!$maxPopupCount) {
				$maxPopupCount = SGPB_ASK_REVIEW_POPUP_COUNT;
			}
			$maxPopupData = AdminHelper::getMaxOpenPopupId();
			if(!empty($maxPopupData['maxCount'])) {
				$maxPopupCount = $maxPopupData['maxCount'];
			}

			$maxPopupCount += SGPB_ASK_REVIEW_POPUP_COUNT;
			update_option('SGPBMaxOpenCount', $maxPopupCount);
			wp_die();
		}

		$popupTimeZone = get_option('timezone_string');
		if(!$popupTimeZone) {
			$popupTimeZone = SG_POPUP_DEFAULT_TIME_ZONE;
		}
		$timeDate = new \DateTime('now', new \DateTimeZone($popupTimeZone));
		$timeDate->modify('+'.SGPB_REVIEW_POPUP_PERIOD.' day');

		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
		update_option('SGPBOpenNextTime', $timeNow);
		$usageDays = get_option('SGPBUsageDays');
		$usageDays += SGPB_REVIEW_POPUP_PERIOD;
		update_option('SGPBUsageDays', $usageDays);
		wp_die();
	}

	public function resetPopupOpeningCount()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		
		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		if (!isset($_POST['popupId'])){
			wp_die(0);
		}
		global $wpdb;

		$tableName = $wpdb->prefix.'sgpb_analytics';
		$popupId = (int)sanitize_text_field( wp_unslash( $_POST['popupId'] ) );
		$allPopupsCount = get_option('SgpbCounter');
		if($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
			SGPopup::deleteAnalyticsDataByPopupId($popupId);
		}
		if(empty($allPopupsCount)) {
			// TODO ASAP remove echo use only wp_die
			echo esc_html(SGPB_AJAX_STATUS_FALSE);
			wp_die();
		}
		if(isset($allPopupsCount[$popupId])) {
			$allPopupsCount[$popupId] = 0;
		}

		$popupAnalyticsData = $wpdb->get_var( $wpdb->prepare(' DELETE FROM '.$wpdb->prefix.'sgpb_analytics WHERE target_id = %d AND event_id NOT IN (7, 12, 13)', $popupId));

		update_option('SgpbCounter', $allPopupsCount);

	}

	public function dontShowAskReviewBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('sgpbDontShowAskReviewBanner', 1);
		echo esc_html(SGPB_AJAX_STATUS_TRUE);
		wp_die();
	}

	public function dontShowProblemAlert()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('sgpb_alert_problems', 1);
		echo esc_html(SGPB_AJAX_STATUS_TRUE);
		wp_die();
	}

	public function extensionNotificationPanel()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('sgpb_extensions_updated', 1);
		echo esc_html(SGPB_AJAX_STATUS_TRUE);
		wp_die();
	}

	public function closeMainRateUsBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('sgpb-hide-support-banner', 1);
		do_action('sgpbGetNotifications');
		wp_die();
	}

	public function closeLicenseNoticeBanner()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		update_option('sgpb-hide-license-notice-banner', 1);
		wp_die();
	}

	public function addToCounter()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		
		if(isset($_GET['sg_popup_preview_id']) && !isset($_POST['params'])) {
			wp_die(0);
		}
		// we will use array_walk_recursive method for sanitizing current data because we can receive an multidimensional array!
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$popupParams = $_POST['params'];
		/* Sanitizing multidimensional array */
		array_walk_recursive($popupParams, function(&$item){
			$item = sanitize_text_field( wp_unslash( $item ) );
		});

		$popupsIdCollection = is_array($popupParams['popupsIdCollection']) ? $popupParams['popupsIdCollection'] : array();
		$popupsCounterData = get_option('SgpbCounter');

		if($popupsCounterData === false) {
			$popupsCounterData = array();
		}

		foreach($popupsIdCollection as $popupId => $popupCount) {
			if(empty($popupsCounterData[$popupId])) {
				$popupsCounterData[$popupId] = 0;
			}
			$popupsCounterData[$popupId] += $popupCount;
		}

		update_option('SgpbCounter', $popupsCounterData);
		wp_die(1);
	}

	public function deleteSubscribers()
	{
		global $wpdb;

		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		if (empty($_POST['subscribersId'])){
			wp_die();
		}
		$subscribersId = array_map('sanitize_text_field', wp_unslash( $_POST['subscribersId'] ) );
		$number_deletedSubscribers = 0 ;	
		foreach($subscribersId as $subscriberId) {
			$table_sgpb_subscribers = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
			$wpdb->query( $wpdb->prepare("DELETE FROM $table_sgpb_subscribers WHERE id = %d", $subscriberId) );
			$number_deletedSubscribers++;
		}
		// translators: %d is the number of subscribers deleted.
		$notification_deletedSubscribers = sprintf( __('You have deleted %d subscribers successfully!', 'popup-builder'), $number_deletedSubscribers );
		set_transient('sgpbImportSubscribersMessaage', $notification_deletedSubscribers , 3600);
	}

	public function addSubscribers()
	{
		global $wpdb;

		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		$status = SGPB_AJAX_STATUS_FALSE;
		$firstName = isset($_POST['firstName']) ? sanitize_text_field( wp_unslash( $_POST['firstName'] ) ) : '';
		$lastName = isset($_POST['lastName']) ? sanitize_text_field( wp_unslash( $_POST['lastName'] ) ): '';
		$email = isset($_POST['email']) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$date = gmdate('Y-m-d');

		// we will use array_walk_recursive method for sanitizing current data because we can receive an multidimensional array!
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$subscriptionPopupsId = !empty($_POST['popups']) ? $_POST['popups'] : [];
		array_walk_recursive($subscriptionPopupsId, function(&$item){
			$item = sanitize_text_field( wp_unslash( $item ) );
		});
		$table_sgpb_subscribers = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
		$popupPostIds = '';
		$popupPostTitle = '';
		foreach($subscriptionPopupsId as $subscriptionPopupId) {			
			
			$res = $wpdb->get_row( $wpdb->prepare("SELECT id FROM $table_sgpb_subscribers WHERE email = %s AND subscriptionType = %d", $email, $subscriptionPopupId), ARRAY_A);
			// add new subscriber
			if(empty($res)) {
				$res = $wpdb->query( $wpdb->prepare("INSERT INTO $table_sgpb_subscribers (firstName, lastName, email, cDate, subscriptionType) VALUES (%s, %s, %s, %s, %d) ", $firstName, $lastName, $email, $date, $subscriptionPopupId) );
			} // edit existing
			else {
				$wpdb->query( $wpdb->prepare("UPDATE $table_sgpb_subscribers SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d, unsubscribered = 0 WHERE id = %d", $firstName, $lastName, $email, $date, $subscriptionPopupId, $res['id']) );
				$res = 1;
			}
			$popupPostIds .= $subscriptionPopupId.' ';
			$popup = get_post($subscriptionPopupId);	
			if (isset($popup) && is_object( $popup ) ) {
				$popup_title = isset( $popup->post_title ) ? $popup->post_title : $subscriptionPopupId; 
				$popupPostTitle .= '`'.$popup_title.'` ';
			}
			
			if($res) {
				$status = SGPB_AJAX_STATUS_TRUE;
			}
		}
		// translators: %s is the title of Popup.
		$notification_importartSubscriber = sprintf( __('You have imported new subscriber to the %s successfully!', 'popup-builder'), $popupPostIds); 
		if ( !empty( $popupPostTitle ) ) {	
			// translators: %s is the title of Popup.			
			$notification_importartSubscriber = sprintf( __('You have imported new subscriber to the %s popup(s) successfully!', 'popup-builder'), $popupPostTitle ); 
		}
		set_transient('sgpbImportSubscribersMessaage', $notification_importartSubscriber , 3600);
		echo esc_html($status);
		wp_die();
	}

	public function importSubscribers()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		$formId = isset($_POST['popupSubscriptionList']) ? (int)sanitize_text_field( wp_unslash( $_POST['popupSubscriptionList'] ) ) : '';
		$fileURL = isset($_POST['importListURL']) ? sanitize_text_field( wp_unslash( $_POST['importListURL'] ) ) : '';
		$fileURLID = isset($_POST['importListID']) ? sanitize_text_field( wp_unslash( $_POST['importListID'] ) ) : '';
		ob_start();
		require_once SG_POPUP_VIEWS_PATH.'importConfigView.php';
		$content = ob_get_contents();
		ob_end_clean();

		echo wp_kses($content, AdminHelper::allowed_html_tags());
		wp_die();
	}

	public function importSettings()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		ob_start();
		require_once SG_POPUP_VIEWS_PATH.'importPopupsView.php';
		$content = ob_get_contents();
		ob_end_clean();

		echo wp_kses($content, AdminHelper::allowed_html_tags());
		wp_die();
	}

	public function saveImportedSubscribers()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		/**
		 * We only allow administrator or roles allowed in setting to do this action
		*/ 		

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		
		$formId = isset($_POST['popupSubscriptionList']) ? (int)sanitize_text_field( wp_unslash( $_POST['popupSubscriptionList'] ) ) : '';
		$fileURL = isset($_POST['importListURL']) ? sanitize_text_field( wp_unslash( $_POST['importListURL'] ) ) : '';
		$fileURLID = isset($_POST['importListID']) ? sanitize_text_field( wp_unslash( $_POST['importListID'] ) ) : '';
		// we will use array_walk_recursive method for sanitizing current data because we can receive an multidimensional array!
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$mapping = !empty($_POST['namesMapping']) ? $_POST['namesMapping'] : [];
		array_walk_recursive($mapping, function(&$item){
			//slashed before sanitization. Use wp_unslash()
			$item = sanitize_text_field( wp_unslash( $item ) );
		});
		
		$fileImportPath = get_attached_file( $fileURLID );	

		$fileContent = AdminHelper::sgpbCustomReadfile($fileImportPath);
		//Decrypt the data when reading it back from the CSV
		$fileContent = AdminHelper::decrypt_data( $fileContent );
		if( $fileContent == false )
		{
			//try old method of read csv data 
			$fileContent = AdminHelper::sgpbCustomReadfile($fileImportPath);
		}

		$csvFileArray = array_map('str_getcsv', explode("\n", $fileContent));


		$header = $csvFileArray[0];
		unset($csvFileArray[0]);
		if( isset($csvFileArray[count($csvFileArray)]) && count( $csvFileArray[count($csvFileArray)]) < 2 )
		{
			unset($csvFileArray[count($csvFileArray)]);
		}
		$subscriptionPlusContent = apply_filters('sgpbImportToSubscriptionList', $csvFileArray, $mapping, $formId);

		// -1 it's mean saved from Subscription Plus
		if($subscriptionPlusContent != -1) {
			global $wpdb;
			$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;	
			$column_name = "submittedData"; 
			$check_column = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `$subscribersTableName` LIKE %s", $column_name ) );	
			$number_importartSubscribers = 0 ;	
			$num_original_importrs = 0;	
			foreach($csvFileArray as $csvData) {				
				$date = gmdate('Y-m-d', time());
				if(!empty($mapping['date'])) {
					$date = $csvData[$mapping['date']];
					$date = gmdate('Y-m-d', strtotime($date));
				}

				$sgpb_check_existed = $wpdb->get_row( $wpdb->prepare("SELECT id FROM $subscribersTableName WHERE email = %s AND subscriptionType = %d", $csvData[$mapping['email']], $formId), ARRAY_A);

				$valid_firstname = isset( $csvData[$mapping['firstName']] ) ?  $csvData[$mapping['firstName']] : '';
				$valid_lastname = isset( $csvData[$mapping['lastName']] ) ?  $csvData[$mapping['lastName']] : '';
				$num_original_importrs++;				
				// add new subscriber
				if(empty($sgpb_check_existed)) {
					if( empty( $check_column ) ) {
						$wpdb->query( $wpdb->prepare("INSERT INTO $subscribersTableName (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed) VALUES (%s, %s, %s, %s, %d, %d, %d) ", $valid_firstname, $valid_lastname, $csvData[$mapping['email']], $date, $formId, 0, 0) );
					} else {
						$wpdb->query( $wpdb->prepare("INSERT INTO $subscribersTableName (firstName, lastName, email, cDate, subscriptionType, status, unsubscribed, submittedData) VALUES (%s, %s, %s, %s, %d, %d, %d, %s) ", $valid_firstname, $valid_lastname, $csvData[$mapping['email']], $date, $formId, 0, 0, '') );
					}
					$number_importartSubscribers++;	
				} 		
			}
			// translators: %d the number of imported subscribers, %s is the title of Popup.
			$notification_importartSubscribers = sprintf( __('You have imported %1$d subscribers to the `%2$s` successfully!', 'popup-builder'), $number_importartSubscribers, $formId); 
			if ( $formId ) {				
				$popup = get_post($formId);	
				if (isset($popup) && is_object( $popup ) ) {
					$popup_title = isset( $popup->post_title ) ? $popup->post_title : $formId; 
					// translators: %d the number of imported subscribers, %s is the title of Popup.
					$notification_importartSubscribers = sprintf( __('You have imported %1$d subscribers to the `%2$s`  popup successfully!', 'popup-builder'), $number_importartSubscribers, $popup_title); 
				}
				if( $num_original_importrs > $number_importartSubscribers) {
					// translators: %d the number of imported subscribers.
					$notification_importartSubscribers .= sprintf( __(' There are %d existing subscribers.', 'popup-builder'), ( $num_original_importrs - $number_importartSubscribers)); 
				}
				set_transient('sgpbImportSubscribersMessaage', $notification_importartSubscribers , 3600);
			}			
		}
		//Fix the vulnerable to Sensitive Information Exposure
		// Get the attachment ID from the URL.
		$csv_attachment_id = attachment_url_to_postid( $fileURL );
		// Check if an attachment ID was found.
		if ($csv_attachment_id) {
			// Check if the attachment exists.
			if (get_post_type($csv_attachment_id) === 'attachment') {
				// Delete the attachment and the file.
				wp_delete_attachment($csv_attachment_id, true);	
			}
		}


		echo esc_html(SGPB_AJAX_STATUS_TRUE);
		wp_die();
	}

	public function sendNewsletter()
	{
		
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		
		global $wpdb;

		// we will use array_walk_recursive method for sanitizing current data because we can receive an multidimensional array!
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$newsletterData = isset($_POST['newsletterData']) ? stripslashes_deep($_POST['newsletterData']) : [];
		array_walk_recursive($newsletterData, function(&$item, $k){
			if ($k === 'messageBody'){
				$item = wp_kses($item, AdminHelper::allowed_html_tags());
			} else {
				$item = sanitize_text_field($item);
			}
		});
		if(isset($newsletterData['testSendingStatus']) && $newsletterData['testSendingStatus'] == 'test') {
			AdminHelper::sendTestNewsletter($newsletterData);
		}
		$subscriptionFormId = (int)$newsletterData['subscriptionFormId'];
		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;		
		$wpdb->query( $wpdb->prepare("UPDATE $subscribersTableName SET status = 0 WHERE subscriptionType = %d", $subscriptionFormId) );
		$newsletterData['blogname'] = get_bloginfo('name');
		$newsletterData['username'] = wp_get_current_user()->user_login;
		update_option('SGPB_NEWSLETTER_DATA', $newsletterData);

		wp_schedule_event(time(), 'sgpb_newsletter_send_every_minute', 'sgpb_send_newsletter');
		wp_die();
	}

	// proStartGold
	public function checkSameOrigin()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}	
		$url = isset($_POST['iframeUrl']) ? esc_url_raw(  wp_unslash( $_POST['iframeUrl'] ) ) : '';
		$status = SGPB_AJAX_STATUS_FALSE;

		$remoteGet = wp_remote_get($url);

		if(is_array($remoteGet) && !empty($remoteGet['headers']['x-frame-options'])) {
			$siteUrl = isset($_POST['siteUrl']) ? esc_url_raw( wp_unslash( $_POST['siteUrl'] ) ) : '';
			$xFrameOptions = $remoteGet['headers']['x-frame-options'];
			$mayNotShow = false;

			if($xFrameOptions == 'deny') {
				$mayNotShow = true;
			} else if($xFrameOptions == 'SAMEORIGIN') {
				if(strpos($url, $siteUrl) === false) {
					$mayNotShow = true;
				}
			} else {
				if(strpos($xFrameOptions, $siteUrl) === false) {
					$mayNotShow = true;;
				}
			}

			if($mayNotShow) {
				echo esc_html($status);
				wp_die();
			}
		}

		// $remoteGet['response']['code'] < 400 it's mean correct status
		if(is_array($remoteGet) && isset($remoteGet['response']['code']) && $remoteGet['response']['code'] < 400) {
			$status = SGPB_AJAX_STATUS_TRUE;
		}

		echo esc_html($status);
		wp_die();
	}

	// proEndGold

	public function changePopupStatus()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'ajaxNonce');
		if (!isset($_POST['popupId'])){
			wp_die(esc_html(SGPB_AJAX_STATUS_FALSE));
		}
		$popupId = (int)sanitize_text_field( wp_unslash( $_POST['popupId'] ) );
		$obj = SGPopup::find($popupId);
		$isDraft = '';
		$postStatus = get_post_status($popupId);
		if($postStatus == 'draft') {
			$isDraft = '_preview';
		}

		if(!$obj || !is_object($obj)) {
			wp_die(esc_html(SGPB_AJAX_STATUS_FALSE));
		}
		$options = $obj->getOptions();
		$options['sgpb-is-active'] = isset($_POST['popupStatus'])? sanitize_text_field( wp_unslash( $_POST['popupStatus'] ) ) : '';

		if( isset( $options['sgpb-conditions'] ) ){
			unset( $options['sgpb-conditions'] );
		}
		update_post_meta($popupId, 'sg_popup_options'.$isDraft, $options);

		wp_die(esc_html($popupId));
	}

	public function subscriptionSubmission()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$submissionData = isset($_POST['formData']) ? $_POST['formData'] : "[]";
		parse_str($submissionData, $formData);
		array_walk_recursive($formData, function(&$item){
			//slashed before sanitization. Use wp_unslash()
			$item = sanitize_text_field( wp_unslash( $item ) );
		});
		$popupPostId = isset($_POST['popupPostId']) ? (int)sanitize_text_field( wp_unslash( $_POST['popupPostId'] ) ) : '';

		if(empty($formData)) {
			echo esc_html( SGPB_AJAX_STATUS_FALSE );
			wp_die();
		}

		$hiddenChecker = sanitize_text_field($formData['sgpb-subs-hidden-checker']);

		// this check is made to protect ourselves from bot
		if(!empty($hiddenChecker)) {
			echo 'Bot';
			wp_die();
		}
		global $wpdb;

		$status = SGPB_AJAX_STATUS_FALSE;
		$date = gmdate('Y-m-d');
		$email = sanitize_email($formData['sgpb-subs-email']);
		$firstName = sanitize_text_field($formData['sgpb-subs-first-name']);
		$lastName = sanitize_text_field($formData['sgpb-subs-last-name']);

		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;
		$list = $wpdb->get_row( $wpdb->prepare("SELECT id FROM $subscribersTableName WHERE email = %s AND subscriptionType = %d", $email, $popupPostId), ARRAY_A);

		// When subscriber does not exist we insert to subscribers table otherwise we update user info
		if(empty($list['id'])) {
			$res = $wpdb->query( $wpdb->prepare("INSERT INTO $subscribersTableName (firstName, lastName, email, cDate, subscriptionType) VALUES (%s, %s, %s, %s, %d) ", $firstName, $lastName, $email, $date, $popupPostId) );
		} else {
			$wpdb->query( $wpdb->prepare("UPDATE $subscribersTableName SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d WHERE id = %d", $firstName, $lastName, $email, $date, $popupPostId, $list['id']) );
			$res = 1;
		}
		if($res) {
			$status = SGPB_AJAX_STATUS_TRUE;
		}
		
		echo esc_html( $status );
		wp_die();
	}

	public function sgpbSubsciptionFormSubmittedAction()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$submissionData = isset($_POST['formData']) ? $_POST['formData'] : "[]";
		parse_str($submissionData, $formData);
		array_walk_recursive($formData, function(&$item){
			//slashed before sanitization. Use wp_unslash()
			$item = sanitize_text_field( wp_unslash( $item ) );
		});
		$popupPostId = isset($_POST['popupPostId']) ? (int)sanitize_text_field( wp_unslash( $_POST['popupPostId'] ) ) : '';
		if(empty($_POST)) {
			echo esc_html( SGPB_AJAX_STATUS_FALSE );
			wp_die();
		}
		$email = isset($_POST['emailValue']) ? sanitize_email( wp_unslash( $_POST['emailValue'] ) ) : '';
		$firstName = isset($_POST['firstNameValue']) ? sanitize_text_field( wp_unslash( $_POST['firstNameValue'] ) ) : '';
		$lastName = isset($_POST['lastNameValue']) ? sanitize_text_field( wp_unslash( $_POST['lastNameValue'] ) ) : '';
		$userData = array(
			'email'     => $email,
			'firstName' => $firstName,
			'lastName'  => $lastName
		);
		$this->sendSuccessEmails($popupPostId, $userData);
		do_action('sgpbProcessAfterSuccessfulSubmission', $popupPostId, $userData);
	}

	public function sendSuccessEmails($popupPostId, $subscriptionDetails)
	{
		global $wpdb;
		$popup = SGPopup::find($popupPostId);

		if(!is_object($popup)) {
			return false;
		}
		$subscribersTableName = $wpdb->prefix.SGPB_SUBSCRIBERS_TABLE_NAME;

		$count = $wpdb->get_row( $wpdb->prepare("SELECT COUNT(id) as countIds FROM $subscribersTableName WHERE subscriptionType = %d", $popupPostId), ARRAY_A);

		$popupOptions = $popup->getOptions();
		$adminUserName = 'admin';

		$adminEmail = get_option('admin_email');
		$userData = @get_user_by('email', $adminEmail);

		if(!empty($userData)) {
			$adminUserName = $userData->display_name;
		}

		$newSubscriberEmailHeader = AdminHelper::getEmailHeader($adminEmail);
		$takeReviewAfterFirstSubscription = get_option('sgpb-new-subscriber');

		if($count['countIds'] == 1 && !$takeReviewAfterFirstSubscription) {
			// take review
			update_option('sgpb-new-subscriber', 1);
			$newSubscriberEmailTitle = __('Congrats! You have already 1 subscriber!', 'popup-builder');
			$reviewEmailTemplate = AdminHelper::getFileFromURL(SG_POPUP_EMAIL_TEMPLATES_URL.'takeReviewAfterSubscribe.html');
			$reviewEmailTemplate = preg_replace('/\[adminUserName]/', $adminUserName, $reviewEmailTemplate);
			$sendStatus = wp_mail($adminEmail, $newSubscriberEmailTitle, $reviewEmailTemplate, $newSubscriberEmailHeader); //return true or false
		}
	}

	public function select2SearchData()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}

		$postTypeName = isset($_POST['searchKey']) ? sanitize_text_field( wp_unslash( $_POST['searchKey'] ) ) : ''; // TODO strongly validate postTypeName example: use ENUM
		$search = isset($_POST['searchTerm']) ? sanitize_text_field( wp_unslash( $_POST['searchTerm'] ) ) : '';

		switch($postTypeName){
			case 'postCategories':
				$searchResults  = SGPBConfigDataHelper::getPostsAllCategories('post', [], $search);
				break;
			case 'postTags':
				$searchResults  = SGPBConfigDataHelper::getAllTags($search);
				break;
			default:
				$searchResults = $this->selectFromPost($postTypeName, $search);
		}

		if(isset($_POST['searchCallback'])) {
			$searchCallback = sanitize_text_field( wp_unslash( $_POST['searchCallback'] ) );
			$searchResults = apply_filters('sgpbSearchAdditionalData', $search, array());
		}

		if(empty($searchResults)) {
			$results['items'] = array();
		}

		/*Selected custom post type convert for select2 format*/
		foreach($searchResults as $id => $name) {
			$results['items'][] = array(
				'id'   => $id,
				'text' => $name
			);
		}

		wp_send_json($results);
	}

	private function selectFromPost($postTypeName, $search)
	{
		$args = array(
			's'              => $search,
			'post__in'       => !empty($_REQUEST['include']) ? array_map('intval', $_REQUEST['include']) : null,
			'page'           => !empty($_REQUEST['page']) ? absint($_REQUEST['page']) : null,
			'posts_per_page' => 100,
			'post_type'      => $postTypeName
		);
		$searchResults = SGPBConfigDataHelper::getPostTypeData($args);

		return $searchResults;
	}

	public function addConditionGroupRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		global $SGPB_DATA_CONFIG_ARRAY;

		$groupId = isset($_POST['groupId']) ? (int)sanitize_text_field( wp_unslash( $_POST['groupId'] ) ) : '';
		$targetType = isset($_POST['conditionName']) ? sanitize_text_field( wp_unslash( $_POST['conditionName'] ) ) : '';
		$addedObj = array();

		$builderObj = new ConditionBuilder();

		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId(SG_CONDITION_FIRST_RULE);
		$builderObj->setSavedData($SGPB_DATA_CONFIG_ARRAY[$targetType]['initialData'][0]);
		$builderObj->setConditionName($targetType);
		$addedObj[] = $builderObj;

		$creator = new ConditionCreator($addedObj);
		echo wp_kses($creator->render(), AdminHelper::allowed_html_tags());
		wp_die();
	}

	public function addConditionRuleRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		$data = '';
		global $SGPB_DATA_CONFIG_ARRAY;
		$targetType = isset($_POST['conditionName']) ? sanitize_text_field( wp_unslash( $_POST['conditionName'] ) ) : '';
		$builderObj = new ConditionBuilder();

		$groupId = isset($_POST['groupId']) ? (int)sanitize_text_field( wp_unslash( $_POST['groupId'] ) ) : '';
		$ruleId = isset($_POST['ruleId']) ? (int)sanitize_text_field( wp_unslash( $_POST['ruleId'] ) ) : '';

		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId($ruleId);
		$builderObj->setSavedData($SGPB_DATA_CONFIG_ARRAY[$targetType]['initialData'][0]);
		$builderObj->setConditionName($targetType);

		$data .= ConditionCreator::createConditionRuleRow($builderObj);

		echo wp_kses($data, AdminHelper::allowed_html_tags());
		wp_die();
	}

	public function changeConditionRuleRow()
	{
		check_ajax_referer(SG_AJAX_NONCE, 'nonce_ajax');

		$allowToAction = AdminHelper::userCanAccessTo();

		if( !$allowToAction )
		{
			/**
			 * We only allow administrator or roles allowed in setting to do this action
			*/ 			
			if ( ! current_user_can( 'manage_options' ) ) {
				
				wp_die(esc_html__('You do not have permission to clone the popup!', 'popup-builder'));
			}
		}
		$data = '';
		global $SGPB_DATA_CONFIG_ARRAY;

		$targetType = isset($_POST['conditionName']) ? sanitize_text_field( wp_unslash( $_POST['conditionName'] ) ) : '';
		$builderObj = new ConditionBuilder();
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$targetType];
		$groupId = isset($_POST['groupId']) ? (int)sanitize_text_field( wp_unslash( $_POST['groupId'] ) ) : '';
		$ruleId = isset($_POST['ruleId']) ? (int)sanitize_text_field( wp_unslash( $_POST['ruleId'] ) ) : '';
		$popupId = isset($_POST['popupId']) ? (int)sanitize_text_field( wp_unslash( $_POST['popupId'] ) ) : '';
		$paramName = isset($_POST['paramName']) ? sanitize_text_field( wp_unslash( $_POST['paramName'] ) ) : '';

		$savedData = array(
			'param' => $paramName
		);

		if($targetType == 'target' || $targetType == 'conditions') {
			$savedData['operator'] = '==';
		} else if($conditionConfig['specialDefaultOperator']) {
			$savedData['operator'] = $paramName;
		}

		if(!empty($_POST['paramValue'])) {
			$savedData['tempParam'] = sanitize_text_field( wp_unslash( $_POST['paramValue'] ) );
			$savedData['operator'] = $paramName;
		}
		// change operator value related to condition value
		if(!empty($conditionConfig['operatorAllowInConditions']) && in_array($paramName, $conditionConfig['operatorAllowInConditions'])) {
			$conditionConfig['paramsData']['operator'] = array();

			if(!empty($conditionConfig['paramsData'][$paramName.'Operator'])) {
				$operatorData = $conditionConfig['paramsData'][$paramName.'Operator'];
				$SGPB_DATA_CONFIG_ARRAY[$targetType]['paramsData']['operator'] = $operatorData;
				// change take value related to condition value
				$operatorDataKeys = array_keys($operatorData);
				if(!empty($operatorDataKeys[0])) {
					$savedData['operator'] = $operatorDataKeys[0];
					$builderObj->setTakeValueFrom('operator');
				}
			}
		}
		// by default set empty value for users' role (adv. tar.)
		$savedData['value'] = array();
		$savedData['hiddenOption'] = isset($conditionConfig['hiddenOptionData'][$paramName]) ? $conditionConfig['hiddenOptionData'][$paramName] : '';

		$builderObj->setPopupId($popupId);
		$builderObj->setGroupId($groupId);
		$builderObj->setRuleId($ruleId);
		$builderObj->setSavedData($savedData);
		$builderObj->setConditionName($targetType);

		$data .= ConditionCreator::createConditionRuleRow($builderObj);

		echo wp_kses($data, AdminHelper::allowed_html_tags());
		wp_die();
	}
}
