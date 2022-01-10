<?php
namespace lib\app;

class application
{

	public static function detail_v6()
	{
		$detail             = [];
		$detail['version']  = self::version();

		$detail['intro']    = self::intro();
		return $detail;
	}


	private static function version()
	{
		$detail                     = [];
		$detail['last']             = 11;
		$detail['deprecated']       = 1;
		$detail['deprecated_title'] = T_("This version is deprecated");
		$detail['deprecated_desc']  = T_("To download new version of this app click blow link");
		$detail['update_title']     = T_("New version is released");
		$detail['update_desc']      = T_("To download new version of this app click blow link");

		return $detail;
	}

	private static function intro()
	{

		$intro   = [];
		$intro[] =
		[
			'title'       => T_("Say hello to Quran! Quran is calling you."),
			'desc'        => T_("Read Quran By font Osman Taha"),
			'bg_from'     => '#ffffff',
			'bg_to'       => '#ffffff',
			'title_color' => '#000000',
			'desc_color'  => '#000000',
			'image'       => \dash\url::static(). '/images/android/img_intro_1.png',
			'btn'         =>
			[
				[
					'title'  => T_("Next"),
					'action' => 'next',
				],
			],
		];

		$intro[] =
		[
			'title'       => T_("Recitation of the Quran by qari prominent"),
			'desc'        => T_("Easily use recitation and illustrative translations"),
			'bg_from'     => '#ffffff',
			'bg_to'       => '#ffffff',
			'title_color' => '#000000',
			'desc_color'  => '#000000',
			'image'       => \dash\url::static(). '/images/android/img_intro_2.png',
			'btn'         =>
			[
				[
					'title'  => T_("Prev"),
					'action' => 'prev',
				],
				[
					'title'  => T_("Next"),
					'action' => 'next',
				],
			],
		];

		$intro[] =
		[
			'title'       => T_('Quick and easy learning of the Holy Quran'),
			'desc'        => T_('Quick and easy learning of the Holy Quran'),
			'bg_from'     => '#ffffff',
			'bg_to'       => '#ffffff',
			'title_color' => '#000000',
			'desc_color'  => '#000000',
			'image'       => \dash\url::static(). '/images/android/img_intro_3.png',
			'btn'         =>
			[
				[
					'title'  => T_("Prev"),
					'action' => 'prev',
				],
				[
					'title'  => T_("Next"),
					'action' => 'next',
				],
			],
		];

		$intro[] =
		[
			'title'       => T_('Completely Free'),
			'desc'        => T_('Take advantage of all the possibilities of Salam Quran for free!'),
			'bg_from'     => '#ffffff',
			'bg_to'       => '#ffffff',
			'title_color' => '#000000',
			'desc_color'  => '#000000',
			'image'       => \dash\url::static(). '/images/android/img_intro_4.png',
			'btn' =>
			[
				[
					'title'  => T_("Start"),
					'action' => 'start',
				],
			],
		];

		return $intro;
	}


}
?>