<?php
namespace lib\app\quran;


class page
{

	public static function load($_type, $_id, $_aya, $_meta, $_war_detail = null)
	{
		$first_page = null;
		$endpage   = null;
		$mode      = $_meta['mode'];

		if(!$_war_detail)
		{
			switch ($_type)
			{
				case 'sura':
					if(!$_aya)
					{
						$first_page = self::find_first_page($_type, $_id);
					}
					else
					{
						$first_page = self::find_first_page($_type, $_id, $_aya);
					}
					break;

				case 'juz':
				case 'hizb':
				case 'rub':
				case 'nim':
					$first_page = self::find_first_page($_type, $_id);
					break;

				case 'page':
					$first_page = intval($_id);
					break;

				case 'aya':
					$first_page = self::find_first_page('index', $_id, $_aya);
					break;


				default:
					return false;
					break;
			}

			$page1 = null;
			$page2 = null;

			if($first_page % 2 === 0)
			{
				$page1 = $first_page - 1;
				$page2 = $first_page;
			}
			else
			{
				$page1 = $first_page;
				$page2 = $first_page + 1;
			}

			$get_db_record_quran = [];

			if($mode === 'onepage' || $mode === 'translatepage' || !$mode)
			{
				$get_db_record_quran['page'] = $first_page;
			}
			elseif($mode === 'twopage')
			{
				if($page1 && $page2)
				{
					$get_db_record_quran['1.1'] = ['= 1.1 AND', " `page` IN ($page1, $page2)"];
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}


			$load             = \lib\db\quran_word::get($get_db_record_quran);
			$load_quran_aya   = \lib\db\quran::get($get_db_record_quran);

		}
		else
		{
			// quran is loade before and use in this function
			$load = $_war_detail;
		}


		$page1_classname = null;
		$page2_classname = null;

		if(!$load || !is_array($load))
		{
			$load = [];
		}

		$translatePage     = [];
		$showTranslatePage = [];

		if($mode === 'translatepage')
		{
			$translatePage = \lib\app\quran\translate::load($load, $_meta);
		}

		// use in translatepage to load aya of this aya
		$page1_aya_list     = [];

		$quran              = [];
		$quran['page1']     = [];
		$quran['page2']     = [];

		$first_verse        = [];
		$text_raw           = [];
		$text_raw['text']   = [];
		$text_raw['simple'] = [];
		$mag_detail         = [];
		$check_sura         = 0;
		$check_line         = 0;

		foreach ($load as $key => $value)
		{
			$myKey      = 'line';
			$myArrayKey = $value['sura']. '_'. $value['line'];
			if($mode === 'onepage' || $mode === 'translatepage' || !$mode)
			{
				$myPageKey = 'page1';
			}
			elseif($mode === 'twopage')
			{
				$myPageKey = intval($value['page']) === $page1 ? 'page1' : 'page2';
			}

			if($myPageKey === 'page1' && !$page1_classname)
			{
				$page1_classname = 'p'. $value['page'];
			}

			if($myPageKey === 'page2' && !$page2_classname)
			{
				$page2_classname = 'p'. $value['page'];
			}

			if($check_sura === 0)
			{
				$check_sura = intval($value['sura']);
			}

			if(intval($value['sura']) !== $check_sura)
			{
				$check_sura = intval($value['sura']);

				if($check_line === 13)
				{
					// load besmellah and next sura detail
					$sura_detail = \lib\app\sura::detail($check_sura);
					$quran[$myPageKey][$myKey][$check_sura. '_14']['detail'] = array_merge(['line_type' => 'start_sura', ], $sura_detail);
					$quran[$myPageKey][$myKey][$check_sura. '_15']['detail'] = self::besmellah(15);

					$showTranslatePage[$sura_detail['index']. '_00'] = array_merge(['line_type' => 'start_sura', 'line' => 14], $sura_detail);
					$showTranslatePage[$sura_detail['index']. '_0'] = self::besmellah_trans($_meta);

				}
				elseif($check_line === 14)
				{
					// load next sura detail
					$sura_detail = \lib\app\sura::detail($check_sura);
					$quran[$myPageKey][$myKey][$check_sura. '_15']['detail'] = array_merge(['line_type' => 'start_sura', ], $sura_detail);

					$showTranslatePage[$sura_detail['index']. '_0'] = array_merge(['line_type' => 'start_sura', 'line' => 14], $sura_detail);

				}
				else
				{
					$sura_detail = \lib\app\sura::detail($check_sura);
					$quran[$myPageKey][$myKey][$check_sura. '_'. (string) ($check_line + 1)]['detail'] = array_merge(['line_type' => 'start_sura', ], $sura_detail);
					$quran[$myPageKey][$myKey][$check_sura. '_'. (string) ($check_line + 2)]['detail'] = self::besmellah($check_line + 2);

					$showTranslatePage[$sura_detail['index']. '_00'] = array_merge(['line_type' => 'start_sura', 'line' => $check_line + 1], $sura_detail);
					$showTranslatePage[$sura_detail['index']. '_0'] = self::besmellah_trans($_meta);
				}
			}

			if(!$check_line)
			{
				$check_line = intval($value['line']);

				if($check_line === 1)
				{
					// nothing
				}
				elseif($check_line === 2)
				{
					// in fatiha sura needless to load besmellah
					if($check_sura !== 1)
					{
						// load besmellah
						$quran[$myPageKey][$myKey][$value['sura']. '_1']['detail'] = self::besmellah(1);
						$showTranslatePage[$value['sura']. '_0'] = self::besmellah_trans($_meta);

					}
					else
					{
						// load fatiha sura detail
						$sura_detail = \lib\app\sura::detail(1);
						$quran[$myPageKey][$myKey][$value['sura']. '_1']['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 1 ], $sura_detail);
						$showTranslatePage[$value['sura']. '_0'] = self::besmellah_trans($_meta);
					}

				}
				elseif($check_line === 3)
				{
					// load sura title and besmellah
					$sura_detail = \lib\app\sura::detail($value['sura']);
					$quran[$myPageKey][$myKey][$value['sura']. '_1']['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 1 ], $sura_detail);
					$quran[$myPageKey][$myKey][$value['sura']. '_2']['detail'] = self::besmellah(2);
					$showTranslatePage[$value['sura']. '_00'] = array_merge(['line_type' => 'start_sura', 'line' => 1 ], $sura_detail);
					$showTranslatePage[$value['sura']. '_0'] = self::besmellah_trans($_meta);
				}
			}
			else
			{
				$check_line = intval($value['line']);
			}



			if(!isset($quran[$myPageKey][$myKey][$myArrayKey]['detail']))
			{
				$quran_aya_key = $value['sura']. '_'. $value['aya'];

				$verse_title = null;
				$verse_title .= T_("Quran");
				$verse_title .= ' - ';
				$verse_title .= T_("Sura");
				$verse_title .= ' ';
				$verse_title .= \dash\utility\human::fitNumber($value['sura']). ' '. T_(\lib\app\sura::detail($value['sura'], 'tname'));
				$verse_title .= ' - ';
				$verse_title .= T_("Aya");
				$verse_title .= ' ';
				$verse_title .= \dash\utility\human::fitNumber($value['aya']);

				$verse_url = \dash\url::kingdom();
				$verse_url .= '/s'. $value['sura'];
				$verse_url .= '/'. $value['aya'];

				if(!$first_verse)
				{
					$first_verse['title'] = $verse_title;
					$first_verse['url']   = $verse_url. \lib\app\quran::url_query();
					$first_verse['audio'] = \lib\app\qari::get_aya_audio($value['sura'], $value['aya'], $_meta);
					$first_verse['juz']   = $value['juz'];
					$first_verse['page']  = $value['page'];
					$first_verse['sura']  = $value['sura'];
					$first_verse['index'] = isset($value['index']) ? $value['index'] : null;
				}

				$temp_translate = \lib\app\quran\translate::get_translation($value['sura'], $value['aya'], $_meta);
				$quran[$myPageKey][$myKey][$myArrayKey]['detail'] =
				[
					'index'         => isset($quran_aya[$quran_aya_key]['index']) ? $quran_aya[$quran_aya_key]['index'] : null,
					'text'          => isset($quran_aya[$quran_aya_key]['text']) ? \lib\app\quran::normalize($quran_aya[$quran_aya_key]['text']) : null,
					'simple'        => isset($quran_aya[$quran_aya_key]['simple']) ? $quran_aya[$quran_aya_key]['simple'] : null,
					'juz'           => isset($quran_aya[$quran_aya_key]['juz']) ? $quran_aya[$quran_aya_key]['juz'] : null,
					'hizb'          => isset($quran_aya[$quran_aya_key]['hizb']) ? $quran_aya[$quran_aya_key]['hizb'] : null,
					'word'          => isset($quran_aya[$quran_aya_key]['word']) ? $quran_aya[$quran_aya_key]['word'] : null,
					'sajdah'        => isset($quran_aya[$quran_aya_key]['sajdah']) ? $quran_aya[$quran_aya_key]['sajdah'] : null,
					'sajdah_number' => isset($quran_aya[$quran_aya_key]['sajdah_number']) ? $quran_aya[$quran_aya_key]['sajdah_number'] : null,
					'rub'           => isset($quran_aya[$quran_aya_key]['rub']) ? $quran_aya[$quran_aya_key]['rub'] : null,
					'word'          => isset($quran_aya[$quran_aya_key]['word']) ? $quran_aya[$quran_aya_key]['word'] : null,
					'aya'           => $value['aya'],
					'sura'          => $value['sura'],
					'verse_key'     => $value['verse_key'],
					'verse_title'   => $verse_title,
					'verse_url'     => $verse_url,
					'page'          => $value['page'],
					'line_type'     => 'line',
					'line'          => intval($value['line']),
					'audio'         => \lib\app\qari::get_aya_audio($value['sura'], $value['aya'], $_meta),
					'translate'     => $temp_translate,
				];

				$text_raw['text'][]   = $quran[$myPageKey][$myKey][$myArrayKey]['detail']['text'];
				$text_raw['simple'][] = $quran[$myPageKey][$myKey][$myArrayKey]['detail']['simple'];

				$mag_detail[] =
				[
					'page' => $quran[$myPageKey][$myKey][$myArrayKey]['detail']['page'],
					'aya'  => $quran[$myPageKey][$myKey][$myArrayKey]['detail']['aya'],
					'sura' => $quran[$myPageKey][$myKey][$myArrayKey]['detail']['sura'],
				];

				if(!isset($showTranslatePage[$value['sura']. '_'. $value['aya']]))
				{
					$showTranslatePage[$value['sura']. '_'. $value['aya']] = $temp_translate;
				}
			}

			if(!isset($quran[$myPageKey][$myKey][$myArrayKey]['word']))
			{
				$quran[$myPageKey][$myKey][$myArrayKey]['word'] = [];
			}
			if(isset($value['audio']))
			{
				$my_sura = intval($value['sura']);

				if($my_sura < 10)
				{
					$my_sura = '00'. $my_sura;
				}
				elseif($my_sura < 100)
				{
					$my_sura = '0'. $my_sura;
				}

				$value['audio'] = $my_sura. $value['audio'];
			}

			if(isset($value['text']))
			{
				$value['text'] = \lib\app\quran::normalize($value['text']);
			}

			if(isset($value['audio']))
			{
				$audio_key = $value['audio'];

				$value['audio_key'] = substr($audio_key, 4, 11);
			}

			if(isset($value['char_type']) && $value['char_type'] === 'end')
			{
				$value = \lib\app\quran::load_aya_detail($value, $_meta);
			}

			$quran[$myPageKey][$myKey][$myArrayKey]['word'][] = $value;

		}

		$count_page1 = 0;
		if(isset($quran['page1']['line']))
		{
			$count_page1 = count($quran['page1']['line']);
		}

		if($count_page1 === 13)
		{
			$end_sura_key  = end($quran['page1']['line']);
			$end_sura_key  = $end_sura_key['detail']['sura'];
			$next_sura_key = intval($end_sura_key) + 1;
			// load sura title and besmellah
			$sura_detail = \lib\app\sura::detail($next_sura_key);
			$quran['page1']['line']["{$next_sura_key}_14"]['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 14], $sura_detail);
			$quran['page1']['line']["{$next_sura_key}_15"]['detail'] = self::besmellah(15);

			$showTranslatePage[$sura_detail['index']. '_00'] = array_merge(['line_type' => 'start_sura', 'line' => 14], $sura_detail);
			$showTranslatePage[$sura_detail['index']. '_0'] = self::besmellah_trans($_meta);

		}
		elseif($count_page1 === 14)
		{
			$end_sura_key  = end($quran['page1']['line']);
			$end_sura_key  = $end_sura_key['detail']['sura'];
			$next_sura_key = intval($end_sura_key) + 1;
			// load sura title and besmellah
			$sura_detail = \lib\app\sura::detail($next_sura_key);
			$quran['page1']['line']["{$next_sura_key}_15"]['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 15], $sura_detail);

			$showTranslatePage[$sura_detail['index']. '_0'] = array_merge(['line_type' => 'start_sura', 'line' => 14], $sura_detail);


		}

		if(isset($quran['page2']['line']))
		{
			$count_page2 = count($quran['page2']['line']);
			if($count_page2 === 13)
			{
				$end_sura_key  = end($quran['page2']['line']);
				$end_sura_key  = $end_sura_key['detail']['sura'];
				$next_sura_key = intval($end_sura_key) + 1;
				// load sura title and besmellah
				$sura_detail = \lib\app\sura::detail($next_sura_key);
				$quran['page2']['line']["{$next_sura_key}_14"]['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 14 ], $sura_detail);
				$quran['page2']['line']["{$next_sura_key}_15"]['detail'] = self::besmellah(15);

			}
			elseif($count_page2 === 14)
			{
				$end_sura_key  = end($quran['page2']['line']);
				$end_sura_key  = $end_sura_key['detail']['sura'];
				$next_sura_key = intval($end_sura_key) + 1;
				// load sura title and besmellah
				$sura_detail = \lib\app\sura::detail($next_sura_key);
				$quran['page2']['line']["{$next_sura_key}_15"]['detail'] = array_merge(['line_type' => 'start_sura', 'line' => 15], $sura_detail);

			}
		}

		$result['translatepage'] = $showTranslatePage;
		if(isset($quran['page1']['line']) && is_array($quran['page1']['line']))
		{
			$quran['page1']['line'] = array_values($quran['page1']['line']);
		}

		if(isset($quran['page2']['line']) && is_array($quran['page2']['line']))
		{
			$quran['page2']['line'] = array_values($quran['page2']['line']);
		}
		$result['text']    = $quran;

		if($mode === 'onepage' || $mode === 'translatepage' || !$mode)
		{
			$next_page = intval($first_page) + 1;
			$prev_page = intval($first_page) - 1;

			if($next_page > 604)
			{
				$next_page = null;
			}

			if($prev_page < 1)
			{
				$prev_page = null;
			}

			$quran_detail              = [];
			$quran_detail['beginning'] = ['title' => T_("Beginning of page"), 'link' => \dash\url::that(). \lib\app\quran::url_query()];

			if($next_page)
			{
				$quran_detail['next'] =
				[
					'index'    => $next_page,
					'url'      => \dash\url::kingdom(). '/p'. $next_page. \lib\app\quran::url_query(true),
					'title'    => T_("Next page"),
					'subtitle' => T_('page') . ' '. \dash\utility\human::fitNumber($next_page),
				];

				// next auto play url
				$quran_detail['nextAuto'] = $quran_detail['next'];

				$appendAutoPlay = '';

				if(strpos($quran_detail['nextAuto']['url'], '?'))
				{
					if(strpos($quran_detail['nextAuto']['url'], 'autoplay=1'))
					{
						// do noting, have it.
					}
					else
					{
						$appendAutoPlay = '&autoplay=1';
					}
				}
				else
				{
					$appendAutoPlay = '?autoplay=1';
				}

				$quran_detail['nextAuto']['url'] .= $appendAutoPlay;
			}

			if($prev_page)
			{
				$quran_detail['prev'] =
				[
					'index'    => $prev_page,
					'url'      => \dash\url::kingdom(). '/p'. $prev_page. \lib\app\quran::url_query(),
					'title'    => T_("Previous page"),
					'subtitle' => T_('page') . ' '. \dash\utility\human::fitNumber($prev_page),
				];
			}
		}
		elseif($mode === 'twopage')
		{
			$next_page = intval($first_page) + 2;
			$prev_page = intval($first_page) - 1;

			if($next_page > 604)
			{
				$next_page = null;
			}

			if($prev_page < 1)
			{
				$prev_page = null;
			}

			$quran_detail              = [];
			$quran_detail['beginning'] = ['title' => T_("Beginning of page"), 'link' => \dash\url::that(). \lib\app\quran::url_query()];

			if($next_page)
			{
				$quran_detail['next'] =
				[
					'index'    => $next_page,
					'url'      => \dash\url::kingdom(). '/p'. $next_page. \lib\app\quran::url_query(true),
					'title'    => T_("Next page"),
					'subtitle' => T_('page') . ' '. \dash\utility\human::fitNumber($next_page),
				];
			}

			if($prev_page)
			{
				$quran_detail['prev'] =
				[
					'index'    => $prev_page,
					'url'      => \dash\url::kingdom(). '/p'. $prev_page. \lib\app\quran::url_query(),
					'title'    => T_("Previous page"),
					'subtitle' => T_('page') . ' '. \dash\utility\human::fitNumber($prev_page),
				];
			}
		}


		$quran_detail['first_verse'] = $first_verse;

		$quran_detail['page1']['class'] = $page1_classname;
		$quran_detail['page2']['class'] = $page2_classname;


		$result['detail']        = $quran_detail;
		$result['find_by']       = $mode;
		$result['find_type']     = ['by' => $_type, 'id' => $_id];
		$result['mode']          = $_meta['mode'];
		$result['find_id']       = ['page1' => substr($page1_classname, 1), 'page2' => substr($page2_classname, 1)];
		$result['translatelist'] = \lib\app\translate::current_list();
		$result['text_raw']      = $text_raw;
		$result['mag_detail']    = $mag_detail;

		return $result;
	}

	private static function besmellah($_line = null)
	{
		return	['line_type' => 'besmellah', 'line' => $_line];
	}

	private static function besmellah_trans($_meta = null)
	{
		return	['line_type' => 'besmellah'];
	}



	private static function find_first_page($_field, $_value, $_aya = null)
	{
		if(!$_aya)
		{
			$result = \lib\db\quran_word::get([$_field => $_value, 'limit' => 1]);
		}
		else
		{
			$result = \lib\db\quran_word::get([$_field => $_value, 'aya' => $_aya, 'limit' => 1]);
		}

		if(isset($result['page']))
		{
			return intval($result['page']);
		}
		return null;
	}


	public static function page_start_sura_aya($_page)
	{
		$addr = root. 'content_api/v6/page/page.json';
		$get  = \dash\file::read($addr);
		$get  = json_decode($get, true);
		if(isset($get[$_page]))
		{
			return $get[$_page];
		}
		return null;

	}
}
?>