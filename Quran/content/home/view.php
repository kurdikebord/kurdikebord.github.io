<?php
namespace content\home;

class view
{
	public static function config()
	{
		// $title = T_('Quran');
		// $desc  = T_("Say hello to Quran!"). ' '. T_("quran is calling you.");

		self::set_best_title();

		if(\dash\data::sureLoaded())
		{
			$translation_list = \lib\app\translate::translate_site_list();
			\dash\data::translationList($translation_list);

			\dash\data::bodyclass('holyQuran');
		}

		if(\dash\request::get('fixframe'))
		{
			\dash\data::bodyclass(\dash\data::bodyclass() . ' fixframe');
		}

		$list = \lib\app\qari::site_list();
		\dash\data::qariList($list);

		$qariLoaded = \lib\app\qari::load(\dash\request::get('qari'));
		\dash\data::qariLoaded($qariLoaded);

		$readMode = \lib\app\read_mode::site_list();
		\dash\data::readModeList($readMode);

		$readModeLoaded = \lib\app\read_mode::load(\dash\request::get('mode'));
		\dash\data::readModeLoaded($readModeLoaded);

		$fontStyle = \lib\app\font_style::site_list();
		\dash\data::fontStyleList($fontStyle);

		$fontStyleLoaded = \lib\app\font_style::load(\dash\request::get('font'));
		\dash\data::fontStyleLoaded($fontStyleLoaded);

		$pageStyle = 'uthmani';
		if(\dash\request::get('font'))
		{
			switch (\dash\request::get('font'))
			{
				case 'uthmani':
				case 'noorehuda':
				case 'iransans':
					$pageStyle = \dash\request::get('font');
					break;

				default:
					$pageStyle = null;
					break;
			}
		}
		\dash\data::pageStyle($pageStyle);

		\dash\data::zoomInUrl(\lib\app\font_style::zoom_in_url());
		\dash\data::zoomOutUrl(\lib\app\font_style::zoom_out_url());

		if(!\dash\url::directory())
		{
			$doners = \lib\app\donate::last_10_donate();
			\dash\data::lastDoners($doners);
		}
		else
		{
			// check load mag
			$mag_detail = \dash\data::quranLoaded_mag_detail();
			$load_mag = \lib\app\mag::find($mag_detail);
			\dash\data::magLoaded($load_mag);
		}
	}


	private static function find_type_id()
	{
		$quranLoaded_find_type = \dash\data::quranLoaded_find_id();
		if(is_numeric($quranLoaded_find_type))
		{
			return $quranLoaded_find_type;
		}

		$quranLoaded_find_type = \dash\data::quranLoaded_find_type();
		if(isset($quranLoaded_find_type['id']))
		{
			return $quranLoaded_find_type['id'];
		}
	}


	private static function first_character($_type, $_len = 100)
	{
		$first_character = null;
		switch ($_type)
		{
			case 'sura':
			case 'page':
			case 'juz':
			case 'aya':
			default:
				$raw_text = \dash\data::quranLoaded_text_raw();
				if(isset($raw_text['text']))
				{
					$raw_text = $raw_text['text'];
					if(is_array($raw_text))
					{
						$raw_text = implode(' ', $raw_text);
					}
					if(is_string($raw_text))
					{
						$first_character = mb_substr($raw_text, 0, $_len);
					}
				}
				break;
		}
		return (string) $first_character;
	}


	private static function set_best_title()
	{
		$type     = \dash\data::quranLoaded_find_by();
		$find_id  = self::find_type_id();

		$module = \dash\url::module();
		$first = substr($module, 0, 1);

		switch ($first)
		{
			case 'r':  // rub
				self::seo_rub();
				break;

			case 'n':  // nim
				self::seo_nim();
				break;

			case 'h':  // hizb
				self::seo_hizb();
				break;

			case 'a':  // aya
				self::seo_aya();
				break;

			case 'j':  // juz
				self::seo_juz();
				break;

			case 'p':  // page
				self::seo_page();
				break;

			case 's':  // sura
			default:
				if(!\dash\url::directory())
				{
					$title = \dash\data::site_title();
					$desc  = \dash\data::site_desc();
					self::fillDownloadLink();
				}
				else
				{
					self::seo_sura();
					return;
				}
				break;
		}
	}


	private static function seo_rub()
	{
		$find_id  = self::find_type_id();

		// set title
		$title = T_('Rub'). ' '. \dash\utility\human::fitNumber($find_id);

		// set seotitle
		$seotitle = T_('rub'). ' '. \dash\utility\human::fitNumber($find_id) . ' '. T_("quran");
		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('rub', 100). '...';

		$desc .= ' / '. T_("Juz"). ' '. \dash\utility\human::fitNumber(ceil(intval($find_id) / 4));

		$rub_detail = \lib\app\rub::detail($find_id);

		if(isset($rub_detail['startpage']) && isset($rub_detail['endpage']))
		{
			$desc .= ' / '. T_("Page"). ' '. \dash\utility\human::fitNumber($rub_detail['startpage']);
			$desc .= '-'. \dash\utility\human::fitNumber($rub_detail['endpage']);
		}

		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");


		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);
	}


	private static function seo_nim()
	{
		$find_id  = self::find_type_id();
		// set title
		$title = T_('Nim'). ' '. \dash\utility\human::fitNumber($find_id);

		// set seotitle
		$seotitle = T_('Nim'). ' '. \dash\utility\human::fitNumber($find_id) . ' '. T_("quran");
		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('nim', 100). '...';

		$desc .= ' / '. T_("Juz"). ' '. \dash\utility\human::fitNumber(ceil(intval($find_id) / 3));

		$nim_detail = \lib\app\nim::detail($find_id);

		if(isset($nim_detail['startpage']) && isset($nim_detail['endpage']))
		{
			$desc .= ' / '. T_("Page"). ' '. \dash\utility\human::fitNumber($nim_detail['startpage']);
			$desc .= '-'. \dash\utility\human::fitNumber($nim_detail['endpage']);
		}

		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");

		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);
	}


	private static function seo_hizb()
	{
		$find_id  = self::find_type_id();
		// set title
		$title = T_('Hizb'). ' '. \dash\utility\human::fitNumber($find_id);

		// set seotitle
		$seotitle = T_('Hizb'). ' '. \dash\utility\human::fitNumber($find_id) . ' '. T_("quran");
		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('hizb', 100). '...';

		$desc .= ' / '. T_("Juz"). ' '. \dash\utility\human::fitNumber(ceil(intval($find_id) / 2));

		$hizb_detail = \lib\app\hizb::detail($find_id);
		if(isset($hizb_detail['startpage']) && isset($hizb_detail['endpage']))
		{
			$desc .= ' / '. T_("Page"). ' '. \dash\utility\human::fitNumber($hizb_detail['startpage']);
			$desc .= '-'. \dash\utility\human::fitNumber($hizb_detail['endpage']);
		}

		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");

		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);

	}


	private static function seo_aya()
	{
		$find_id  = self::find_type_id();

		$aya_detail = \lib\app\quran\aya::load_one_aya($find_id);

		$title = T_('Aya'). ' '. \dash\utility\human::fitNumber($find_id) . ' '. T_("quran");

		$seotitle = '';
		if(isset($aya_detail['sura']) && isset($aya_detail['aya']))
		{
			$sura_detail = \lib\app\sura::detail($aya_detail['sura']);

			if(isset($sura_detail['tname']))
			{

				$seotitle .= T_('Aya'). ' '. \dash\utility\human::fitNumber($aya_detail['aya']). ' '. T_("surah"). ' '. T_($sura_detail['tname']);
			}
		}

		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('aya', 100). '...';

		if(isset($aya_detail['page']))
		{
			$desc .= ' / '. T_("page"). ' '. \dash\utility\human::fitNumber($aya_detail['page']);
		}

		if(isset($aya_detail['juz']))
		{
			$desc .= ' / '. T_("juz"). ' '. \dash\utility\human::fitNumber($aya_detail['juz']);
		}

		if(isset($aya_detail['index']))
		{
			$desc .= ' / '. T_("aya"). ' '. \dash\utility\human::fitNumber($aya_detail['index']) . ' '. T_("quran");
		}

		$desc .= ' / '. T_("Uthmani font");

		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);
	}

	// private static function seo_onepage()
	// {
	// 	$page  = self::find_type_id();
	// 	$page1 = null;

	// 	if(isset($page['page1']))
	// 	{
	// 		$page1 = $page['page1'];
	// 	}

	// 	self::fillDownloadLink($page1);

	// 	// \dash\data::page_title($title);
	// 	// \dash\data::page_desc($desc);
	// 	// \dash\data::page_seotitle($seotitle);

	// }

	// private static function seo_twopage()
	// {

	// 	$page  = self::find_type_id();
	// 	$page1 = null;
	// 	$page2 = null;
	// 	if(isset($page['page1']))
	// 	{
	// 		$page1 = $page['page1'];
	// 	}

	// 	if(isset($page['page2']))
	// 	{
	// 		$page2 = $page['page2'];
	// 	}

	// 	// if($page1 && $page2)
	// 	// {
	// 	// 	$title = T_('Pages'). ' '. \dash\utility\human::fitNumber($page1). ' '. T_(","). ' '. \dash\utility\human::fitNumber($page2);
	// 	// 	$desc  = T_('Quran'). ' #' . T_('page'). ' '. \dash\utility\human::fitNumber($page1). ' - '. \dash\utility\human::fitNumber($page2);
	// 	// }
	// 	// elseif($page1)
	// 	// {
	// 	// 	$title = T_('Page'). ' '. \dash\utility\human::fitNumber($page1);
	// 	// 	$desc  = T_('Quran'). ' #'. \dash\utility\human::fitNumber($page1). ' '. T_('page');
	// 	// }

	// 	self::fillDownloadLink($page1);


	// 	// \dash\data::page_title($title);
	// 	// \dash\data::page_desc($desc);
	// 	// \dash\data::page_seotitle($seotitle);
	// }


	private static function seo_page()
	{
		$find_id  = self::find_type_id();
		// set title
		$title = T_('Page'). ' '. \dash\utility\human::fitNumber($find_id);

		// set seotitle
		$seotitle = T_('Page'). ' '. \dash\utility\human::fitNumber($find_id);
		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('page', 100). '...';

		$desc .= ' / '. T_("Juz"). ' '. \dash\utility\human::fitNumber(\lib\app\quran::page_juz($find_id));

		$start_page = \lib\app\quran\page::page_start_sura_aya($find_id);
		if(isset($start_page['aya']))
		{
			$desc .= ' / '. \dash\utility\human::fitNumber($start_page['aya']);
		}

		if(isset($start_page['sura']))
		{
			$desc .= ' '. T_(\lib\app\sura::detail($start_page['sura'], 'name'));
		}

		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");

		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);
	}


	private static function seo_juz()
	{
		$find_id  = self::find_type_id();
		// set title
		$title = T_('Juz'). ' '. \dash\utility\human::fitNumber($find_id);

		// set seotitle
		$seotitle = T_('Juz'). ' '. \dash\utility\human::fitNumber($find_id). ' '. T_("quran");
		$seotitle .= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('juz', 100). '...';

		$juz_detail = \lib\app\juz::detail($find_id);
		if(isset($juz_detail['startpage']) && isset($juz_detail['endpage']))
		{
			$desc .= ' / '. T_("Page"). ' '. \dash\utility\human::fitNumber($juz_detail['startpage']);
			$desc .= '-'. \dash\utility\human::fitNumber($juz_detail['endpage']);
		}

		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");

		\dash\data::page_title($title);
		\dash\data::page_desc($desc);
		\dash\data::page_seotitle($seotitle);
	}


	private static function seo_sura()
	{
		// set title
		$title = T_("Sura"). ' '. T_(\dash\data::suraDetail_tname());

		// set seotitle
		$seotitle = T_('Surah'). ' '. T_(\dash\data::suraDetail_tname());
		$seotitle.= ' + '. T_("audio, text, translate & download");

		// set desc
		$desc = self::first_character('sura', 100). '...';

		$desc  .= ' '. \dash\utility\human::fitNumber(\dash\data::suraDetail_ayas()). ' '. T_('ayah');

		$start_page = \lib\app\sura::detail(\dash\data::suraDetail_index(), 'startpage');
		$end_page = \lib\app\sura::detail(\dash\data::suraDetail_index(), 'endpage');

		$desc .= ' / '. T_("Page"). ' '. \dash\utility\human::fitNumber($start_page);
		if(intval($start_page) !== intval($end_page))
		{
			$desc .= '-'. \dash\utility\human::fitNumber($end_page);
		}

		$start_juz = \lib\app\sura::detail(\dash\data::suraDetail_index(), 'startjuz');
		$end_juz = \lib\app\sura::detail(\dash\data::suraDetail_index(), 'endjuz');

		$desc .= ' / '. T_("Juz"). ' '. \dash\utility\human::fitNumber($start_juz);
		if(intval($start_juz) !== intval($end_juz))
		{
			$desc .= '-'. \dash\utility\human::fitNumber($end_juz);
		}

		// add total ayah number
		// add type
		$desc  .= ' / '. T_(\dash\data::suraDetail_type());

		// add translated name
		$desc  .= ' / '. T_(\dash\data::suraDetail_ename());
		// add arabic name
		$desc  .= ' / '. \dash\data::suraDetail_name();
		$desc .= ' / '. T_("Uthmani font + 26 Qari Quran + 112 translations in 43 languages with interpretation and transliteration");

		\dash\data::page_title($title);
		\dash\data::page_seotitle($seotitle);
		\dash\data::page_desc($desc);
	}


	private static function fillDownloadLink($_page = null)
	{
		if($_page === null)
		{
			$page_day = \lib\app\page_day::get();
			if(isset($page_day['page']))
			{
				// page of the day
				$_page = intval($page_day['page']);
			}
			else
			{
				// random page
				$_page = mt_rand(1, 604);
			}
		}

		$imgSrc = str_pad($_page, 3, '0', STR_PAD_LEFT);
		$imgSrc = 'https://dl.salamquran.com/images/v1/page'. $imgSrc. '.png';

		\dash\data::dl_pageNum($_page);
		\dash\data::dl_pageLink(\dash\url::kingdom().'/p'.$_page);
		\dash\data::dl_pageImage($imgSrc);
		\dash\data::dl_pageTitle(T_('Download image of Quran page :val', ['val' => $_page]));
	}
}
?>