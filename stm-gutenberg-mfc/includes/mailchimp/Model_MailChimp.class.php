<?php
/**
 *          RAFAEL FERREIRA © 2014 || MailChimp Form
 * ------------------------------------------------------------------------
 *                      ** MailChimp Class    **
 * ------------------------------------------------------------------------
 */
require_once("mailchimp.php");

class Model_MailChimp{
    public static function subscribe($email, $merge_vars) {
		$settings = get_option('stmt_to_settings', array());
		$mailchimp_key = (!empty($settings['mc_api_key'])) ? $settings['mc_api_key'] : '';
		$mailchimp_list = (!empty($settings['mc_list_id'])) ? $settings['mc_list_id'] : '';

        $instance = new Mailchimp($mailchimp_key);
        return $instance->lists->subscribe($mailchimp_list, array("email" => $email), $merge_vars, 'html', false);
    }

    public static function subscribe_with_confirmation($email, $merge_vars) {
		$settings = get_option('stmt_to_settings', array());
		$mailchimp_key = (!empty($settings['mc_api_key'])) ? $settings['mc_api_key'] : '';
		$mailchimp_list = (!empty($settings['mc_list_id'])) ? $settings['mc_list_id'] : '';

        $instance = new Mailchimp($mailchimp_key);
        return $instance->lists->subscribe($mailchimp_list, array("email" => $email), $merge_vars);
    }
}