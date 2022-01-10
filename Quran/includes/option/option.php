<?php
require_once('social.php');
require_once('payment.php');
require_once('sms.php');

if(!defined('db_data_name'))
{
	define('db_data_name', 'salamquran_data');
}


self::$language =
[
	'default' => 'en',
	'list'    => ['fa','en','ar'],
];
/**
 * system default lanuage
 */
self::$config['site']['title']         = "SalamQuran";
self::$config['site']['desc']          = "Say hello to Quran! Quran is calling you. Access to Quran in your language. Free and Easy.";
self::$config['site']['slogan']        = "Quran Anywhere Anytime";


self::$config['site']['googleAnalytics'] = "UA-130942533-12";


self::$config['enter']['force_verify'] = false;

// self::$config['coming'] = true;

self::$config['api_v6']['appkey']   = [];
self::$config['api_v6']['appkey'][] = null;


self::$config['force_stop_log_header']   = true;



?>