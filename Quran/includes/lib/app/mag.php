<?php
namespace lib\app;


class mag
{
	public static function find_by_post($_post_id)
	{
		$lang = \dash\language::current();
		$list = \lib\db\mags::find_by_post($_post_id, $lang);
		if(!$list || !is_array($list))
		{
			return null;
		}

		$result = [];
		foreach ($list as $key => $value)
		{
			$temp              = [];
			$temp['link']      = \dash\url::kingdom(). '/'. $value['url'];
			$temp['title']     = $value['title'];
			$temp['word']      = $value['word'];
			$temp['wordtitle'] = $value['wordtitle'];
			$temp['type']      = $value['type'];
			$temp['subtype']   = $value['subtype'];
			$quran_link        = null;
			$quran_title       = null;

			if($value['sura'] && $value['aya'])
			{
				$quran_title = T_("Sura"). ' '. T_(\lib\app\sura::detail($value['sura'], 'name'));
				$quran_title .= ' '. T_("Aya"). ' '. \dash\utility\human::fitNumber($value['aya']);
				$quran_link  = 's'. $value['sura']. '/'. $value['aya'];
			}
			elseif($value['sura'])
			{
				$quran_title = T_("Sura"). ' '. T_(\lib\app\sura::detail($value['sura'], 'name'));
				$quran_link  = 's'. $value['sura'];
			}
			elseif($value['page'])
			{
				$quran_title = T_("Page"). ' '. \dash\utility\human::fitNumber($value['page']);
				$quran_link  = 'p'. $value['page'];
			}

			$temp['quran_link'] = \dash\url::kingdom(). '/'. $quran_link;
			$temp['quran_title'] = T_("Quran") . ' '. $quran_title;

			$result[] = $temp;
		}

		return $result;
	}


	public static function find($_detail)
	{
		if(!is_array($_detail))
		{
			return false;
		}

		$page = array_column($_detail, 'page');
		$page = array_unique($page);
		$page = array_filter($page);
		if(!$page)
		{
			return null;
		}

		$page = array_map('intval', $page);

		$lang = \dash\language::current();
		$list = \lib\db\mags::get_by_page(implode(',', $page), $lang);
		if(!$list)
		{
			return null;
		}

		$result = [];
		foreach ($list as $key => $value)
		{
			$temp              = [];
			$temp['link']      = \dash\url::kingdom(). '/'. $value['url'];
			$temp['title']     = $value['title'];
			$temp['word']      = $value['word'];
			$temp['wordtitle'] = $value['wordtitle'];
			$temp['type']      = $value['type'];
			$temp['subtype']   = $value['subtype'];
			$quran_link        = null;

			if($value['sura'] && $value['aya'])
			{
				$quran_link = 's'. $value['sura']. '/'. $value['aya'];
			}
			elseif($value['sura'])
			{
				$quran_link = 's'. $value['sura'];
			}
			elseif($value['page'])
			{
				$quran_link = 'p'. $value['page'];
			}

			$temp['quran_link'] = \dash\url::kingdom(). '/'. $quran_link;

			$result[] = $temp;
		}

		return $result;
	}


	public static function subtype_list()
	{
		$list         = [];
		$list['rule'] = ['title' => T_("Rule")];
		$list['info'] = ['title' => T_("Information")];
		return $list;
	}

	public static function get_subtype_title($_key)
	{
		$list = self::subtype_list();
		if(isset($list[$_key]['title']))
		{
			return $list[$_key]['title'];
		}
		return null;
	}


	public static function add($_args)
	{
		\dash\app::variable($_args);

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		$type  = \dash\app::request('type');
		if(!in_array($type, ['aya', 'sura', 'page', 'word']))
		{
			\dash\notif::error(T_("Invalid type"));
			return false;
		}

		$post = \dash\app::request('post');
		if(!$post)
		{
			\dash\notif::error(T_("Please choose one magazine"));
			return false;
		}

		$load_post = \dash\app\posts::get($post);
		if(!$load_post)
		{
			\dash\notif::error(T_("Invalid post"));
			return false;
		}

		$word = \dash\app::request('word');
		if($word && !is_numeric($word))
		{
			\dash\notif::error(T_("Please choose a word of this aya"), 'word');
			return false;
		}


		$subtype = \dash\app::request('subtype');
		if($subtype && !array_key_exists($subtype, self::subtype_list()))
		{
			\dash\notif::error(T_("Invalid subtype"), 'subtype');
			return false;
		}

		$insert               = [];
		$insert['post_id']    = \dash\coding::decode($post);
		$insert['type']       = $type;
		$insert['creator']    = \dash\user::id();
		$insert['subtype']    = $subtype;

		$duplicate            = [];
		$duplicate['post_id'] = $insert['post_id'];
		$duplicate['type']    = $insert['type'];

		switch ($type)
		{
			case 'sura':
				$surah = \dash\app::request('surah');
				$surah = intval($surah);
				if($surah < 1 || $surah > 114)
				{
					\dash\notif::error(T_("Invalid sura id"));
					return false;
				}
				$insert['sura']    = $surah;
				$startpage = \lib\app\sura::detail($surah, 'startpage');

				if(is_numeric($startpage))
				{
					$insert['page']    = $startpage;
					$duplicate['page'] = $startpage;
				}

				$duplicate['sura'] = $insert['sura'];
				break;

			case 'aya':
				$surah = \dash\app::request('surah');
				$surah = intval($surah);
				if($surah < 1 || $surah > 114)
				{
					\dash\notif::error(T_("Invalid sura id"));
					return false;
				}

				$aya       = \dash\app::request('aya');
				$aya       = intval($aya);
				$sura_ayas = \lib\app\sura::detail($surah, 'ayas');

				if($aya < 1 || $aya > $sura_ayas)
				{
					\dash\notif::error(T_("This sura have :val aya", ['val' => \dash\utility\human::fitNumber($sura_ayas)]));
					return false;
				}

				$startpage = \lib\app\sura::detail($surah, 'startpage');

				if(is_numeric($startpage))
				{
					$insert['page']    = $startpage;
					$duplicate['page'] = $startpage;
				}

				$insert['sura']    = $surah;
				$insert['aya']     = $aya;
				$duplicate['sura'] = $insert['sura'];
				$duplicate['aya']  = $insert['aya'];
				break;

			case 'page':
				$page = \dash\app::request('page');
				$page = intval($page);
				if($page < 1 || $page > 604)
				{
					\dash\notif::error(T_("Invalid page number"));
					return false;
				}
				$insert['page']    = $page;
				$duplicate['page'] = $insert['page'];
				break;

			case 'word':
				$surah = \dash\app::request('surah');
				$surah = intval($surah);
				if($surah < 1 || $surah > 114)
				{
					\dash\notif::error(T_("Invalid sura id"));
					return false;
				}

				$aya       = \dash\app::request('aya');
				$aya       = intval($aya);
				$sura_ayas = \lib\app\sura::detail($surah, 'ayas');

				if($aya < 1 || $aya > $sura_ayas)
				{
					\dash\notif::error(T_("This sura have :val aya", ['val' => \dash\utility\human::fitNumber($sura_ayas)]));
					return false;
				}

				$word = intval($word);
				// 88246 number of word in quran database `salamquran_data.1_quran_word.id`
				if($word < 0 || $word > 88246)
				{
					\dash\notif::error(T_("Invalid word id"));
					return false;
				}

				$check_word_is_ok = \lib\db\quran_word::get(['id' => $word, 'sura' => $surah, 'aya' => $aya, 'limit' => 1]);
				if(!isset($check_word_is_ok['id']))
				{
					\dash\notif::error(T_("Invalid word id"));
					return false;
				}

				$startpage = \lib\app\sura::detail($surah, 'startpage');

				if(is_numeric($startpage))
				{
					$insert['page']    = $startpage;
					$duplicate['page'] = $startpage;
				}

				$insert['sura']      = $surah;
				$insert['aya']       = $aya;
				$insert['word']      = $word;
				$insert['wordtitle'] = isset($check_word_is_ok['simple']) ? $check_word_is_ok['simple'] : null;
				$duplicate['sura']   = $insert['sura'];
				$duplicate['aya']    = $insert['aya'];
				$duplicate['word']   = $insert['word'];

				break;

			default:
				\dash\notif::error(T_("This method is not supported"));
				return false;
				break;
		}

		if(!empty($insert))
		{
			$duplicate['limit'] = 1;
			$check_duplicate    = \lib\db\mags::get($duplicate);
			if(isset($check_duplicate['id']))
			{
				\dash\notif::error(T_("Duplicate connection mags to quran"), ['element' => ['post_id', 'page', 'aya', 'surah']]);
				return false;
			}

			$insert_ok          = \lib\db\mags::insert($insert);
			if($insert_ok)
			{
				\dash\notif::ok(T_("The magazine connect to quran"));
				return true;
			}
		}
	}


	public static function list($_string = null, $_args = null, $_option = null)
	{
		$list = \lib\db\mags::search($_string, $_args, $_option);
		if(is_array($list))
		{
			$list = array_map(['self', 'ready'], $list);
		}

		return $list;
	}


	public static function ready($_data)
	{
		$result = [];
		if(!is_array($_data))
		{
			return $result;
		}

		foreach ($_data as $key => $value)
		{
			switch ($key)
			{
				case 'mag_id':
				case 'post_id':
					$result[$key] = \dash\coding::encode($value);
					break;

				case 'subtype':
					$result[$key] = $value;
					$result['subtype_title'] = self::get_subtype_title($value);
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		return $result;
	}


	public static function remove($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Id not set"));
			return false;
		}

		$load = \lib\db\mags::get_to_remove($id);
		if($load)
		{
			\lib\db\mags::remove($id);
			\dash\notif::ok(T_("Connection mag to quran removed"));
			return true;
		}
		else
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}
	}
}
?>