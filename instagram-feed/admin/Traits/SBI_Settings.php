<?php

/**
 * The Settings Trait
 *
 * @since 4.0
 */

namespace InstagramFeed\Admin\Traits;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


trait SBI_Settings
{
	/**
	 * Return the locales
	 *
	 * @return array
	 * @since 4.0
	 */
	public static function locales()
	{
		return array(
			'af_ZA' => 'Afrikaans',
			'ar_AR' => 'Arabic',
			'az_AZ' => 'Azerbaijani',
			'be_BY' => 'Belarusian',
			'bg_BG' => 'Bulgarian',
			'bn_IN' => 'Bengali',
			'bs_BA' => 'Bosnian',
			'ca_ES' => 'Catalan',
			'cs_CZ' => 'Czech',
			'cy_GB' => 'Welsh',
			'da_DK' => 'Danish',
			'de_DE' => 'German',
			'el_GR' => 'Greek',
			'en_GB' => 'English (UK',
			'en_PI' => 'English (Pirate)',
			'en_US' => 'English (US)',
			'eo_EO' => 'Esperanto',
			'es_ES' => 'Spanish (Spain)',
			'es_LA' => 'Spanish',
			'et_EE' => 'Estonian',
			'eu_ES' => 'Basque',
			'fa_IR' => 'Persian',
			'fb_LT' => 'Leet Speak',
			'fi_FI' => 'Finnish',
			'fo_FO' => 'Faroese',
			'fr_CA' => 'French (Canada)',
			'fr_FR' => 'French (France)',
			'fy_NL' => 'Frisian',
			'ga_IE' => 'Irish',
			'gl_ES' => 'Galician',
			'he_IL' => 'Hebrew',
			'hi_IN' => 'Hindi',
			'hr_HR' => 'Croatian',
			'hu_HU' => 'Hungarian',
			'hy_AM' => 'Armenian',
			'id_ID' => 'Indonesian',
			'is_IS' => 'Icelandic',
			'it_IT' => 'Italian',
			'ja_JP' => 'Japanese',
			'ka_GE' => 'Georgian',
			'km_KH' => 'Khmer',
			'ko_KR' => 'Korean',
			'ku_TR' => 'Kurdish',
			'la_VA' => 'Latin',
			'lt_LT' => 'Lithuanian',
			'lv_LV' => 'Latvian',
			'mk_MK' => 'Macedonian',
			'ml_IN' => 'Malayalam',
			'ms_MY' => 'Malay',
			'nb_NO' => 'Norwegian (bokmal)',
			'ne_NP' => 'Nepali',
			'nl_NL' => 'Dutch',
			'nn_NO' => 'Norwegian (nynorsk)',
			'pa_IN' => 'Punjabi',
			'pl_PL' => 'Polish',
			'ps_AF' => 'Pashto',
			'pt_BR' => 'Portuguese (Brazil)',
			'pt_PT' => 'Portuguese (Portugal)',
			'ro_RO' => 'Romanian',
			'ru_RU' => 'Russian',
			'sk_SK' => 'Slovak',
			'sl_SI' => 'Slovenian',
			'sq_AL' => 'Albanian',
			'sr_RS' => 'Serbian',
			'sv_SE' => 'Swedish',
			'sw_KE' => 'Swahili',
			'ta_IN' => 'Tamil',
			'te_IN' => 'Telugu',
			'th_TH' => 'Thai',
			'tl_PH' => 'Filipino',
			'tr_TR' => 'Turkish',
			'uk_UA' => 'Ukrainian',
			'vi_VN' => 'Vietnamese',
			'zh_CN' => 'Simplified Chinese (China)',
			'zh_HK' => 'Traditional Chinese (Hong Kong)',
			'zh_TW' => 'Traditional Chinese (Taiwan)',
		);
	}

	/**
	 * Return the timezones
	 *
	 * @return array
	 * @since 4.0
	 */
	public static function timezones()
	{
		return array(
			'Pacific/Midway' => '(GMT-11:00) Midway Island, Samoa',
			'America/Adak' => '(GMT-10:00) Hawaii-Aleutian',
			'Etc/GMT+10' => '(GMT-10:00) Hawaii',
			'Pacific/Marquesas' => '(GMT-09:30) Marquesas Islands',
			'Pacific/Gambier' => '(GMT-09:00) Gambier Islands',
			'America/Anchorage' => '(GMT-09:00) Alaska',
			'America/Ensenada' => '(GMT-08:00) Tijuana, Baja California',
			'Etc/GMT+8' => '(GMT-08:00) Pitcairn Islands',
			'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada',
			'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada',
			'America/Chihuahua' => '(GMT-07:00) Chihuahua, La Paz, Mazatlan',
			'America/Dawson_Creek' => '(GMT-07:00) Arizona',
			'America/Belize' => '(GMT-06:00) Saskatchewan, Central America',
			'America/Cancun' => '(GMT-06:00) Guadalajara, Mexico City, Monterrey',
			'Chile/EasterIsland' => '(GMT-06:00) Easter Island',
			'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
			'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
			'America/Havana' => '(GMT-05:00) Cuba',
			'America/Bogota' => '(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
			'America/Caracas' => '(GMT-04:30) Caracas',
			'America/Santiago' => '(GMT-04:00) Santiago',
			'America/La_Paz' => '(GMT-04:00) La Paz',
			'Atlantic/Stanley' => '(GMT-04:00) Faukland Islands',
			'America/Campo_Grande' => '(GMT-04:00) Brazil',
			'America/Goose_Bay' => '(GMT-04:00) Atlantic Time (Goose Bay)',
			'America/Glace_Bay' => '(GMT-04:00) Atlantic Time (Canada)',
			'America/St_Johns' => '(GMT-03:30) Newfoundland',
			'America/Araguaina' => '(GMT-03:00) UTC-3',
			'America/Montevideo' => '(GMT-03:00) Montevideo',
			'America/Miquelon' => '(GMT-03:00) Miquelon, St. Pierre',
			'America/Godthab' => '(GMT-03:00) Greenland',
			'America/Argentina/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
			'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
			'America/Noronha' => '(GMT-02:00) Mid-Atlantic',
			'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is',
			'Atlantic/Azores' => '(GMT-01:00) Azores',
			'Europe/Belfast' => '(GMT) Greenwich Mean Time : Belfast',
			'Europe/Dublin' => '(GMT) Greenwich Mean Time : Dublin',
			'Europe/Lisbon' => '(GMT) Greenwich Mean Time : Lisbon',
			'Europe/London' => '(GMT) Greenwich Mean Time : London',
			'Africa/Abidjan' => '(GMT) Monrovia, Reykjavik',
			'Europe/Amsterdam' => '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
			'Europe/Belgrade' => '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
			'Europe/Brussels' => '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
			'Africa/Algiers' => '(GMT+01:00) West Central Africa',
			'Africa/Windhoek' => '(GMT+01:00) Windhoek',
			'Asia/Beirut' => '(GMT+02:00) Beirut',
			'Africa/Cairo' => '(GMT+02:00) Cairo',
			'Asia/Gaza' => '(GMT+02:00) Gaza',
			'Africa/Blantyre' => '(GMT+02:00) Harare, Pretoria',
			'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
			'Europe/Helsinki' => '(GMT+02:00) Helsinki',
			'Europe/Minsk' => '(GMT+02:00) Minsk',
			'Asia/Damascus' => '(GMT+02:00) Syria',
			'Europe/Moscow' => '(GMT+03:00) Moscow, St. Petersburg, Volgograd',
			'Africa/Addis_Ababa' => '(GMT+03:00) Nairobi',
			'Asia/Tehran' => '(GMT+03:30) Tehran',
			'Asia/Dubai' => '(GMT+04:00) Abu Dhabi, Muscat',
			'Asia/Yerevan' => '(GMT+04:00) Yerevan',
			'Asia/Kabul' => '(GMT+04:30) Kabul',
			'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
			'Asia/Tashkent' => '(GMT+05:00) Tashkent',
			'Asia/Kolkata' => '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
			'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
			'Asia/Dhaka' => '(GMT+06:00) Astana, Dhaka',
			'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
			'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon',
			'Asia/Bangkok' => '(GMT+07:00) Bangkok, Hanoi, Jakarta',
			'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
			'Asia/Hong_Kong' => '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
			'Asia/Irkutsk' => '(GMT+08:00) Irkutsk, Ulaan Bataar',
			'Australia/Perth' => '(GMT+08:00) Perth',
			'Australia/Eucla' => '(GMT+08:45) Eucla',
			'Asia/Tokyo' => '(GMT+09:00) Osaka, Sapporo, Tokyo',
			'Asia/Seoul' => '(GMT+09:00) Seoul',
			'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
			'Australia/Adelaide' => '(GMT+09:30) Adelaide',
			'Australia/Darwin' => '(GMT+09:30) Darwin',
			'Australia/Brisbane' => '(GMT+10:00) Brisbane',
			'Australia/Hobart' => '(GMT+10:00) Sydney',
			'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
			'Australia/Lord_Howe' => '(GMT+10:30) Lord Howe Island',
			'Etc/GMT-11' => '(GMT+11:00) Solomon Is., New Caledonia',
			'Asia/Magadan' => '(GMT+11:00) Magadan',
			'Pacific/Norfolk' => '(GMT+11:30) Norfolk Island',
			'Asia/Anadyr' => '(GMT+12:00) Anadyr, Kamchatka',
			'Pacific/Auckland' => '(GMT+12:00) Auckland, Wellington',
			'Etc/GMT-12' => 'GMT+12:00) Fiji, Kamchatka, Marshall Is',
			'Pacific/Chatham' => '(GMT+12:45) Chatham Islands',
			'Pacific/Tongatapu' => '(GMT+13:00) Nuku\'alofa',
			'Pacific/Kiritimati' => '(GMT+14:00) Kiritimati'
		);
	}
}
