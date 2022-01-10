<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\step;

class Quran
{
	public static function start()
	{
		bot::ok();

		// if start with callback answer callback
		if(bot::isCallback())
		{
			bot::answerCallbackQuery(T_("Quran is calling you!"));
		}

		// show message to go to website
		$msg = '';
		// $msg .= T_('You have no survey yet!') ."\n\n";
		$msg .= "<b>". T_('SalamQuran'). "</b>\n\n";
		$msg .= T_('Please choose from below keyboard or type your request.');
		$msg .= "\n\n";

		$result =
		[
			'text' => $msg,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Page"),
							'callback_data'  => '/p',
						],
					],
					[
						[
							'text' => T_("Surah"),
							'callback_data'  => '/s',
						],
						[
							'text' => T_("Juz"),
							'callback_data'  => '/j',
						],
					],
					[
						[
							'text' => T_("Aye of the day"),
							'callback_data'  => '/a_today',
						],
						[
							'text' => T_("Page of the day"),
							'callback_data'  => '/p_today',
						],
					],
					[
						[
							'text' => T_("Random Aye"),
							'callback_data'  => '/a_random',
						],
						[
							'text' => T_("Random Page"),
							'callback_data'  => '/p_random',
						],
					],
					// [
					// 	[
					// 		'text' => T_("PDF"),
					// 		'callback_data'  => '/pdf1',
					// 	],
					// ],
				]
			]
		];


		// add sync
		if(!\dash\user::detail('mobile'))
		{
			$result['reply_markup']['inline_keyboard'][][] =
			[
				'text'          => T_("Sync with website"),
				'callback_data' => 'sync',
			];
		}

		bot::sendMessage($result);
	}


	public static function requireCode()
	{
		bot::ok();
		$msg = T_("We need a valid number!")." 🙁";

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => $msg,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		$result =
		[
			'text' => $msg,
		];
		bot::sendMessage($result);
	}


	public static function page($_pageNumber, $_from = null,  $_random = null)
	{
		$current = $_pageNumber;
		bot::ok();

		if($_pageNumber)
		{
			if(is_numeric($_pageNumber))
			{
				if($_pageNumber < 1 || $_pageNumber > 604)
				{
					return self::requireCode();
				}
			}
			elseif($_pageNumber === 'today')
			{
				$apiUrl = bot::website(). '/api/v6/page/day';
				$pageOfTheDay  = \dash\curl::go($apiUrl);

				if(isset($pageOfTheDay['result']['page']))
				{
					$current = $pageOfTheDay['result']['page'];
				}
				else
				{
					// page of the day is not exist, show random page
					$current = mt_rand(1, 604);
				}
			}
			else
			{
				// if text like today, get today page number
				$current = mt_rand(1, 604);
			}

		}
		else
		{
			$randomVal = mt_rand(1, 604);
			// we dont have number show help of page
			$msg = '';
			$msg .= "<b>". T_('SalamQuran'). "</b>". "\n";
			$msg .= T_('For access to specefic page of Quran please use and type one of below syntax')."\n";
			$msg .= "<code>ص". $randomVal. "</code>"."\n";
			$msg .= "<code>p". $randomVal. "</code>"."\n";
			$msg .= "/p". $randomVal. "\n";
			$msg .= "\n";
			$msg .= bot::website();
			bot::sendMessage($msg);
			return true;
		}

		// if start with callback answer callback
		if(bot::isCallback())
		{
			bot::answerCallbackQuery(T_("Request for Quran page"). ' ' . $current);
		}

		$next = $current + 1;
		if($next > 604)
		{
			$next = 1;
		}
		$prev = $current - 1;
		if($prev < 1)
		{
			$prev = 604;
		}

		$website = bot::website(). '/p'. $current;

		$currentPageNum = str_pad($current, 3, "0", STR_PAD_LEFT);
		$dlLink = 'https://dl.salamquran.com/images/v1/page'. $currentPageNum. '.png';


		// show message to go to website
		$msg = '';
		// $msg .= T_('You have no survey yet!') ."\n\n";
		$msg .= "<b>". T_('SalamQuran'). "</b> | ";
		$msg .= T_('Page'). ' '. $current;
		// show msg for random messages
		if($_random)
		{
			$msg .= ' <code>'. T_("Random"). '</code>';
		}
		$msg .= "\n";
		$msg .= $website;

		$result =
		[
			'photo'        => $dlLink,
			'caption'      => $msg,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Next"),
							'callback_data'  => '/p'. $next,
						],
						[
							'text' => T_("Prev"),
							'callback_data'  => '/p'. $prev,
						],
					],
					[
						[
							'text' => T_("Iqra"),
							'url'  => $website. '?autoplay=1',
						],
					],
				]
			]
		];


		// add sync
		if(!\dash\user::detail('mobile'))
		{
			$result['reply_markup']['inline_keyboard'][][] =
			[
				'text'          => T_("Sync with website"),
				'callback_data' => 'sync',
			];
		}

		bot::sendPhoto($result);
	}



	public static function juz($_juz)
	{
		if($_juz)
		{
			if(is_numeric($_juz))
			{
				if($_juz < 1 || $_juz > 30)
				{
					return self::requireCode();
				}
			}
			else
			{
				$startPage = self::page_of_juz(mt_rand(1, 30));
				return self::page($startPage, 'juz', true);
			}

		}
		else
		{
			bot::ok();
			$randomVal = mt_rand(1, 30);

			// we dont have number show help of juz
			$msg = '';
			$msg .= "<b>". T_('SalamQuran'). "</b>". "\n";
			$msg .= T_('For access to specefic juz of Quran please use and type one of below syntax')."\n";
			$msg .= "<code>ج". $randomVal. "</code>"."\n";
			$msg .= "<code>j". $randomVal. "</code>"."\n";
			$msg .= "/j". $randomVal. "\n";

			$msg .= "\n";
			$msg .= bot::website();
			bot::sendMessage($msg);
			return true;
		}

		$startPage = self::page_of_juz($_juz);

		return self::page($startPage, 'juz');
	}


	public static function page_of_juz($_juz)
	{
		$juz_startpages =
		[
			1  => 1,
			2  => 22,
			3  => 42,
			4  => 62,
			5  => 82,
			6  => 102,
			7  => 121,
			8  => 142,
			9  => 162,
			10 => 182,
			11 => 201,
			12 => 222,
			13 => 242,
			14 => 262,
			15 => 282,
			16 => 302,
			17 => 322,
			18 => 342,
			19 => 362,
			20 => 382,
			21 => 402,
			22 => 422,
			23 => 442,
			24 => 462,
			25 => 482,
			26 => 502,
			27 => 522,
			28 => 542,
			29 => 562,
			30 => 582
		];

		if(isset($juz_startpages[$_juz]))
		{
			return $juz_startpages[$_juz];
		}
	}


	public static function surah($_surah)
	{
		if($_surah)
		{
			if(is_numeric($_surah))
			{
				if($_surah < 1 || $_surah > 114)
				{
					return self::requireCode();
				}
			}
			else
			{
				$startPage = self::page_of_surah(mt_rand(1, 114));
				return self::page($startPage, 'surah', true);
			}

		}
		else
		{
			bot::ok();
			$randomVal = mt_rand(1, 114);

			// we dont have number show help of juz
			$msg = '';
			$msg .= "<b>". T_('SalamQuran'). "</b>". "\n";
			$msg .= T_('For access to specefic surah of Quran please use and type one of below syntax')."\n";
			$msg .= "<code>س". $randomVal. "</code>"."\n";
			$msg .= "<code>s". $randomVal. "</code>"."\n";
			$msg .= "/s". $randomVal. "\n";

			$msg .= "\n";
			$msg .= bot::website();
			bot::sendMessage($msg);
			return true;
		}

		$startPage = self::page_of_surah($_surah);

		return  self::page($startPage, 'surah');
	}


	public static function page_of_surah($_surah)
	{
		$sura_startpages =
		[
			1   => 1,
			2   => 2,
			3   => 50,
			4   => 77,
			5   => 106,
			6   => 128,
			7   => 151,
			8   => 177,
			9   => 187,
			10  => 208,
			11  => 221,
			12  => 235,
			13  => 249,
			14  => 255,
			15  => 262,
			16  => 267,
			17  => 282,
			18  => 293,
			19  => 305,
			20  => 312,
			21  => 322,
			22  => 332,
			23  => 342,
			24  => 350,
			25  => 359,
			26  => 367,
			27  => 377,
			28  => 385,
			29  => 396,
			30  => 404,
			31  => 411,
			32  => 415,
			33  => 418,
			34  => 428,
			35  => 434,
			36  => 440,
			37  => 446,
			38  => 453,
			39  => 458,
			40  => 467,
			41  => 477,
			42  => 483,
			43  => 489,
			44  => 496,
			45  => 499,
			46  => 502,
			47  => 507,
			48  => 511,
			49  => 515,
			50  => 518,
			51  => 520,
			52  => 523,
			53  => 526,
			54  => 528,
			55  => 531,
			56  => 534,
			57  => 537,
			58  => 542,
			59  => 545,
			60  => 549,
			61  => 551,
			62  => 553,
			63  => 554,
			64  => 556,
			65  => 558,
			66  => 560,
			67  => 562,
			68  => 564,
			69  => 566,
			70  => 568,
			71  => 570,
			72  => 572,
			73  => 574,
			74  => 575,
			75  => 577,
			76  => 578,
			77  => 580,
			78  => 582,
			79  => 583,
			80  => 585,
			81  => 586,
			82  => 587,
			83  => 587,
			84  => 589,
			85  => 590,
			86  => 591,
			87  => 591,
			88  => 592,
			89  => 593,
			90  => 594,
			91  => 595,
			92  => 595,
			93  => 596,
			94  => 596,
			95  => 597,
			96  => 597,
			97  => 598,
			98  => 598,
			99  => 599,
			100 => 599,
			101 => 600,
			102 => 600,
			103 => 601,
			104 => 601,
			105 => 601,
			106 => 602,
			107 => 602,
			108 => 602,
			109 => 603,
			110 => 603,
			111 => 603,
			112 => 604,
			113 => 604,
			114 => 604
		];

		if(isset($sura_startpages[$_surah]))
		{
			return $sura_startpages[$_surah];
		}
	}


	public static function aya($_aye)
	{
		bot::ok();

		if($_aye)
		{
			if(is_numeric($_aye))
			{
				if($_aye < 1 || $_aye > 6236)
				{
					return self::requireCode();
				}
			}
			elseif($_aye === 'today')
			{
				$apiUrl = bot::website(). '/api/v6/aya/day';
				$ayaOfTheDay  = \dash\curl::go($apiUrl);

				if(isset($ayaOfTheDay['result']['index']))
				{
					$_aye = $ayaOfTheDay['result']['index'];
				}
				else
				{
					// page of the day is not exist, show random page
					$_aye = mt_rand(1, 6236);
				}
			}
			else
			{
				// get random aye
				$_aye = mt_rand(1, 6236);
			}

		}
		else
		{
			bot::ok();
			$randomVal = mt_rand(1, 6236);

			// we dont have number show help of juz
			$msg = '';
			$msg .= "<b>". T_('SalamQuran'). "</b>". "\n";
			$msg .= T_('For access to specefic aya of Quran please use and type one of below syntax')."\n";
			$msg .= "<code>آ". $randomVal. "</code>"."\n";
			$msg .= "<code>a". $randomVal. "</code>"."\n";
			$msg .= "/a". $randomVal. "\n";

			$msg .= "\n";
			$msg .= bot::website();
			bot::sendMessage($msg);
			return true;
		}


		// if start with callback answer callback
		if(bot::isCallback())
		{
			bot::answerCallbackQuery(T_("Request for Quran aya"). ' ' . $_aye);
		}

		$apiUrl = bot::website(). '/api/v6/aya?index='. $_aye;
		$myAye  = \dash\curl::go($apiUrl);
		if(isset($myAye['result']['text']))
		{
			$msg = '';
			$msg .= $myAye['result']['text']. "\n";

			if(isset($myAye['result']['sura_detail']['name']))
			{
				// add surah name
				$msg .= T_('Surah'). ' '. $myAye['result']['sura_detail']['name']. ' ';
				// add surah aya number
				if(isset($myAye['result']['aya']))
				{
					$msg .= T_('Aya'). ' '. $myAye['result']['aya'];
				}
			}

			$msg .= "\n\n";
			$msg .= "<b>". T_('SalamQuran'). "</b>". "\n";
			$msg .= bot::website();
			if(isset($myAye['result']['index']))
			{
				$msg .= '/a'. $myAye['result']['index'];
			}

			bot::sendMessage($msg);
			return true;
		}
		return null;
	}


	public static function pdf1()
	{
		bot::ok();
		$msg    = T_("Full text of Quran");
		$dlLink = 'https://dl.salamquran.com/images/pdf/v1/SalamQuran.pdf';

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => $msg,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		$result =
		[
			'caption'     => $msg,
			// 'thumb' => '',
			'document' => $dlLink
		];
		bot::sendDocument($result);
	}
}
