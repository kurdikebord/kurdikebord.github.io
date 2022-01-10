<?php
namespace lib\app;


class translate
{
	public static function aya_translate($_lang, $_sura, $_aya)
	{
		$table_name = self::get_default_lang_tablename($_lang);
		if($table_name)
		{
			$result = \lib\db\translate::load($table_name, ['sura' => $_sura, 'aya' => $_aya]);
			if(isset($result[0]))
			{
				return $result[0];
			}
		}
		return null;
	}


	public static function current_list()
	{
		$get       = \dash\request::get();
		unset($get['autoplay']);
		$getTrans  = isset($get['t']) ? $get['t'] : '';
		$getTrans  = explode('-', $getTrans);
		$getTrans  = array_filter($getTrans);
		$getTrans  = array_unique($getTrans);
		if(!$getTrans)
		{
			return null;
		}
		$current_list = [];
		$list         = self::translate_list();

		foreach ($list as $key => $value)
		{
			$myKey = $value['language']. $value['index'];

			if(in_array($myKey, $getTrans))
			{
				$thisGet       = \dash\request::get();
				unset($get['autoplay']);
				$thisGetTrans  = isset($thisGet['t']) ? $thisGet['t'] : '';
				$thisGetTrans  = explode('-', $thisGetTrans);
				$thisGetTrans  = array_filter($thisGetTrans);
				$thisGetTrans  = array_unique($thisGetTrans);
				if(in_array($myKey, $thisGetTrans))
				{
					unset($thisGetTrans[array_search($myKey, $thisGetTrans)]);
				}

				if(!isset($get['mode']) || (isset($get['mode']) && $get['mode'] !== 'aye'))
				{
					$get['mode'] = 'aye';
				}

				$get['t']        = implode('-', $thisGetTrans);

				$url             = \dash\url::that(). '?'. http_build_query($get);

				$value['remove_url'] = $url;

				$current_list[] = $value;
			}
		}

		return $current_list;
	}

	public static function get_translate($_lang)
	{
		$list = self::translate_list();
		$result = [];
		foreach ($list as $key => $value)
		{
			if(isset($value['language']) && $value['language'] === $_lang)
			{
				$result[] = $value;
			}
		}

		return $result;
	}


	// get
	public static function get_default_lang_tablename($_lang = null)
	{
		if(!$_lang)
		{
			$_lang = \dash\language::current();
		}

		$get = self::get_translate($_lang);

		foreach ($get as $key => $value)
		{
			if(isset($value['default']) && $value['default'])
			{
				return $value['table_name'];
			}
		}
		return null;
	}

	// get fa5 for farsi
	public static function get_default_lang_key()
	{
		$get = self::current_lang_translate();

		foreach ($get as $key => $value)
		{
			if(isset($value['default']) && $value['default'])
			{
				return $value['language']. $value['index'];
			}
		}
		return null;
	}

	public static function current_lang_translate()
	{
		return self::get_translate(\dash\language::current());
	}


	public static function table_name($_id)
	{
		$list = self::translate_list();

		foreach ($list as $key => $value)
		{
			if(isset($value['index']) && isset($value['language']))
			{
				if(substr($_id, 0, 2) === $value['language'] && intval(substr($_id, 2)) === $value['index'])
				{
					return $value;
				}
			}
		}
		return false;
	}


	public static function translate_site_list()
	{
		$list      = self::translate_list();
		$site_list = [];
		$language  = array_column($list, 'language');
		$language  = array_filter($language);
		$language  = array_unique($language);
		$language  = array_flip($language);
		$language  = array_map(function(){return [];}, $language);



		foreach ($list as $key => $value)
		{
			$get       = \dash\request::get();
			unset($get['autoplay']);
			$getTrans  = isset($get['t']) ? $get['t'] : '';
			$getTrans  = explode('-', $getTrans);
			$getTrans  = array_filter($getTrans);
			$getTrans  = array_unique($getTrans);
			if(empty($language[$value['language']]))
			{
				$language[$value['language']]['detail'] =
				[
					'language_name' => T_($value['language_name']),
					'flag'          => $value['flag'],
				];

				$language[$value['language']]['list'] = [];
			}

			$thisTransKey = $value['language']. $value['index'];
			if(!in_array($thisTransKey, $getTrans))
			{
				$getTrans[] = $thisTransKey;
			}

			if(!isset($get['mode']) || (isset($get['mode']) && $get['mode'] !== 'aye'))
			{
				$get['mode'] = 'aye';
			}

			$get['t']                       = implode('-', $getTrans);
			$url                            = \dash\url::that(). '?'. http_build_query($get);
			$value['url']                   = $url;

			$language[$value['language']]['list'][] = $value;
		}

		foreach ($language as $key => $value)
		{
			$count = count($value['list']);
			$language[$key]['detail']['count'] = $count;
			if($count > 1)
			{
				$get       = \dash\request::get();
				unset($get['autoplay']);
				// in all link remove all trans
				$getTrans  = '';
				$getTrans  = explode('-', $getTrans);
				$getTrans  = array_filter($getTrans);
				$getTrans  = array_unique($getTrans);

				$index = array_column($value['list'], 'index');
				foreach ($index as $k => $myIndex)
				{
					if(!in_array($key.$myIndex, $getTrans))
					{
						$getTrans[] = $key.$myIndex;
					}
				}

				$get['t']                       = implode('-', $getTrans);
				$all_url                         = \dash\url::that(). '?'. http_build_query($get);

				$language[$key]['detail']['allLink'] = ['title' => T_("Show all translate for this language"), 'link' => $all_url];
			}
		}

		$current = \dash\language::current();
		if(isset($language[$current]))
		{
			$temp           = [];
			$temp[$current] = $language[$current];
			unset($language[$current]);
			$temp           = array_merge($temp, $language);
			$language       = $temp;

		}

		return $language;

	}

	// get all url of language
	// for example: fa1, ar2, en8, ...
	// use in url https://salamquran.com/s1?t=fa1
	public static function all_translate_url()
	{
		$list = self::translate_list();
		$url = [];
		foreach ($list as $key => $value)
		{
			$url[] = $value['language']. $value['index'];
		}

		return $url;
	}

	// 112 translate
	// 43 language
	public static function translate_list()
	{
		$image_addr = \dash\url::site(). '/static/images/qariyan/';

		$translate =
		[
			'am_sadiq'           => [ 'default' => true,  'id' => 1, 'index' => 1, 'language' => 'am', 'name' => 'sadiq', 'image' => null, 'localname' => 'sadiq', 'table_name' => 'am_sadiq', 'flag' => 'et', 'language_name' => 'Amharic', 'local_name' => 'ሳዲቅ ሳኒ ሐቢብ', 'ename' => 'Muhammed Sadiq and Muhammed Sani Habib', ],
			'ar_jalalayn'        => [ 'default' => true,  'id' => 2, 'index' => 1, 'language' => 'ar', 'name' => 'jalalayn', 'image' => null, 'localname' => 'jalalayn', 'table_name' => 'ar_jalalayn', 'flag' => 'sa', 'language_name' => 'Arabic', 'local_name' => 'تفسير الجلالين', 'ename' => 'Jalal ad-Din al-Mahalli and Jalal ad-Din as-Suyuti', ],
			'ar_muyassar'        => [ 'default' => false, 'id' => 3, 'index' => 2, 'language' => 'ar', 'name' => 'muyassar', 'image' => null, 'localname' => 'muyassar', 'table_name' => 'ar_muyassar', 'flag' => 'sa', 'language_name' => 'Arabic', 'local_name' => 'تفسير المیسر', 'ename' => 'King Fahad Quran Complex', ],
			'az_mammadaliyev'    => [ 'default' => true,  'id' => 4, 'index' => 1, 'language' => 'az', 'name' => 'mammadaliyev', 'image' => null, 'localname' => 'mammadaliyev', 'table_name' => 'az_mammadaliyev', 'flag' => 'az', 'language_name' => 'Azerbaijani', 'local_name' => 'Məmmədəliyev Bünyadov', 'ename' => 'Vasim Mammadaliyev and Ziya Bunyadov', ],
			'az_musayev'         => [ 'default' => false, 'id' => 5, 'index' => 2, 'language' => 'az', 'name' => 'musayev', 'image' => null, 'localname' => 'musayev', 'table_name' => 'az_musayev', 'flag' => 'az', 'language_name' => 'Azerbaijani', 'local_name' => 'Musayev', 'ename' => 'Alikhan Musayev', ],
			'be_mensur'          => [ 'default' => true,  'id' => 6, 'index' => 1, 'language' => 'be', 'name' => 'mensur', 'image' => null, 'localname' => 'mensur', 'table_name' => 'be_mensur', 'flag' => 'dz', 'language_name' => 'Amazigh', 'local_name' => 'At Mensur', 'ename' => 'Ramdane At Mansour', ],
			'bg_theophanov'      => [ 'default' => true,  'id' => 7, 'index' => 1, 'language' => 'bg', 'name' => 'theophanov', 'image' => null, 'localname' => 'theophanov', 'table_name' => 'bg_theophanov', 'flag' => 'bg', 'language_name' => 'Bulgarian', 'local_name' => 'Теофанов', 'ename' => 'Tzvetan Theophanov', ],
			'bn_bengali'         => [ 'default' => true,  'id' => 8, 'index' => 1, 'language' => 'bn', 'name' => 'bengali', 'image' => null, 'localname' => 'bengali', 'table_name' => 'bn_bengali', 'flag' => 'bd', 'language_name' => 'Bengali', 'local_name' => 'মুহিউদ্দীন খান', 'ename' => 'Muhiuddin Khan', ],
			'bn_hoque'           => [ 'default' => false, 'id' => 9, 'index' => 2, 'language' => 'bn', 'name' => 'hoque', 'image' => null, 'localname' => 'hoque', 'table_name' => 'bn_hoque', 'flag' => 'bd', 'language_name' => 'Bengali', 'local_name' => 'জহুরুল হক', 'ename' => 'Zohurul Hoque', ],
			'bs_korkut'          => [ 'default' => true,  'id' => 10, 'index' => 1, 'language' => 'bs', 'name' => 'korkut', 'image' => null, 'localname' => 'korkut', 'table_name' => 'bs_korkut', 'flag' => 'ba', 'language_name' => 'Bosnian', 'local_name' => 'Korkut', 'ename' => 'Besim Korkut', ],
			'bs_mlivo'           => [ 'default' => false, 'id' => 11, 'index' => 2, 'language' => 'bs', 'name' => 'mlivo', 'image' => null, 'localname' => 'mlivo', 'table_name' => 'bs_mlivo', 'flag' => 'ba', 'language_name' => 'Bosnian', 'local_name' => 'Mlivo', 'ename' => 'Mustafa Mlivo', ],
			'cs_hrbek'           => [ 'default' => true,  'id' => 12, 'index' => 1, 'language' => 'cs', 'name' => 'hrbek', 'image' => null, 'localname' => 'hrbek', 'table_name' => 'cs_hrbek', 'flag' => 'cz', 'language_name' => 'Czech', 'local_name' => 'Hrbek', 'ename' => 'Preklad I. Hrbek', ],
			'cs_nykl'            => [ 'default' => false, 'id' => 13, 'index' => 2, 'language' => 'cs', 'name' => 'nykl', 'image' => null, 'localname' => 'nykl', 'table_name' => 'cs_nykl', 'flag' => 'cz', 'language_name' => 'Czech', 'local_name' => 'Nykl', 'ename' => 'A. R. Nykl', ],
			'de_aburida'         => [ 'default' => true,  'id' => 14, 'index' => 1, 'language' => 'de', 'name' => 'aburida', 'image' => null, 'localname' => 'aburida', 'table_name' => 'de_aburida', 'flag' => 'de', 'language_name' => 'German', 'local_name' => 'Abu Rida', 'ename' => 'Abu Rida Muhammad ibn Ahmad ibn Rassoul', ],
			'de_bubenheim'       => [ 'default' => false, 'id' => 15, 'index' => 2, 'language' => 'de', 'name' => 'bubenheim', 'image' => null, 'localname' => 'bubenheim', 'table_name' => 'de_bubenheim', 'flag' => 'de', 'language_name' => 'German', 'local_name' => 'Bubenheim Elyas', 'ename' => 'A. S. F. Bubenheim and N. Elyas', ],
			'de_khoury'          => [ 'default' => false, 'id' => 16, 'index' => 3, 'language' => 'de', 'name' => 'khoury', 'image' => null, 'localname' => 'khoury', 'table_name' => 'de_khoury', 'flag' => 'de', 'language_name' => 'German', 'local_name' => 'Khoury', 'ename' => 'Adel Theodor Khoury', ],
			'de_zaidan'          => [ 'default' => false, 'id' => 17, 'index' => 4, 'language' => 'de', 'name' => 'zaidan', 'image' => null, 'localname' => 'zaidan', 'table_name' => 'de_zaidan', 'flag' => 'de', 'language_name' => 'German', 'local_name' => 'Zaidan', 'ename' => 'Amir Zaidan', ],
			'dv_divehi'          => [ 'default' => true,  'id' => 18, 'index' => 1, 'language' => 'dv', 'name' => 'divehi', 'image' => null, 'localname' => 'divehi', 'table_name' => 'dv_divehi', 'flag' => 'mv', 'language_name' => 'Divehi', 'local_name' => 'ދިވެހި', 'ename' => 'Office of the President of Maldives', ],
			'en_ahmedali'        => [ 'default' => false,  'id' => 19, 'index' => 1, 'language' => 'en', 'name' => 'ahmedali', 'image' => null, 'localname' => 'ahmedali', 'table_name' => 'en_ahmedali', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Ahmed Ali', 'ename' => 'Ahmed Ali', ],
			'en_ahmedraza'       => [ 'default' => false, 'id' => 20, 'index' => 2, 'language' => 'en', 'name' => 'ahmedraza', 'image' => null, 'localname' => 'ahmedraza', 'table_name' => 'en_ahmedraza', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Ahmed Raza Khan', 'ename' => 'Ahmed Raza Khan', ],
			'en_arberry'         => [ 'default' => false, 'id' => 21, 'index' => 3, 'language' => 'en', 'name' => 'arberry', 'image' => null, 'localname' => 'arberry', 'table_name' => 'en_arberry', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Arberry', 'ename' => 'A. J. Arberry', ],
			'en_daryabadi'       => [ 'default' => false, 'id' => 22, 'index' => 4, 'language' => 'en', 'name' => 'daryabadi', 'image' => null, 'localname' => 'daryabadi', 'table_name' => 'en_daryabadi', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Daryabadi', 'ename' => 'Abdul Majid Daryabadi', ],
			'en_hilali'          => [ 'default' => false, 'id' => 23, 'index' => 5, 'language' => 'en', 'name' => 'hilali', 'image' => null, 'localname' => 'hilali', 'table_name' => 'en_hilali', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Hilali Khan', 'ename' => 'Muhammad Taqi-ud-Din al-Hilali and Muhammad Muhsin Khan', ],
			'en_itani'           => [ 'default' => false, 'id' => 24, 'index' => 6, 'language' => 'en', 'name' => 'itani', 'image' => null, 'localname' => 'itani', 'table_name' => 'en_itani', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Itani', 'ename' => 'Talal Itani', ],
			'en_maududi'         => [ 'default' => false, 'id' => 25, 'index' => 7, 'language' => 'en', 'name' => 'maududi', 'image' => null, 'localname' => 'maududi', 'table_name' => 'en_maududi', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Maududi', 'ename' => 'Abul Ala Maududi', ],
			'en_mubarakpuri'     => [ 'default' => false, 'id' => 26, 'index' => 8, 'language' => 'en', 'name' => 'mubarakpuri', 'image' => null, 'localname' => 'mubarakpuri', 'table_name' => 'en_mubarakpuri', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Mubarakpuri', 'ename' => 'Safi-ur-Rahman al-Mubarakpuri', ],
			'en_pickthall'       => [ 'default' => false, 'id' => 27, 'index' => 9, 'language' => 'en', 'name' => 'pickthall', 'image' => null, 'localname' => 'pickthall', 'table_name' => 'en_pickthall', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Pickthall', 'ename' => 'Mohammed Marmaduke William Pickthall', ],
			'en_qarai'           => [ 'default' => false, 'id' => 28, 'index' => 10, 'language' => 'en', 'name' => 'qarai', 'image' => null, 'localname' => 'qarai', 'table_name' => 'en_qarai', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Qarai', 'ename' => 'Ali Quli Qarai', ],
			'en_qaribullah'      => [ 'default' => false, 'id' => 29, 'index' => 11, 'language' => 'en', 'name' => 'qaribullah', 'image' => null, 'localname' => 'qaribullah', 'table_name' => 'en_qaribullah', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Qaribullah Darwish', 'ename' => 'Hasan al-Fatih Qaribullah and Ahmad Darwish', ],
			'en_sahih'           => [ 'default' => true, 'id' => 30, 'index' => 12, 'language' => 'en', 'name' => 'sahih', 'image' => null, 'localname' => 'Sahih International', 'table_name' => 'en_sahih', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Saheeh International', 'ename' => 'Saheeh International', ],
			'en_sarwar'          => [ 'default' => false, 'id' => 31, 'index' => 13, 'language' => 'en', 'name' => 'sarwar', 'image' => null, 'localname' => 'sarwar', 'table_name' => 'en_sarwar', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Sarwar', 'ename' => 'Muhammad Sarwar', ],
			'en_shakir'          => [ 'default' => false, 'id' => 32, 'index' => 14, 'language' => 'en', 'name' => 'shakir', 'image' => null, 'localname' => 'shakir', 'table_name' => 'en_shakir', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Shakir', 'ename' => 'Mohammad Habib Shakir', ],
			'en_transliteration' => [ 'default' => false, 'id' => 33, 'index' => 15, 'language' => 'en', 'name' => 'transliteration', 'image' => null, 'localname' => 'transliteration', 'table_name' => 'en_transliteration', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Transliteration', 'ename' => 'English Transliteration', ],
			'en_wahiduddin'      => [ 'default' => false, 'id' => 34, 'index' => 16, 'language' => 'en', 'name' => 'wahiduddin', 'image' => null, 'localname' => 'wahiduddin', 'table_name' => 'en_wahiduddin', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Wahiduddin Khan', 'ename' => 'Wahiduddin Khan', ],
			'en_yusufali'        => [ 'default' => false, 'id' => 35, 'index' => 17, 'language' => 'en', 'name' => 'yusufali', 'image' => null, 'localname' => 'yusufali', 'table_name' => 'en_yusufali', 'flag' => 'us', 'language_name' => 'English', 'local_name' => 'Yusuf Ali', 'ename' => 'Abdullah Yusuf Ali', ],
			'es_bornez'          => [ 'default' => true,  'id' => 36, 'index' => 1, 'language' => 'es', 'name' => 'bornez', 'image' => null, 'localname' => 'bornez', 'table_name' => 'es_bornez', 'flag' => 'es', 'language_name' => 'Spanish', 'local_name' => 'Bornez', 'ename' => 'Raúl González Bórnez', ],
			'es_cortes'          => [ 'default' => false, 'id' => 37, 'index' => 2, 'language' => 'es', 'name' => 'cortes', 'image' => null, 'localname' => 'cortes', 'table_name' => 'es_cortes', 'flag' => 'es', 'language_name' => 'Spanish', 'local_name' => 'Cortes', 'ename' => 'Julio Cortes ', ],
			'es_garcia'          => [ 'default' => false, 'id' => 38, 'index' => 3, 'language' => 'es', 'name' => 'garcia', 'image' => null, 'localname' => 'garcia', 'table_name' => 'es_garcia', 'flag' => 'es', 'language_name' => 'Spanish', 'local_name' => 'Garcia', 'ename' => 'Muhammad Isa García', ],
			'fa_ansarian'        => [ 'default' => false, 'id' => 39, 'index' => 1, 'language' => 'fa', 'name' => 'ansarian', 'image' => null, 'localname' => 'انصاریان', 'table_name' => 'fa_ansarian', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'انصاریان', 'ename' => 'Hussain Ansarian', ],
			'fa_ayati'           => [ 'default' => false, 'id' => 40, 'index' => 2, 'language' => 'fa', 'name' => 'ayati', 'image' => null, 'localname' => 'آیتی', 'table_name' => 'fa_ayati', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'آیتی', 'ename' => 'AbdolMohammad Ayati', ],
			'fa_bahrampour'      => [ 'default' => false, 'id' => 41, 'index' => 3, 'language' => 'fa', 'name' => 'bahrampour', 'image' => null, 'localname' => 'بهرام‌پور', 'table_name' => 'fa_bahrampour', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'بهرام پور', 'ename' => 'Abolfazl Bahrampour', ],
			'fa_fooladvand'      => [ 'default' => false, 'id' => 42, 'index' => 4, 'language' => 'fa', 'name' => 'fooladvand', 'image' => null, 'localname' => 'فولادوند', 'table_name' => 'fa_fooladvand', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'فولادوند', 'ename' => 'Mohammad Mahdi Fooladvand', ],
			'fa_gharaati'        => [ 'default' => true,  'id' => 43, 'index' => 5, 'language' => 'fa', 'name' => 'gharaati', 'image' => $image_addr. 'qaraati.png', 'localname' => 'قرائتی', 'table_name' => 'fa_gharaati', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'قرائتی', 'ename' => 'Mohsen Gharaati', ],
			'fa_ghomshei'        => [ 'default' => false, 'id' => 44, 'index' => 6, 'language' => 'fa', 'name' => 'ghomshei', 'image' => null, 'localname' => 'قمشه‌ای', 'table_name' => 'fa_ghomshei', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'الهی قمشه ای', 'ename' => 'Mahdi Elahi Ghomshei', ],
			'fa_khorramdel'      => [ 'default' => false, 'id' => 45, 'index' => 7, 'language' => 'fa', 'name' => 'khorramdel', 'image' => null, 'localname' => 'خرم‌دل', 'table_name' => 'fa_khorramdel', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'خرمدل', 'ename' => 'Mostafa Khorramdel', ],
			'fa_khorramshahi'    => [ 'default' => false, 'id' => 46, 'index' => 8, 'language' => 'fa', 'name' => 'khorramshahi', 'image' => null, 'localname' => 'خرم‌شاهی', 'table_name' => 'fa_khorramshahi', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'خرمشاهی', 'ename' => 'Baha\'oddin Khorramshahi', ],
			'fa_makarem'         => [ 'default' => false, 'id' => 47, 'index' => 9, 'language' => 'fa', 'name' => 'makarem', 'image' => $image_addr. 'makarem.png', 'localname' => 'مکارم', 'table_name' => 'fa_makarem', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'مکارم شیرازی', 'ename' => 'Naser Makarem Shirazi', ],
			'fa_moezzi'          => [ 'default' => false, 'id' => 48, 'index' => 10, 'language' => 'fa', 'name' => 'moezzi', 'image' => null, 'localname' => 'معزی', 'table_name' => 'fa_moezzi', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'معزی', 'ename' => 'Mohammad Kazem Moezzi', ],
			'fa_mojtabavi'       => [ 'default' => false, 'id' => 49, 'index' => 11, 'language' => 'fa', 'name' => 'mojtabavi', 'image' => null, 'localname' => 'مجتبوی', 'table_name' => 'fa_mojtabavi', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'مجتبوی', 'ename' => 'Sayyed Jalaloddin Mojtabavi', ],
			'fa_sadeqi'          => [ 'default' => false, 'id' => 50, 'index' => 12, 'language' => 'fa', 'name' => 'sadeqi', 'image' => null, 'localname' => 'صادقی', 'table_name' => 'fa_sadeqi', 'flag' => 'ir', 'language_name' => 'Persian', 'local_name' => 'صادقی تهرانی', 'ename' => 'Mohammad Sadeqi Tehrani', ],
			'fr_hamidullah'      => [ 'default' => true,  'id' => 51, 'index' => 1, 'language' => 'fr', 'name' => 'hamidullah', 'image' => null, 'localname' => 'hamidullah', 'table_name' => 'fr_hamidullah', 'flag' => 'fr', 'language_name' => 'French', 'local_name' => 'Hamidullah', 'ename' => 'Muhammad Hamidullah', ],
			'ha_gumi'            => [ 'default' => true,  'id' => 52, 'index' => 1, 'language' => 'ha', 'name' => 'gumi', 'image' => null, 'localname' => 'gumi', 'table_name' => 'ha_gumi', 'flag' => 'ng', 'language_name' => 'Hausa', 'local_name' => 'Gumi', 'ename' => 'Abubakar Mahmoud Gumi', ],
			'hi_farooq'          => [ 'default' => true,  'id' => 53, 'index' => 1, 'language' => 'hi', 'name' => 'farooq', 'image' => null, 'localname' => 'farooq', 'table_name' => 'hi_farooq', 'flag' => 'in', 'language_name' => 'Hindi', 'local_name' => 'फ़ारूक़ ख़ान अहमद', 'ename' => 'Muhammad Farooq Khan and Muhammad Ahmed ', ],
			'hi_hindi'           => [ 'default' => false, 'id' => 54, 'index' => 2, 'language' => 'hi', 'name' => 'hindi', 'image' => null, 'localname' => 'hindi', 'table_name' => 'hi_hindi', 'flag' => 'in', 'language_name' => 'Hindi', 'local_name' => 'फ़ारूक़ ख़ान नदवी', 'ename' => 'Suhel Farooq Khan and Saifur Rahman Nadwi', ],
			'id_indonesian'      => [ 'default' => true,  'id' => 55, 'index' => 1, 'language' => 'id', 'name' => 'indonesian', 'image' => null, 'localname' => 'indonesian', 'table_name' => 'id_indonesian', 'flag' => 'id', 'language_name' => 'Indonesian', 'local_name' => 'Bahasa Indonesia', 'ename' => 'Indonesian Ministry of Religious Affairs', ],
			'id_jalalayn'        => [ 'default' => false, 'id' => 56, 'index' => 2, 'language' => 'id', 'name' => 'jalalayn', 'image' => null, 'localname' => 'jalalayn', 'table_name' => 'id_jalalayn', 'flag' => 'id', 'language_name' => 'Indonesian', 'local_name' => 'Tafsir Jalalayn', 'ename' => 'Jalal ad-Din al-Mahalli and Jalal ad-Din as-Suyuti', ],
			'id_muntakhab'       => [ 'default' => false, 'id' => 57, 'index' => 3, 'language' => 'id', 'name' => 'muntakhab', 'image' => null, 'localname' => 'muntakhab', 'table_name' => 'id_muntakhab', 'flag' => 'id', 'language_name' => 'Indonesian', 'local_name' => 'Quraish Shihab', 'ename' => 'Muhammad Quraish Shihab et al.', ],
			'it_piccardo'        => [ 'default' => true,  'id' => 58, 'index' => 1, 'language' => 'it', 'name' => 'piccardo', 'image' => null, 'localname' => 'piccardo', 'table_name' => 'it_piccardo', 'flag' => 'it', 'language_name' => 'Italian', 'local_name' => 'Piccardo', 'ename' => 'Hamza Roberto Piccardo', ],
			'ja_japanese'        => [ 'default' => true,  'id' => 59, 'index' => 1, 'language' => 'ja', 'name' => 'japanese', 'image' => null, 'localname' => 'japanese', 'table_name' => 'ja_japanese', 'flag' => 'jp', 'language_name' => 'Japanese', 'local_name' => 'Japanese', 'ename' => 'Unknown', ],
			'ko_korean'          => [ 'default' => true,  'id' => 60, 'index' => 1, 'language' => 'ko', 'name' => 'korean', 'image' => null, 'localname' => 'korean', 'table_name' => 'ko_korean', 'flag' => 'kr', 'language_name' => 'Korean', 'local_name' => 'Korean', 'ename' => 'Unknown', ],
			'ku_asan'            => [ 'default' => true,  'id' => 61, 'index' => 1, 'language' => 'ku', 'name' => 'asan', 'image' => null, 'localname' => 'asan', 'table_name' => 'ku_asan', 'flag' => 'iq', 'language_name' => 'Kurdish', 'local_name' => 'ته فسیری ئاسان', 'ename' => 'Burhan Muhammad-Amin', ],
			'ml_abdulhameed'     => [ 'default' => true,  'id' => 62, 'index' => 1, 'language' => 'ml', 'name' => 'abdulhameed', 'image' => null, 'localname' => 'abdulhameed', 'table_name' => 'ml_abdulhameed', 'flag' => 'in', 'language_name' => 'Malayalam', 'local_name' => 'അബ്ദുല്‍ ഹമീദ് പറപ്പൂര്‍, ', 'ename' => 'Cheriyamundam Abdul Hameed and Kunhi Mohammed Parappoor', ],
			'ml_karakunnu'       => [ 'default' => false, 'id' => 63, 'index' => 2, 'language' => 'ml', 'name' => 'karakunnu', 'image' => null, 'localname' => 'karakunnu', 'table_name' => 'ml_karakunnu', 'flag' => 'in', 'language_name' => 'Malayalam', 'local_name' => 'കാരകുന്ന് എളയാവൂര്', 'ename' => 'Muhammad Karakunnu and Vanidas Elayavoor', ],
			'ms_basmeih'         => [ 'default' => true,  'id' => 64, 'index' => 1, 'language' => 'ms', 'name' => 'basmeih', 'image' => null, 'localname' => 'basmeih', 'table_name' => 'ms_basmeih', 'flag' => 'my', 'language_name' => 'Malay', 'local_name' => 'Basmeih', 'ename' => 'Abdullah Muhammad Basmeih', ],
			'nl_keyzer'          => [ 'default' => true,  'id' => 65, 'index' => 1, 'language' => 'nl', 'name' => 'keyzer', 'image' => null, 'localname' => 'keyzer', 'table_name' => 'nl_keyzer', 'flag' => 'nl', 'language_name' => 'Dutch', 'local_name' => 'Keyzer', 'ename' => 'Salomo Keyzer ', ],
			'nl_leemhuis'        => [ 'default' => false, 'id' => 66, 'index' => 2, 'language' => 'nl', 'name' => 'leemhuis', 'image' => null, 'localname' => 'leemhuis', 'table_name' => 'nl_leemhuis', 'flag' => 'nl', 'language_name' => 'Dutch', 'local_name' => 'Leemhuis', 'ename' => 'Fred Leemhuis', ],
			'nl_siregar'         => [ 'default' => false, 'id' => 67, 'index' => 3, 'language' => 'nl', 'name' => 'siregar', 'image' => null, 'localname' => 'siregar', 'table_name' => 'nl_siregar', 'flag' => 'nl', 'language_name' => 'Dutch', 'local_name' => 'Siregar', 'ename' => 'Sofian S. Siregar', ],
			'no_berg'            => [ 'default' => true,  'id' => 68, 'index' => 1, 'language' => 'no', 'name' => 'berg', 'image' => null, 'localname' => 'berg', 'table_name' => 'no_berg', 'flag' => 'no', 'language_name' => 'Norwegian', 'local_name' => 'Einar Berg', 'ename' => 'Einar Berg', ],
			'pl_bielawskiego'    => [ 'default' => true,  'id' => 69, 'index' => 1, 'language' => 'pl', 'name' => 'bielawskiego', 'image' => null, 'localname' => 'bielawskiego', 'table_name' => 'pl_bielawskiego', 'flag' => 'pl', 'language_name' => 'Polish', 'local_name' => 'Bielawskiego', 'ename' => 'Józefa Bielawskiego', ],
			'ps_abdulwali'       => [ 'default' => true,  'id' => 70, 'index' => 1, 'language' => 'ps', 'name' => 'abdulwali', 'image' => null, 'localname' => 'abdulwali', 'table_name' => 'ps_abdulwali', 'flag' => 'af', 'language_name' => 'Pashto', 'local_name' => 'عبدالولي', 'ename' => 'Abdulwali Khan', ],
			'pt_elhayek'         => [ 'default' => true,  'id' => 71, 'index' => 1, 'language' => 'pt', 'name' => 'elhayek', 'image' => null, 'localname' => 'elhayek', 'table_name' => 'pt_elhayek', 'flag' => 'pt', 'language_name' => 'Portuguese', 'local_name' => 'El-Hayek', 'ename' => 'Samir El-Hayek ', ],
			'ro_grigore'         => [ 'default' => true,  'id' => 72, 'index' => 1, 'language' => 'ro', 'name' => 'grigore', 'image' => null, 'localname' => 'grigore', 'table_name' => 'ro_grigore', 'flag' => 'ro', 'language_name' => 'Romanian', 'local_name' => 'Grigore', 'ename' => 'George Grigore', ],
			'ru_abuadel'         => [ 'default' => true,  'id' => 73, 'index' => 1, 'language' => 'ru', 'name' => 'abuadel', 'image' => null, 'localname' => 'abuadel', 'table_name' => 'ru_abuadel', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Абу Адель', 'ename' => 'Abu Adel', ],
			'ru_krachkovsky'     => [ 'default' => false, 'id' => 74, 'index' => 2, 'language' => 'ru', 'name' => 'krachkovsky', 'image' => null, 'localname' => 'krachkovsky', 'table_name' => 'ru_krachkovsky', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Крачковский', 'ename' => 'Ignaty Yulianovich Krachkovsky', ],
			'ru_kuliev'          => [ 'default' => false, 'id' => 75, 'index' => 3, 'language' => 'ru', 'name' => 'kuliev', 'image' => null, 'localname' => 'kuliev', 'table_name' => 'ru_kuliev', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Кулиев', 'ename' => 'Elmir Kuliev', ],
			'ru_kuliev-alsaadi'  => [ 'default' => false, 'id' => 76, 'index' => 4, 'language' => 'ru', 'name' => 'kuliev', 'image' => null, 'localname' => 'kuliev', 'table_name' => 'ru_kuliev-alsaadi', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Кулиев + ас-Саади', 'ename' => 'Elmir Kuliev (with Abd ar-Rahman as-Saadi\'s commentaries)', ],
			'ru_muntahab'        => [ 'default' => false, 'id' => 77, 'index' => 4, 'language' => 'ru', 'name' => 'muntahab', 'image' => null, 'localname' => 'muntahab', 'table_name' => 'ru_muntahab', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Аль-Мунтахаб', 'ename' => 'Ministry of Awqaf, Egypt', ],
			'ru_osmanov'         => [ 'default' => false, 'id' => 78, 'index' => 5, 'language' => 'ru', 'name' => 'osmanov', 'image' => null, 'localname' => 'osmanov', 'table_name' => 'ru_osmanov', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Османов', 'ename' => 'Magomed-Nuri Osmanovich Osmanov', ],
			'ru_porokhova'       => [ 'default' => false, 'id' => 79, 'index' => 6, 'language' => 'ru', 'name' => 'porokhova', 'image' => null, 'localname' => 'porokhova', 'table_name' => 'ru_porokhova', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Порохова', 'ename' => 'V. Porokhova', ],
			'ru_sablukov'        => [ 'default' => false, 'id' => 80, 'index' => 7, 'language' => 'ru', 'name' => 'sablukov', 'image' => null, 'localname' => 'sablukov', 'table_name' => 'ru_sablukov', 'flag' => 'ru', 'language_name' => 'Russian', 'local_name' => 'Саблуков', 'ename' => 'Gordy Semyonovich Sablukov', ],
			'sd_amroti'          => [ 'default' => true,  'id' => 81, 'index' => 1, 'language' => 'sd', 'name' => 'amroti', 'image' => null, 'localname' => 'amroti', 'table_name' => 'sd_amroti', 'flag' => 'pk', 'language_name' => 'Sindhi', 'local_name' => 'امروٽي', 'ename' => 'Taj Mehmood Amroti', ],
			'so_abduh'           => [ 'default' => true,  'id' => 82, 'index' => 1, 'language' => 'so', 'name' => 'abduh', 'image' => null, 'localname' => 'abduh', 'table_name' => 'so_abduh', 'flag' => 'so', 'language_name' => 'Somali', 'local_name' => 'Abduh', 'ename' => 'Mahmud Muhammad Abduh', ],
			'sq_ahmeti'          => [ 'default' => true,  'id' => 83, 'index' => 1, 'language' => 'sq', 'name' => 'ahmeti', 'image' => null, 'localname' => 'ahmeti', 'table_name' => 'sq_ahmeti', 'flag' => 'al', 'language_name' => 'Albanian', 'local_name' => 'Sherif Ahmeti', 'ename' => 'Sherif Ahmeti', ],
			'sq_mehdiu'          => [ 'default' => false, 'id' => 84, 'index' => 2, 'language' => 'sq', 'name' => 'mehdiu', 'image' => null, 'localname' => 'mehdiu', 'table_name' => 'sq_mehdiu', 'flag' => 'al', 'language_name' => 'Albanian', 'local_name' => 'Feti Mehdiu', 'ename' => 'Feti Mehdiu', ],
			'sq_nahi'            => [ 'default' => false, 'id' => 85, 'index' => 3, 'language' => 'sq', 'name' => 'nahi', 'image' => null, 'localname' => 'nahi', 'table_name' => 'sq_nahi', 'flag' => 'al', 'language_name' => 'Albanian', 'local_name' => 'Efendi Nahi', 'ename' => 'Hasan Efendi Nahi', ],
			'sv_bernstrom'       => [ 'default' => true,  'id' => 86, 'index' => 1, 'language' => 'sv', 'name' => 'bernstrom', 'image' => null, 'localname' => 'bernstrom', 'table_name' => 'sv_bernstrom', 'flag' => 'se', 'language_name' => 'Swedish', 'local_name' => 'Bernström', 'ename' => 'Knut Bernström', ],
			'sw_barwani'         => [ 'default' => true,  'id' => 87, 'index' => 1, 'language' => 'sw', 'name' => 'barwani', 'image' => null, 'localname' => 'barwani', 'table_name' => 'sw_barwani', 'flag' => 'ke', 'language_name' => 'Swahili', 'local_name' => 'Al-Barwani', 'ename' => 'Ali Muhsin Al-Barwani', ],
			'ta_tamil'           => [ 'default' => true,  'id' => 88, 'index' => 1, 'language' => 'ta', 'name' => 'tamil', 'image' => null, 'localname' => 'tamil', 'table_name' => 'ta_tamil', 'flag' => 'in', 'language_name' => 'Tamil', 'local_name' => 'ஜான் டிரஸ்ட்', 'ename' => 'Jan Turst Foundation', ],
			'tg_ayati'           => [ 'default' => true,  'id' => 89, 'index' => 1, 'language' => 'tg', 'name' => 'ayati', 'image' => null, 'localname' => 'ayati', 'table_name' => 'tg_ayati', 'flag' => 'tj', 'language_name' => 'Tajik', 'local_name' => 'Оятӣ', 'ename' => 'AbdolMohammad Ayati', ],
			'th_thai'            => [ 'default' => true,  'id' => 90, 'index' => 1, 'language' => 'th', 'name' => 'thai', 'image' => null, 'localname' => 'thai', 'table_name' => 'th_thai', 'flag' => 'th', 'language_name' => 'Thai', 'local_name' => 'ภาษาไทย', 'ename' => 'King Fahad Quran Complex', ],
			'tr_ates'            => [ 'default' => true,  'id' => 91, 'index' => 1, 'language' => 'tr', 'name' => 'ates', 'image' => null, 'localname' => 'ates', 'table_name' => 'tr_ates', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Süleyman Ateş', 'ename' => 'Suleyman Ates', ],
			'tr_bulac'           => [ 'default' => false, 'id' => 92, 'index' => 2, 'language' => 'tr', 'name' => 'bulac', 'image' => null, 'localname' => 'bulac', 'table_name' => 'tr_bulac', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Alİ Bulaç', 'ename' => 'Alİ Bulaç', ],
			'tr_diyanet'         => [ 'default' => false, 'id' => 93, 'index' => 3, 'language' => 'tr', 'name' => 'diyanet', 'image' => null, 'localname' => 'diyanet', 'table_name' => 'tr_diyanet', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Diyanet İşleri', 'ename' => 'Diyanet Isleri', ],
			'tr_golpinarli'      => [ 'default' => false, 'id' => 94, 'index' => 4, 'language' => 'tr', 'name' => 'golpinarli', 'image' => null, 'localname' => 'golpinarli', 'table_name' => 'tr_golpinarli', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Abdulbakî Gölpınarlı', 'ename' => 'Abdulbaki Golpinarli', ],
			'tr_ozturk'          => [ 'default' => false, 'id' => 95, 'index' => 5, 'language' => 'tr', 'name' => 'ozturk', 'image' => null, 'localname' => 'ozturk', 'table_name' => 'tr_ozturk', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Öztürk', 'ename' => 'Yasar Nuri Ozturk', ],
			'tr_transliteration' => [ 'default' => false, 'id' => 96, 'index' => 6, 'language' => 'tr', 'name' => 'transliteration', 'image' => null, 'localname' => 'transliteration', 'table_name' => 'tr_transliteration', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Çeviriyazı', 'ename' => 'Muhammet Abay', ],
			'tr_vakfi'           => [ 'default' => false, 'id' => 97, 'index' => 7, 'language' => 'tr', 'name' => 'vakfi', 'image' => null, 'localname' => 'vakfi', 'table_name' => 'tr_vakfi', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Diyanet Vakfı', 'ename' => 'Diyanet Vakfi', ],
			'tr_yazir'           => [ 'default' => false, 'id' => 98, 'index' => 8, 'language' => 'tr', 'name' => 'yazir', 'image' => null, 'localname' => 'yazir', 'table_name' => 'tr_yazir', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Elmalılı Hamdi Yazır', 'ename' => 'Elmalili Hamdi Yazir', ],
			'tr_yildirim'        => [ 'default' => false, 'id' => 99, 'index' => 9, 'language' => 'tr', 'name' => 'yildirim', 'image' => null, 'localname' => 'yildirim', 'table_name' => 'tr_yildirim', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Suat Yıldırım', 'ename' => 'Suat Yildirim', ],
			'tr_yuksel'          => [ 'default' => false, 'id' => 100, 'index' => 10, 'language' => 'tr', 'name' => 'yuksel', 'image' => null, 'localname' => 'yuksel', 'table_name' => 'tr_yuksel', 'flag' => 'tr', 'language_name' => 'Turkish', 'local_name' => 'Edip Yüksel', 'ename' => 'Edip Yüksel', ],
			'tt_nugman'          => [ 'default' => true,  'id' => 101, 'index' => 1, 'language' => 'tt', 'name' => 'nugman', 'image' => null, 'localname' => 'nugman', 'table_name' => 'tt_nugman', 'flag' => 'ru', 'language_name' => 'Tatar', 'local_name' => 'Yakub Ibn Nugman', 'ename' => 'Yakub Ibn Nugman', ],
			'ug_saleh'           => [ 'default' => true,  'id' => 102, 'index' => 1, 'language' => 'ug', 'name' => 'saleh', 'image' => null, 'localname' => 'saleh', 'table_name' => 'ug_saleh', 'flag' => 'cn', 'language_name' => 'Uyghur', 'local_name' => 'محمد صالح', 'ename' => 'Muhammad Saleh', ],
			'ur_ahmedali'        => [ 'default' => true,  'id' => 103, 'index' => 1, 'language' => 'ur', 'name' => 'ahmedali', 'image' => null, 'localname' => 'ahmedali', 'table_name' => 'ur_ahmedali', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'احمد علی', 'ename' => 'Ahmed Ali', ],
			'ur_jalandhry'       => [ 'default' => false, 'id' => 104, 'index' => 2, 'language' => 'ur', 'name' => 'jalandhry', 'image' => null, 'localname' => 'jalandhry', 'table_name' => 'ur_jalandhry', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'جالندہری', 'ename' => 'Fateh Muhammad Jalandhry', ],
			'ur_jawadi'          => [ 'default' => false, 'id' => 105, 'index' => 3, 'language' => 'ur', 'name' => 'jawadi', 'image' => null, 'localname' => 'jawadi', 'table_name' => 'ur_jawadi', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'علامہ جوادی', 'ename' => 'Syed Zeeshan Haider Jawadi', ],
			'ur_junagarhi'       => [ 'default' => false, 'id' => 106, 'index' => 4, 'language' => 'ur', 'name' => 'junagarhi', 'image' => null, 'localname' => 'junagarhi', 'table_name' => 'ur_junagarhi', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'محمد جوناگڑھی', 'ename' => 'Muhammad Junagarhi', ],
			'ur_kanzuliman'      => [ 'default' => false, 'id' => 107, 'index' => 5, 'language' => 'ur', 'name' => 'kanzuliman', 'image' => null, 'localname' => 'kanzuliman', 'table_name' => 'ur_kanzuliman', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'احمد رضا خان', 'ename' => 'Ahmed Raza Khan', ],
			'ur_maududi'         => [ 'default' => false, 'id' => 108, 'index' => 6, 'language' => 'ur', 'name' => 'maududi', 'image' => null, 'localname' => 'maududi', 'table_name' => 'ur_maududi', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'ابوالاعلی مودودی', 'ename' => 'Abul A\'ala Maududi', ],
			'ur_najafi'          => [ 'default' => false, 'id' => 109, 'index' => 7, 'language' => 'ur', 'name' => 'najafi', 'image' => null, 'localname' => 'najafi', 'table_name' => 'ur_najafi', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'محمد حسین نجفی', 'ename' => 'Muhammad Hussain Najafi ', ],
			'ur_qadri'           => [ 'default' => false, 'id' => 110, 'index' => 8, 'language' => 'ur', 'name' => 'qadri', 'image' => null, 'localname' => 'qadri', 'table_name' => 'ur_qadri', 'flag' => 'pk', 'language_name' => 'Urdu', 'local_name' => 'طاہر القادری', 'ename' => 'Tahir ul Qadri', ],
			'uz_sodik'           => [ 'default' => true,  'id' => 111, 'index' => 1, 'language' => 'uz', 'name' => 'sodik', 'image' => null, 'localname' => 'sodik', 'table_name' => 'uz_sodik', 'flag' => 'uz', 'language_name' => 'Uzbek', 'local_name' => 'Мухаммад Содик', 'ename' => 'Muhammad Sodik Muhammad Yusuf', ],
			'zh_jian'            => [ 'default' => true,  'id' => 112, 'index' => 1, 'language' => 'zh', 'name' => 'jian', 'image' => null, 'localname' => 'jian', 'table_name' => 'zh_jian', 'flag' => 'cn', 'language_name' => 'Chinese', 'local_name' => 'Ma Jian', 'ename' => 'Ma Jian', ],
			'zh_majian'          => [ 'default' => false, 'id' => 113, 'index' => 2, 'language' => 'zh', 'name' => 'majian', 'image' => null, 'localname' => 'majian', 'table_name' => 'zh_majian', 'flag' => 'tw', 'language_name' => 'Chinese', 'local_name' => 'Ma Jian (Traditional)', 'ename' => 'Ma Jian, '],

		];
		return $translate;
	}
}
?>