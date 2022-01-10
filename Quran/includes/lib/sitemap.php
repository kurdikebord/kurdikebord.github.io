<?php
namespace lib;


class sitemap
{
	public static function create()
	{
		$language  = \dash\language::all();
		$read_mode = \lib\app\read_mode::list();

		foreach ($language as $lang => $detail)
		{
			$myLang = $lang;
			if($myLang == \dash\language::primary())
			{
				$myLang = null;
			}

			$translate_list = \lib\app\translate::all_translate_url();
			foreach ($translate_list as $translate)
			{
				self::aya($myLang, $translate);
			}

			foreach ($read_mode as $mode => $value)
			{
				$myMode = $mode;
				if($value['default'])
				{
					$myMode = null;
				}

				if($myMode === 'aya')
				{
					self::quran_link('sura', 	's', 	114, 	'0.9', 	'monthly', 	null, 	$myLang, 	$myMode);
				}

				self::quran_link('page',	'p', 	604, 	'0.8', 	'monthly', 	null, 	$myLang, 	$myMode);
				self::quran_link('juz', 	'j', 	30, 	'0.8', 	'monthly', 	null, 	$myLang, 	$myMode);
				self::quran_link('hizb', 	'h', 	60, 	'0.8', 	'monthly', 	null, 	$myLang, 	$myMode);
				self::quran_link('rub', 	'r', 	240, 	'0.8', 	'monthly', 	null, 	$myLang, 	$myMode);
				self::quran_link('nim', 	'n', 	120, 	'0.8', 	'monthly', 	null, 	$myLang, 	$myMode);

			}
		}
	}


	private static function conflict()
	{
		$sura = \dash\file::read(root. 'content_api/v6/sura/sura.json');
		$sura = json_decode($sura, true);

		$conflict = [];

		foreach ($sura as $sura_index => $value)
		{
			$conflict['s'. $sura_index] = ['by' => 'p'. $value['startpage'], 'not_confilicat_in_mode' => ['aye']];
		}

	}


	private static function quran_link($_filename, $_url_key, $_count, $_priority, $_changefreq, $_lastmodify , $_lang, $_mode)
	{
		$filename = 'quran'. $_filename;

		$myLang = null;
		if($_lang)
		{
			$myLang = $_lang. '/';
			$filename .= '-'. $_lang;
		}

		$get_url = [];
		if($_mode)
		{
			$get_url['mode'] = $_mode;
			$filename .= '-'. $_mode;
		}

		if($get_url)
		{
			$get_url = '?'. http_build_query($get_url);
		}
		else
		{
			$get_url = null;
		}

		$sitemap  = \dash\utility\sitemap::new_sitemap();

		$sitemap->setFilename($filename);

		for ($i=1; $i <= $_count ; $i++)
		{
			$myUrl = $myLang. $_url_key. $i. $get_url;

			$sitemap->addItem($myUrl, $_priority, $_changefreq, $_lastmodify);
		}

		$sitemap->endSitemap();

		\dash\utility\sitemap::set_result($filename, $_count);
	}




	private static function aya($_lang = null, $_translate = null, $_mode = null)
	{
		$filename = 'quranaya';
		$myLang = null;
		if($_lang)
		{
			$myLang = $_lang. '/';
			$filename .= '-'. $_lang;
		}

		$filename .= '-'. $_translate;

		$get_url = [];
		$get_url['t'] = $_translate;

		if($_mode)
		{
			$get_url['mode'] = $_mode;
			$filename .= '-'. $_mode;
		}

		if($get_url)
		{
			$get_url = '?'. http_build_query($get_url);
		}
		else
		{
			$get_url = null;
		}


		$sitemap  = \dash\utility\sitemap::new_sitemap();

		$sitemap->setFilename($filename);

		for ($i=1; $i <= 6236 ; $i++)
		{
			$myUrl = $myLang. 'a'.$i. $get_url;

			$sitemap->addItem($myUrl, '0.7', 'monthly', null);
		}

		$sitemap->endSitemap();

		\dash\utility\sitemap::set_result($filename, 6236);
	}



}
?>