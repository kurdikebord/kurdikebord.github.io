<?php
namespace lib\app;


class qari
{
	// 26 qari
	public static function get_by_slug($_slug, $_key = null)
	{
		$list =
		[
			'sahih_international' => ['name' => T_("Sahih international"), 			'country' => 'US'],
			'saeediyan'           => ['name' => T_("Hossein Saeediyan"), 			'country' => 'IR'],
			'sabzali'             => ['name' => T_("Mohammad Hossein Sabzali"), 	'country' => 'IR'],
			'foladvand'           => ['name' => T_('Mohammad mahdi foladvand'), 	'country' => 'IR'],
			'azerbaijani'         => ['name' => T_("Azerbaijani"), 					'country' => 'AZ'],
			'ansarian'            => ['name' => T_("Hossein Ansarian"), 			'country' => 'IR'],
			'khalafi'             => ['name' => T_('Hannaneh Khalafi'), 			'country' => 'IR'],

			'ibrahim_walk'        => ['name' => T_("Ibrahim Walk"), 'country' => 'US'],
			'kabiri'              => ['name' => T_("kabiri"), 'country' => 'IR'],
			'hedayatfar'          => ['name' => T_("hedayatfar"), 'country' => 'IR'],
			'salimi'              => ['name' => T_("salimi"), 'country' => 'IR'],

			'muhammad_jibreel'    => ['name' => T_('Muhammad jibreel'), 			'country' => 'EG'],
			'mustafa_ismail'      => ['name' => T_('Mustafa Ismail'), 				'country' => 'EG'],
			'abdulbasit'          => ['name' => T_('AbdulBaset AbdulSamad'), 		'country' => 'EG'],
			'afasy'               => ['name' => T_('Mishary Rashid Alafasy'), 		'country' => 'KW'],
			'husary'              => ['name' => T_('Mahmoud Khalil Al-Husary'), 	'country' => 'EG'],
			'minshawi'            => ['name' => T_('Mohamed Siddiq al-Minshawi'), 	'country' => 'EG'],
			'rifai'               => ['name' => T_('Hani ar-Rifai'), 				'country' => 'SA'],
			'shatri'              => ['name' => T_('Abu Bakr al-Shatri'), 			'country' => 'SA'],
			'shuraym'             => ['name' => T_('Sa`ud ash-Shuraym'), 			'country' => 'SA'],
			'sudais'              => ['name' => T_('Abdur-Rahman as-Sudais'), 		'country' => 'SA'],
			'balayev'             => ['name' => T_('Rasim Balayev'), 				'country' => null],
			'ibrahimwalk'         => ['name' => T_('Ibrahim Walk'), 				'country' => null],
			'parhizgar'           => ['name' => T_('Shahriyar parhizgar'), 			'country' => 'IR'],
			'mansouri'            => ['name' => T_('Karim mansouri'), 				'country' => 'IR'],
			'qaraati'             => ['name' => T_('Mohsen Qaraati'), 				'country' => 'IR'],

			'makarem'             => ['name' => T_('Naser makarem shirazi'), 		'country' => 'IR'],
		];

		if(isset($list[$_slug]))
		{
			if($_key)
			{
				if(isset($list[$_slug][$_key]))
				{
					return $list[$_slug][$_key];
				}
				else
				{
					return null;
				}
			}
			else
			{
				return $list[$_slug];
			}
		}
		return null;
	}


	public static function get_aya_audio($_sura, $_aya, $_meta = [], $_get_key = false)
	{
		if(!isset($_meta['qari']))
		{
			$_meta['qari'] = 1;
		}

		if(!ctype_digit($_meta['qari']))
		{
			$_meta['qari'] = 1;
		}

		$get_url = self::get_aya_url($_meta['qari'], $_sura, $_aya, $_get_key);
		return $get_url;
	}


	public static function qari_image($_slug)
	{
		$url = \dash\url::site(). '/static/images/qariyan/';
		$url .= $_slug. '.png';
		return $url;
	}

	private static function master_path()
	{
		return 'https://dl.salamquran.com/ayat/';
	}

	public static function list()
	{
		$Mujawwad   =	T_('Mujawwad');
		$Murattal   = T_('Murattal');
		$Translate  = T_('Translate');
		$Commentary = T_('Commentary');
		$Muallim    = T_('Muallim');

		$list =
		[
			// ----------------- abdoabaset
			// ['index' => 1000, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'abdulbasit-mujawwad-128/', 'slug'  => 'abdulbasit', 'name' => self::get_by_slug('abdulbasit', 'name'), 'default' => false],
			// ['index' => 1001, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'abdulbasit-murattal-192/', 'slug'  => 'abdulbasit', 'name' => self::get_by_slug('abdulbasit', 'name'), ],
			['index' => 1001, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'abdulbasit-murattal-32/', 'slug'  => 'abdulbasit', 'name' => self::get_by_slug('abdulbasit', 'name'), ],
			['index' => 1000, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'abdulbasit-mujawwad-32/', 'slug'  => 'abdulbasit', 'name' => self::get_by_slug('abdulbasit', 'name'), 'default' => false],

			// ----------------- afasy
			['index' => 1020, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'afasy-murattal-192/', 'slug'  => 'afasy', 'name' => self::get_by_slug('afasy', 'name'), 'default' => true],

			// ----------------- husary
			// ['index' => 1030, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'husary-murattal-128/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'),],
			// ['index' => 1031, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'husary-mujawwad-128/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'),],
			// ['index' => 1032, 'lang' => 'ar', 'type' => $Muallim, 'addr'  => 'husary-muallim-128/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'), ],
			['index' => 1030, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'husary-murattal-32/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'),],
			['index' => 1031, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'husary-mujawwad-32/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'),],
			['index' => 1032, 'lang' => 'ar', 'type' => $Muallim, 'addr'  => 'husary-muallim-16/', 'slug'  => 'husary', 'name' => self::get_by_slug('husary', 'name'), ],

			// ----------------- minshawi
			// ['index' => 1040, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'minshawi-murattal-128/', 'slug'  => 'minshawi', 'name' => self::get_by_slug('minshawi', 'name'),],
			// ['index' => 1041, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'minshawi-mujawwad-128/', 'slug'  => 'minshawi', 'name' => self::get_by_slug('minshawi', 'name'),],
			['index' => 1041, 'lang' => 'ar', 'type' => $Mujawwad, 'addr'  => 'minshawi-mujawwad-32/', 'slug'  => 'minshawi', 'name' => self::get_by_slug('minshawi', 'name'),],
			['index' => 1040, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'minshawi-murattal-32/', 'slug'  => 'minshawi', 'name' => self::get_by_slug('minshawi', 'name'),],
			['index' => 1042, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'minshawi-withchild-32/', 'slug'  => 'minshawi', 'name' => self::get_by_slug('minshawi', 'name'),],

			// ----------------- rifai
			// ['index' => 1050, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'rifai-murattal-192/', 'slug'  => 'rifai', 'name' => self::get_by_slug('rifai', 'name'),],
			['index' => 1050, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'rifai-murattal-32/', 'slug'  => 'rifai', 'name' => self::get_by_slug('rifai', 'name'),],

			// ----------------- shatri
			// ['index' => 1060, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'shatri-murattal-128/', 'slug'  => 'shatri', 'name' => self::get_by_slug('shatri', 'name'),],

			// ----------------- shuraym
			// ['index' => 1070, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'shuraym-murattal-128/', 'slug'  => 'shuraym', 'name' => self::get_by_slug('shuraym', 'name'),],

			// ----------------- sudais
			// ['index' => 1080, 'lang' => 'ar', 'type' => $Murattal, 'addr'  => 'sudais-murattal-192/', 'slug'  => 'sudais', 'name' => self::get_by_slug('sudais', 'name'),],

			// ----------------- trnaslate - az - balayev
			['index' => 1081, 'lang' => 'az', 'type' => $Translate, 'addr'  => 'azerbaijani.az.balayev-translation-128/', 'slug'  => 'balayev', 'name' => self::get_by_slug('balayev', 'name'),],

			// ----------------- trnaslate - en - ibrahimwalk
			['index' => 1082, 'lang' => 'en', 'type' => $Translate, 'addr'  => 'sahih_international.en.ibrahim_walk-translation-32/', 'slug'  => 'ibrahimwalk', 'name' => self::get_by_slug('ibrahimwalk', 'name'),],

			// ----------------- parhizgar
			['index' => 1090, 'lang' => 'fa', 'type' => $Murattal, 'addr'  => 'parhizgar-murattal-48/', 'slug'  => 'parhizgar', 'name' => self::get_by_slug('parhizgar', 'name')],

			// ----------------- mansouri
			['index' => 1091, 'lang' => 'fa', 'type' => $Murattal, 'addr'  => 'mansouri-murattal-40/', 'slug'  => 'mansouri', 'name' => self::get_by_slug('mansouri', 'name'), ],
			['index' => 1092, 'lang' => 'fa', 'type' => $Murattal, 'addr' => 'sabzali-murattal-32/', 'slug' => 'sabzali', 'name' => self::get_by_slug('sabzali', 'name'),],

			['index' => 1093, 'lang' => 'fa', 'type' => $Murattal, 'addr' => 'khalafi-murattal-192/', 'slug' => 'khalafi', 'name' => self::get_by_slug('khalafi', 'name'),],

			// ----------------- trnaslate - fa - qeraati
			['index' => 1086, 'lang' => 'fa', 'type' => $Commentary, 'addr'  => 'qaraati.fa.qaraati-tafsir-16/', 'slug'  => 'qaraati', 'name' => self::get_by_slug('qaraati', 'name'), 'default_lang' => false],
			['index' => 1087, 'lang' => 'fa', 'type' => $Translate, 'addr' => 'ansarian.fa.salimi-translation-16/', 'slug' => 'ansarian', 'name' => self::get_by_slug('ansarian', 'name'),],

			// ----------------- trnaslate - fa - foladvand
			['index' => 1083, 'lang' => 'fa', 'type' => $Translate, 'addr'  => 'foladvand.fa.hedayatfar-translation-40/', 'slug'  => 'foladvand', 'name' => self::get_by_slug('foladvand', 'name'), ],


			// ----------------- trnaslate - fa - makarem
			['index' => 1084, 'lang' => 'fa', 'type' => $Translate, 'addr'  => 'makarem.fa.kabiri-translation-16/', 'slug'  => 'makarem', 'name' => self::get_by_slug('makarem', 'name'), ],

			['index' => 1101, 'lang' => 'ar', 'type' => $Murattal, 'addr' => 'muhammad_jibreel-murattal-32/', 'slug' => 'muhammad_jibreel', 'name' => self::get_by_slug('muhammad_jibreel', 'name'),],
			['index' => 1102, 'lang' => 'ar', 'type' => $Murattal, 'addr' => 'mustafa_ismail-murattal-32/', 'slug' => 'mustafa_ismail', 'name' => self::get_by_slug('mustafa_ismail', 'name'),],
			['index' => 1103, 'lang' => 'ar', 'type' => $Murattal, 'addr' => 'saeediyan-murattal-16/', 'slug' => 'saeediyan', 'name' => self::get_by_slug('saeediyan', 'name'),],


		];

		return $list;
	}

	private static function ready($_data)
	{
		$get                 = \dash\request::get();
		unset($get['autoplay']);
		$get['qari']         = $_data['index'];
		$_data['url']        = \dash\url::that(). '?'. http_build_query($get);
		$_data['addr']       = self::master_path(). $_data['addr'];
		$_data['image']      = self::qari_image($_data['slug']);
		$_data['short_name'] = T_($_data['slug']);
		return $_data;
	}

	public static function site_list()
	{
		$list         = self::list();
		$list         = array_map(['self', 'ready'], $list);
		$current_lang = \dash\language::current();
		$lang_list    = [];
		$all_list     = [];

		foreach ($list as $key => $value)
		{
			if(isset($value['lang']) && $value['lang'] === $current_lang)
			{
				$lang_list[] = $value;
			}
			else
			{
				$all_list[] = $value;
			}
		}

		$site_list = array_merge($lang_list, $all_list);

		return $site_list;
	}

	public static function load($_id)
	{
		if(!$_id || !ctype_digit($_id))
		{
			$_id = 1;
		}

		$list    = self::list();

		$current_lang = \dash\language::current();
		$default_lang = null;
		$default      = null;
		$load         = null;

		foreach ($list as $key => $value)
		{
			if(intval($value['index']) === intval($_id))
			{
				$load = $value;
			}

			if(isset($value['default_lang']) && $value['default_lang'] && isset($value['lang']) && $value['lang'] === $current_lang)
			{
				$default_lang = $value;
			}

			if(isset($value['default']) && $value['default'])
			{
				$default = $value;
			}
		}

		if(!$load)
		{
			if(!$default_lang)
			{
				$load = $default;
			}
			else
			{
				$load = $default_lang;
			}
		}

		$load = self::ready($load);

		return $load;
	}


	public static function get_aya_url($_gari, $_sura, $_aya, $_get_key = false)
	{

		$_sura = intval($_sura);
		$_aya  = intval($_aya);

		if($_sura < 10)
		{
			$_sura = '00'. $_sura;
		}
		elseif($_sura < 100)
		{
			$_sura = '0'. $_sura;
		}

		if($_aya < 10)
		{
			$_aya = '00'. $_aya;
		}
		elseif($_aya < 100)
		{
			$_aya = '0'. $_aya;
		}

		if($_get_key)
		{
			$key = $_sura. '_'. $_aya;
			return $key;
		}
		else
		{
			$load = self::load($_gari);


			if(isset($load['addr']))
			{
				$addr = $load['addr'];

				if($load['slug'] === 'qaraati')
				{
					$qeraati = \lib\app\qeraati_audio::get($_sura. $_aya);
					if($qeraati)
					{
						$url = $addr. $qeraati. '.mp3';
					}
					else
					{
						$url = null;
					}
				}
				else
				{
					$url = $addr. $_sura. $_aya. '.mp3';
				}
				return $url;
			}
			else
			{
				return false;
			}

		}
	}
}
?>