<?php
/*
	Copyright (C) 2015-20 CERBER TECH INC., https://cerber.tech
	Copyright (C) 2015-20 CERBER TECH INC., https://wpcerber.com

    Licenced under the GNU GPL.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*

*========================================================================*
|                                                                        |
|	       ATTENTION!  Do not change or edit this file!                  |
|                                                                        |
*========================================================================*

*/

// If this file is called directly, abort executing.
if ( ! defined( 'WPINC' ) ) {
	exit;
}


function cerber_push_the_news( $version ) {

	$news['3.0'] =
		'<h3>Welcome a new version with reCAPTCHA and WordPress filters</h3>
<ul>
 	<li>Now you can use Google reCAPTCHA to protect WordPress registration form from spam registrations. Also reCAPTCHA available for lost password and login forms. <a href="https://wpcerber.com/how-to-setup-recaptcha/">How to setup reCAPTCHA</a>.</li>
 	<li>The registration process, WordPress registration form, XML-RPC, WP REST API are controlled by <a href="http://wpcerber.com/using-ip-access-lists-to-protect-wordpress/">IP Access Lists</a>.</li>
 	<li>Registration is impossible if a particular IP address is locked out.</li>
 	<li>Registration with a prohibited username is impossible.</li>
 	<li><a href="https://wpcerber.com/wp-cerber-hooks/">A set of filters and actions</a>. They are useful if you want to customize some aspects of the plugin as you want.</li>
 	<li>A new action <strong>Get WHOIS info</strong> that obtains detailed WHOIS information about given IP address. You can use it in vary <a href="http://jetflow.io">jetFlow.io automation scenarios</a>. For instance, you can monitor countries from what your users are logged in on the website or you <a href="https://wpcerber.com/notifications-on-wordpress-user-logs-in/">monitor user logins with notifications</a>.</li>
 	<li>A new trigger <strong>IP locked out</strong> that starts automation scenario after a suspicious IP address has been locked out by the WP Cerber plugin.</li>
</ul>

';

	$news['4.0'] =
		'<h3>Welcome a new version with extended Access Lists and reCAPTCHA for WooCommerce</h3>
<ul>
 	<li>reCAPTCHA for WooCommerce forms. <a href="https://wpcerber.com/how-to-setup-recaptcha/">How to set up reCAPTCHA</a>.</li>
 	<li>IP Access Lists has got support for IP networks in three forms: ability to restrict access with IPv4 ranges, IPv4 CIDR notation and IPv4 subnets: A,B,C has been added. Read more: <a href="https://wpcerber.com/using-ip-access-lists-to-protect-wordpress/">Access Lists for WordPress</a>.</li>
 	<li>Cerber can automatically detect an IP network of an intruder and suggest you to block the entire network right from the Activity screen.</li>
 	<!-- <li>reCAPTCHA will not be shown and processed for IP addresses from the White IP Access List.</li> -->
</ul>

 <p><a href="https://wpcerber.com/wp-cerber-security-4-0/" target="_blank">Read a full list of changes and improvements</a></p> 
';

	$news['4.3'] =
		'<h3>What\'s new in version 4.3</h3>
<ul>
 	<li>Do you want to keep eye on specific activity on your website? I have good news for you! Track them like a PRO. Use powerful subscriptions to get email notifications according to filters for events you have set. Filter out activities that you are interested to monitor and then click Subscribe. <a href="https://wpcerber.com/wordpress-notifications-made-easy/">Read more</a></li>
 	<li>Search and/or filter activity by IP address, username (login), specific event and a user. You can use any combination of them. </li>
 	<li>Now you can export activity from your WordPress website to a CSV file. You can export all activities or a set of filtered activities only as it described above. When you will import the CSV file in your spreadsheet editor, don\'t forget to select UTF-8 charset.</li>
 	<li>You can use multiple email addresses for notifications (Main Settings -> Notifications -> Email Address). Use a comma to specify several addresses.</li>
</ul>
';

	$news['8.5'][] = 'A new Data Shield module for advanced protection of user data and vital settings in the website database. Available in the PRO version.';
	$news['8.5'][] = 'Improvement: Compatibility with WooCommerce significantly improved.';
	$news['8.5'][] = 'Bug fixed: Custom login URL doesn\'t work after updating WordPress to 5.2.3.';
	$news['8.5'][] = 'Bug fixed: User Policies tabs are not switchable if a user role was declared with a hyphen instead of the underscore.';
	$news['8.5'][] = 'Bug fixed: A PHP warning while adding a network to the Black IP Access List from the Activity tab.';
	$news['8.5'][] = 'Bug fixed: An anti-spam false positive: some WordPress DB updates can\'t be completed.';

	$news['8.5.3'][] = 'The malware scanner and integrity checker window has got a new filter that enables you to filter out and navigate to specific issues quickly.';
	$news['8.5.3'][] = 'New in Cerber.Hub: new columns and filters have been added to the list of slave websites. The new columns display server IP addresses, hostnames, and countries where servers are located.';
	$news['8.5.3'][] = 'Bug fixed: depending on the number of items in the access lists, the IP address 0.0.0.0 can be erroneously marked as whitelisted or blacklisted.';
	$news['8.5.3'][] = 'Bug fixed in Cerber.Hub: if a WordPress plugin is installed on several slave websites and the plugin needs to be updated on some of the slave websites, the plugin is shown as needs to be updated on all the slave websites.';

	$news['8.5.5'][] = 'IP Access Lists now support IPv6 networks, ranges, and wildcards. Add as many IPv6 entries to the access lists as you need. We\'ve developed an extraordinarily fast ACL engine to process them.';
	$news['8.5.5'][] = 'The algorithm of handling consecutive IP address lockouts has been improved: the reason for an existing lockout is updated and its duration is recalculated in real-time now.';
	$news['8.5.5'][] = 'Traffic inspection algorithms were optimized to reduce false positives and make algorithms more human-friendly.';
	$news['8.5.5'][] = 'Improved compatibility with WooCommerce: the password reset and login forms are not blocked anymore if a user???s IP gets locked out due to using a non-existing username by mistake, using a prohibited username, or if a user has exceeded the number of allowed login attempts.';
	$news['8.5.5'][] = 'Improved compatibility with WordPress scheduled cron tasks if a website runs on a server with PHP-FPM (FastCGI Process Manager)';
	$news['8.5.5'][] = 'Very long URLs on the Live Traffic page are now displayed in full when you click the "Details" link in a row.';
	$news['8.5.5'][] = 'The Cerber.Hub multi-site manager: the server column on the slave websites list page now contains a link to quickly filter out websites on the same server.';
	$news['8.5.5'][] = 'The Cerber.Hub multi-site manager: now it remembers the filtered list of slave websites while you???re switching between them and the master.';

	$news['8.5.6'][] = 'Now you can separately set the number of days of keeping log records in the database for authenticated (logged in) website users and non-authenticated (not logged in) visitors. These settings are configured for the activity log and the live traffic log separately.';
	$news['8.5.6'][] = 'You can completely turn off the Citadel mode feature in the Main Settings now.';
	$news['8.5.6'][] = 'The appearance of the Activity log has got small visual improvements.';
	$news['8.5.6'][] = 'When you upload a ZIP archive on the integrity scanner page it processes nested ZIP archives now and writes errors to the diagnostic log if it\'s enabled.';
	$news['8.5.6'][] = 'Fixed: The blacklisting buttons on the Activity tab do not work showing "Incorrect IP address or IP range".';

	$news['8.5.8'][] = 'A personal data export and erase feature which can be used through the WordPress personal data export and erase tool. This feature helps your organization to be in compliance with data privacy laws such as GDPR in Europe or CCPA in California';
	$news['8.5.8'][] = 'The performance of the algorithm that handles exporting rows from the Activity log and the Live Traffic log to a CSV file has been improved enabling export larger datasets';
	$news['8.5.8'][] = 'When you block a user you can add an optional admin note now';
	$news['8.5.8'][] = 'Fixed: If a user is blocked, it???s not possible to update the user message';
	$news['8.5.8'][] = 'Fixed: Depending on the logging settings the "Details" links on the Live Traffic log are not displayed in some rows';

	$news['8.5.9'][] = 'On the Live Traffic log, now you can search and filter our requests with software errors if they occurred.';
	$news['8.5.9'][] = 'The code of WP Cerber has been updated and tested to fully support and be compatible with PHP 7.4.';
	$news['8.5.9'][] = 'The layout of the list of slave websites on the Cerber.Hub\'s main page has been improved to display the list more accurately on narrow and mobile screens.';
	$news['8.5.9'][] = 'If a slave website has the professional version of WP Cerber, it has a PRO sign in the "WP Cerber" column. The license expiration date is shown when you hover the mouse over the sign.';
	$news['8.5.9'][] = 'Fixed: A bug with displaying long file names in the Security Scanner Quarantine that makes unavailable deleting or restoring quarantined files manually.';
	$news['8.5.9'][] = 'Fixed: A bug that requires installing a valid license key on a Cerber.Hub master website to permit configuring settings on slave websites remotely, which is not intended behavior.';

	if ( ! empty( $news[ $version ] ) ) {
		//$text = '<h3>What\'s new in WP Cerber '.$version.'</h3>';

		$text = '<h3>Highlights from WP Cerber Security '.$version.'</h3>';

		$text .= '<ul><li>'.implode('</li><li>', $news[ $version ]).'</li></ul>';

		$text .= '	<p style="margin-top: 18px; font-weight: bold;"><a href="https://wpcerber.com/?plugin_version='.$version.'" target="_blank">Read more on wpcerber.com</a></p>';
		$text .= '	<p style="margin-top: 18px; font-weight: bold;"><a href="'.cerber_admin_link('change-log').'">See the whole history in the changelog</a></p>';

		$text .= '	<p style="margin-top: 24px;"><span class="dashicons-before dashicons-email-alt"></span> &nbsp; <a href="https://wpcerber.com/subscribe-newsletter/">Subscribe to Cerber\'s newsletter</a></p>
					<p><span class="dashicons-before dashicons-twitter"></span> &nbsp; <a href="https://twitter.com/wpcerber">Follow Cerber on Twitter</a></p>
					<p><span class="dashicons-before dashicons-facebook"></span> &nbsp; <a href="https://www.facebook.com/wpcerber/">Follow Cerber on Facebook</a></p>
				';
		cerber_admin_info( $text );
	}
}


function cerber_admin_info($msg, $type = 'normal'){
	//global $crb_assets_url;
	$crb_assets_url = cerber_plugin_dir_url() . 'assets/';
	update_site_option('cerber_admin_info',
		'<table><tr><td><img style="float:left; margin-left:-10px;" src="'.$crb_assets_url.'icon-128x128.png"></td>'.
		'<td>'.$msg.
		'<p style="text-align:right;">
		<input type="button" class="button button-primary cerber-dismiss" value=" &nbsp; '.__('Awesome!','wp-cerber').' &nbsp; "/></p></td></tr></table>');
}


