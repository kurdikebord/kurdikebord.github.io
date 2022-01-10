<?php
namespace lib\app;

/**
 * Class for lm_level.
 */

class lm_level
{
	private static $loaded_level = null;
	private static $sura_replace_by_1 = null;

	public static $sort_field =
	[
		'id',
		'lm_group_id',
		'title',
		// 'desc',
		'type',
		'quranfrom',
		'quranto',
		// 'file',
		// 'setting',
		'sort',
		'ratio',
		'unlockscore',
		'status',
		'datecreated',
	];


	public static function all_level_type($_type)
	{
		$list = \lib\db\lm_level::get_by_type($_type);
		if(is_array($list))
		{
			$list = array_map(['self', 'ready'], $list);
		}

		return $list;
	}


	public static function find_next_level($_lm_level_id)
	{
		$id = \dash\coding::decode($_lm_level_id);
		if(!$id)
		{
			return false;
		}

		$get_next = \lib\db\lm_level::find_next_level($id);
		if(!$get_next)
		{
			return false;
		}

		$get_next = self::ready($get_next);
		return $get_next;
	}


	public static function count_listen($_lm_level_id, $_time = null)
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$load = self::public_load_level($_lm_level_id);
		if(!$load)
		{
			return false;
		}

		$quran_start_sura = isset($load['quran_start_sura']) ? $load['quran_start_sura'] : null;
		$quran_start_aya  = isset($load['quran_start_aya'])  ? $load['quran_start_aya']  : null;
		// $quran_end_sura   = isset($load['quran_end_sura']) 	 ? $load['quran_end_sura'] 	 : return false;
		$quran_end_aya    = isset($load['quran_end_aya']) 	 ? $load['quran_end_aya'] 	 : null;


		if(!$quran_start_sura || !$quran_start_aya || !$quran_end_aya)
		{
			return false;
		}

		if(!$_time || !is_numeric($_time))
		{
			$_time = time() - (60*10);
		}

		$count_listen = \lib\db\history::get_count_listen(\dash\user::id(), $_time, $quran_start_sura, $quran_start_aya, $quran_end_aya);

		if(count($count_listen) >= intval($quran_end_aya))
		{
			$count = array_column($count_listen, 'count');
			$min = min($count);
			return intval($min);
		}

		return 0;

	}

	public static function result($_level_id)
	{
		$load = self::public_load_level($_level_id);
		if(!$load)
		{
			return false;
		}

		// need to load exam result and iqra result
		if(isset($load['type']))
		{
			if($load['type'] === 'iqra')
			{
				$audio_detail = \lib\app\lm_audio::get_result($load['id']);
				$load['audio_detail'] = $audio_detail;
			}
			elseif(in_array($load['type'], ['exam', 'tajweed', 'theme']))
			{
				$session_key = 'save_rand_question_'. $_level_id;

				$loaded_question = \dash\session::get($session_key);
				if($loaded_question && is_array($loaded_question))
				{
					\dash\session::set('show_lms_exam_result_'. $_level_id, true);
					$question_id = array_column($loaded_question, 'id');
					$show_answer = \lib\db\lm_question::get_answer_user(implode(',', $question_id), \dash\user::id());
					if(is_array($show_answer))
					{
						$show_answer = array_combine(array_column($show_answer, 'lm_question_id'), $show_answer);

						foreach ($loaded_question as $key => $value)
						{
							if(isset($show_answer[$value['id']]))
							{
								$loaded_question[$key]['user_answer'] = $show_answer[$value['id']];
								if(intval($show_answer[$value['id']]['opt']) === intval($value['trueopt']))
								{
									$loaded_question[$key]['user_answer']['is_true'] = true;
								}
							}
						}
					}

				}
				$load['question_answer'] = $loaded_question;
			}
		}
		return $load;
	}


	public static function public_load_level($_lm_level_id)
	{
		if(!self::$loaded_level)
		{
			$load_level = self::get($_lm_level_id);
			if(!$load_level)
			{
				\dash\header::status(404, T_("Invalid id"));
				return false;
			}
			self::$loaded_level = $load_level;
		}

		return self::$loaded_level;
	}


	public static function load_quran($_lm_level_id)
	{

		$load_level = self::public_load_level($_lm_level_id);

		if(!isset($load_level['type']) || (isset($load_level['type']) && !in_array($load_level['type'], ['quran', 'iqra'])))
		{
			\dash\header::status(404, T_("Invalid type"));
			return false;
		}

		if(!isset($load_level['quranfrom']) || (isset($load_level['quranfrom']) && !$load_level['quranfrom']))
		{
			\dash\header::status(404, T_("Invalid start quran"));
			return false;
		}

		if(!isset($load_level['quranto']) || (isset($load_level['quranto']) && !$load_level['quranto']))
		{
			\dash\header::status(404, T_("Invalid start quran"));
			return false;
		}

		$load_quran = \lib\db\quran_word::load_from_to($load_level['quranfrom'], $load_level['quranto']);
		$quran = \lib\app\quran\page::load('aya', 0, 0, ['mode' => 'onepage'], $load_quran);

		return $quran;


	}


	public static function public_level_list($_lm_group_id)
	{
		// if(!\dash\user::id())
		// {
		// 	\dash\notif::error(T_("Please login to continue"));
		// 	return false;
		// }

		$_lm_group_id = \dash\coding::decode($_lm_group_id);
		if(!$_lm_group_id)
		{
			\dash\header::status(404, T_("Invalid id"));
			return false;
		}

		$list = \lib\db\lm_level::public_level_list($_lm_group_id, \dash\user::id());
		if(is_array($list))
		{
			$list = array_map(['self', 'ready'], $list);
		}

		return $list;
	}


	public static function type_list($_check = null, $_get_field = null)
	{
		$list               = [];
		$list['quran']      = ['title' => T_("Quran")];
		$list['quranvideo'] = ['title' => T_("Quran video")];
		$list['review']     = ['title' => T_("Review Old Lessons")];
		$list['practice']   = ['title' => T_("Practice")];
		$list['hefz']       = ['title' => T_("Memorize the Quran")];
		$list['lesson']     = ['title' => T_("Lesson")];
		$list['reading']    = ['title' => T_("Reading quran")];
		$list['tajweed']    = ['title' => T_("Tajweed")];
		$list['theme']      = ['title' => T_("Theme of surah")];
		$list['exam']       = ['title' => T_("Exam")];
		$list['iqra']       = ['title' => T_("Fix qiraat")];

		if($_check === null)
		{
			return $list;
		}
		else
		{
			if(array_key_exists($_check, $list))
			{
				if($_get_field === null)
				{
					return $list[$_check];
				}
				else
				{
					if(array_key_exists($_get_field, $list[$_check]))
					{
						return $list[$_check][$_get_field];
					}
					else
					{
						return null;
					}
				}
			}
			else
			{
				return false;
			}
		}
	}


	public static function get_count_group()
	{
		$group_count = \lib\db\lm_level::get_count_group();
		if(is_array($group_count))
		{
			$group_count = array_map(['self', 'ready'], $group_count);
		}
		return $group_count;
	}


	public static function add($_args = [])
	{
		\dash\app::variable($_args);

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}

		// check args
		$args = self::check();

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}

		$return = [];

		if(!$args['status'])
		{
			$args['status']  = 'enable';
		}

		if($args['type'] === 'quran')
		{
			$args['besmellah'] = 1;
		}

		$lm_level_id = \lib\db\lm_level::insert($args);

		if(!$lm_level_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($lm_level_id);

		if(\dash\engine\process::status())
		{
			\dash\log::set('addNewLevelGroup', ['code' => $lm_level_id]);
			\dash\notif::ok(T_("Level group successfuly added"));
		}

		return $return;
	}


	public static function edit($_args, $_id)
	{
		\dash\app::variable($_args);

		$result = self::get($_id);

		if(!$result)
		{
			return false;
		}

		$id = \dash\coding::decode($_id);

		$args = self::check($id);

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}


		if(!\dash\app::isset_request('lm_group_id')) unset($args['lm_group_id']);
		if(!\dash\app::isset_request('title')) unset($args['title']);
		if(!\dash\app::isset_request('desc')) unset($args['desc']);
		if(!\dash\app::isset_request('type')) unset($args['type']);
		if(!\dash\app::isset_request('badge')) unset($args['badge']);

		if(!\dash\app::isset_request('besmellah')) unset($args['besmellah']);
		if(!\dash\app::isset_request('file')) unset($args['file']);
		if(!\dash\app::isset_request('setting')) unset($args['setting']);
		if(!\dash\app::isset_request('sort')) unset($args['sort']);
		if(!\dash\app::isset_request('ratio')) unset($args['ratio']);
		if(!\dash\app::isset_request('unlockscore')) unset($args['unlockscore']);
		if(!\dash\app::isset_request('status')) unset($args['status']);
		if(!\dash\app::isset_request('questionrandcount')) unset($args['questionrandcount']);
		if(!\dash\app::isset_request('filepic')) unset($args['filepic']);



		if(!empty($args))
		{
			$update = \lib\db\lm_level::update($args, $id);

			$title = isset($args['title']) ? $args['title'] : T_("LevelGroup");

			\dash\log::set('editLevelGroup', ['code' => $id]);

			if(\dash\engine\process::status())
			{
				\dash\notif::ok(T_(":val successfully updated", ['val' => $title]));
			}
		}

		return \dash\engine\process::status();
	}


	public static function get($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("lm_level id not set"));
			return false;
		}

		$get = \lib\db\lm_level::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid lm_level id"));
			return false;
		}

		$result = self::ready($get);

		return $result;
	}


	public static function list($_string = null, $_args = [])
	{
		// if(!\dash\user::id())
		// {
		// 	return false;
		// }

		$default_meta =
		[
			'sort'  => null,
			'order' => null,
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default_meta, $_args);

		if($_args['sort'] && !in_array($_args['sort'], self::$sort_field))
		{
			$_args['sort'] = null;
		}

		$result            = \lib\db\lm_level::search($_string, $_args);
		$temp              = [];

		foreach ($result as $key => $value)
		{
			$check = self::ready($value);
			if($check)
			{
				$temp[] = $check;
			}
		}

		return $temp;
	}


	private static function check($_id = null)
	{
		$title = \dash\app::request('title');
		if(\dash\app::isset_request('title') && !$title)
		{
			\dash\notif::error(T_("Please fill the title"), 'title');
			return false;
		}

		if(mb_strlen($title) > 300)
		{
			\dash\notif::error(T_("Please fill the title less than 300 character"), 'title');
			return false;
		}

		$lm_group_id = \dash\app::request('lm_group_id');
		$lm_group_id = \dash\coding::decode($lm_group_id);
		if(!$lm_group_id && !$_id)
		{
			\dash\notif::error(T_("Please set group id"));
			return false;
		}

		if($lm_group_id)
		{
			$check_duplicate = \lib\db\lm_level::get(['title' => $title, 'lm_group_id' => $lm_group_id, 'limit' => 1]);
			if(isset($check_duplicate['id']))
			{
				if(intval($_id) === intval($check_duplicate['id']))
				{
					// no problem to edit it
				}
				else
				{
					$code = \dash\coding::encode($check_duplicate['id']);
					$msg = T_("This title is already exist in your list");
					$msg .= ' <a href="'. \dash\url::this(). '/edit?id='.$code. '">'. T_("Click here to edit it"). "</a>";
					\dash\notif::error($msg, 'title');
					return false;
				}
			}
		}

		$status = \dash\app::request('status');
		if($status && !in_array($status, ['enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire']))
		{
			\dash\notif::error(T_("Invalid status"), 'status');
			return false;
		}

		$desc    = \dash\app::request('desc');
		$file    = \dash\app::request('file');
		$setting    = \dash\app::request('setting');
		$filepic    = \dash\app::request('filepic');

		$sort = \dash\app::request('sort');
		$sort = \dash\utility\convert::to_en_number($sort);
		if($sort && !is_numeric($sort))
		{
			\dash\notif::error(T_("Please set the sort as a number"), 'sort');
			return false;
		}

		if($sort)
		{
			$sort = intval($sort);
			$sort = abs($sort);
		}

		if($sort && intval($sort) > 1E+4)
		{
			\dash\notif::error(T_("Sort is out of range!"), 'sort');
			return false;
		}

		$questionrandcount = \dash\app::request('questionrandcount');
		$questionrandcount = \dash\utility\convert::to_en_number($questionrandcount);
		if($questionrandcount && !is_numeric($questionrandcount))
		{
			\dash\notif::error(T_("Please set the questionrandcount as a number"), 'questionrandcount');
			return false;
		}

		if($questionrandcount)
		{
			$questionrandcount = intval($questionrandcount);
			$questionrandcount = abs($questionrandcount);
		}

		if($questionrandcount && intval($questionrandcount) > 9999)
		{
			\dash\notif::error(T_("Unlock score is out of range!"), 'questionrandcount');
			return false;
		}


		$unlockscore = \dash\app::request('unlockscore');
		$unlockscore = \dash\utility\convert::to_en_number($unlockscore);
		if($unlockscore && !is_numeric($unlockscore))
		{
			\dash\notif::error(T_("Please set the unlockscore as a number"), 'unlockscore');
			return false;
		}

		if($unlockscore)
		{
			$unlockscore = intval($unlockscore);
			$unlockscore = abs($unlockscore);
		}

		if($unlockscore && intval($unlockscore) > 1E+4)
		{
			\dash\notif::error(T_("Unlock score is out of range!"), 'unlockscore');
			return false;
		}


		$type = \dash\app::request('type');
		if(!$type && \dash\app::isset_request('type'))
		{
			\dash\notif::error(T_("Please choose type"), 'type');
			return false;
		}

		if($type && !self::type_list($type))
		{
			\dash\notif::error(T_("Invalid type"), 'type');
			return false;
		}

		$badge = \dash\app::request('badge');
		if($badge && !\lib\badge::list($badge))
		{
			\dash\notif::error(T_("Invalid badge"));
			return false;
		}



		$quranfrom = self::quran_from();
		$quranto = self::quran_to();

		if(!\dash\engine\process::status())
		{
			return false;
		}

		if($quranfrom || $quranto)
		{
			if(intval($quranfrom) > intval($quranto))
			{
				\dash\notif::error(T_("Please set quran start aya before end aya"));
				return false;
			}

			if(intval($quranto) - intval($quranfrom) > 200)
			{
				\dash\notif::error(T_("Maximum range of quran in learning mode is 200 character"));
				return false;
			}

			if($quranfrom && !$quranto)
			{
				\dash\notif::error(T_("Plase set end aya"));
				return false;
			}

			if(!$quranfrom && $quranto)
			{
				\dash\notif::error(T_("Plase set start aya"));
				return false;
			}
		}

		if($quranfrom && $quranto && self::$sura_replace_by_1 && $_id)
		{
			$saved_record = \lib\db\lm_level::get(['id' => $_id, 'limit' => 1]);
			if(isset($saved_record['title']) && $saved_record['title'] == '1' && isset($saved_record['type']))
			{
				$sura_name = T_(\lib\app\sura::detail(self::$sura_replace_by_1, 'tname'));

				$new_title = null;
				switch ($saved_record['type'])
				{
					case 'quran':
					case 'quranvideo':
						$new_title = 'سوره '. $sura_name;
						break;

					case 'iqra':
						$new_title = 'تصحیح قرائت سوره '. $sura_name;
						break;

					case 'theme':
					case 'exam':
					case 'reading':
					case 'tajweed':
					default:
						// nothing
						break;
				}

				if($new_title)
				{
					\lib\db\lm_level::update(['title' => $new_title], $saved_record['id']);
				}
			}
		}

		$besmellah = \dash\app::request('besmellah') ? 1 : null;

		$ratio = \dash\app::request('ratio');
		$ratio = \dash\utility\convert::to_en_number($ratio);
		if($ratio && !is_numeric($ratio))
		{
			\dash\notif::error(T_("Please set the ratio as a number"), 'ratio');
			return false;
		}

		if($ratio)
		{
			$ratio = intval($ratio);
			$ratio = abs($ratio);
		}

		if($ratio && intval($ratio) > 1E+4)
		{
			\dash\notif::error(T_("Unlock score is out of range!"), 'ratio');
			return false;
		}

		$args                      = [];
		$args['title']             = $title;
		$args['badge']             = $badge;
		$args['lm_group_id']       = $lm_group_id;
		$args['status']            = $status;
		$args['desc']              = $desc;
		$args['file']              = $file;
		$args['filepic']           = $filepic;
		$args['setting']           = $setting;
		$args['sort']              = $sort;
		$args['unlockscore']       = $unlockscore;
		$args['type']              = $type;
		$args['questionrandcount'] = $questionrandcount;

		if(\dash\app::isset_request('startsurah') &&  \dash\app::isset_request('startaya'))
		{
			$args['quranfrom']   = $quranfrom;
		}

		if(\dash\app::isset_request('endsurah') &&  \dash\app::isset_request('endaya'))
		{
			$args['quranto']     = $quranto;
		}

		$args['ratio']       = $ratio;
		$args['besmellah']   = $besmellah;

		return $args;
	}


	private static function quran_to()
	{
		$endsurah = \dash\app::request('endsurah');

		$endsurah = \dash\utility\convert::to_en_number($endsurah);
		if($endsurah && !is_numeric($endsurah))
		{
			\dash\notif::error(T_("Please set the endsurah as a number"), 'endsurah');
			return false;
		}

		if($endsurah)
		{
			$endsurah = intval($endsurah);
			$endsurah = abs($endsurah);
		}

		if($endsurah && intval($endsurah) > 114)
		{
			\dash\notif::error(T_("Surah index is out of range!"), 'quranfrom');
			return false;
		}

		$endaya   = \dash\app::request('endaya');
		$endaya = \dash\utility\convert::to_en_number($endaya);
		if($endaya && !is_numeric($endaya))
		{
			\dash\notif::error(T_("Please set the endaya as a number"), 'endaya');
			return false;
		}

		if($endaya)
		{
			$endaya = intval($endaya);
			$endaya = abs($endaya);
		}

		if($endaya && intval($endaya) > intval(\lib\app\sura::detail($endsurah, 'ayas')))
		{
			\dash\notif::error(T_("Aya number is out of range!"), 'endaya');
			return false;
		}


		if(!self::$sura_replace_by_1)
		{
			self::$sura_replace_by_1 = $endsurah;
		}

		if($endsurah && $endaya)
		{
			$id = \lib\db\quran_word::get_first_word(['sura' => $endsurah, 'aya'=> $endaya], 'DESC');
			if(isset($id['id']))
			{
				return $id['id'];
			}
			else
			{
				\dash\notif::error(T_("Detail not found"));
				return false;
			}
		}

		return null;

	}


	private static function quran_from()
	{
		$startsurah = \dash\app::request('startsurah');

		$startsurah = \dash\utility\convert::to_en_number($startsurah);
		if($startsurah && !is_numeric($startsurah))
		{
			\dash\notif::error(T_("Please set the startsurah as a number"), 'startsurah');
			return false;
		}

		if($startsurah)
		{
			$startsurah = intval($startsurah);
			$startsurah = abs($startsurah);
		}

		if($startsurah && intval($startsurah) > 114)
		{
			\dash\notif::error(T_("Surah index is out of range!"), 'quranfrom');
			return false;
		}

		$startaya   = \dash\app::request('startaya');
		$startaya = \dash\utility\convert::to_en_number($startaya);
		if($startaya && !is_numeric($startaya))
		{
			\dash\notif::error(T_("Please set the startaya as a number"), 'startaya');
			return false;
		}

		if($startaya)
		{
			$startaya = intval($startaya);
			$startaya = abs($startaya);
		}

		if($startaya && intval($startaya) > intval(\lib\app\sura::detail($startsurah, 'ayas')))
		{
			\dash\notif::error(T_("Aya number is out of range!"), 'startaya');
			return false;
		}

		if(!self::$sura_replace_by_1)
		{
			self::$sura_replace_by_1 = $startsurah;
		}


		if($startsurah && $startaya)
		{
			$id = \lib\db\quran_word::get_first_word(['sura' => $startsurah, 'aya'=> $startaya]);
			if(isset($id['id']))
			{
				return $id['id'];
			}
			else
			{
				\dash\notif::error(T_("Detail not found"));
				return false;
			}
		}

		return null;

	}


	public static function ready($_data)
	{
		$result = [];
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
				case 'lm_group_id':
					if(isset($value))
					{
						$result[$key] = \dash\coding::encode($value);
					}
					else
					{
						$result[$key] = null;
					}
					break;

				case 'file':
					// if(\dash\url::content() !== 'm')
					// {
					// 	if(!$value)
					// 	{
					// 		$value = \dash\app::static_logo_url();
					// 	}
					// }
					$result[$key] = $value;
					break;

				case 'type':
					$result[$key] = $value;
					if($value)
					{
						$result['type_title'] = self::type_list($value, 'title');
					}
					break;

				case 'quranfrom':
					$result[$key] = $value;

					// if(\dash\url::child() === 'quran' && $value)
					{
						$load_detail                = \lib\db\quran_word::get_by_id($value);
						$result['quran_start_sura'] = (isset($load_detail['sura'])) ? $load_detail['sura'] : null;
						if($result['quran_start_sura'])
						{
							$result['quran_sura'] = T_(\lib\app\sura::detail($result['quran_start_sura'], 'tname'));
						}
						$result['quran_start_aya']  = (isset($load_detail['aya'])) ? $load_detail['aya'] : null;
					}

					break;

				case 'quranto':
					$result[$key] = $value;
					// if(\dash\url::child() === 'quran' && $value)
					{
						$load_detail              = \lib\db\quran_word::get_by_id($value);
						$result['quran_end_sura'] = (isset($load_detail['sura'])) ? $load_detail['sura'] : null;
						if($result['quran_end_sura'])
						{
							$result['quran_sura'] = T_(\lib\app\sura::detail($result['quran_end_sura'], 'tname'));
						}
						$result['quran_end_aya']  = (isset($load_detail['aya'])) ? $load_detail['aya'] : null;
					}
					break;

				case 'setting':
					if($value)
					{
						$result[$key] = json_decode($value, true);
					}
					else
					{
						$result[$key] = $value;
					}
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		$xtype = isset($result['type']) ? $result['type'] : null;
		if(in_array($xtype, ['tajweed', 'reading', 'theme', 'review', 'practice', 'hefz', 'lesson']))
		{
			$xtype = 'video';
		}

		if(in_array($xtype, ['exam']) && isset($result['file']) && $result['file'])
		{
			$xtype = 'video';
		}

		$result['xtype'] = $xtype;

		if(isset($result['quran_start_sura']) && isset($result['quran_start_aya']) && isset($result['quran_end_aya']))
		{
			$iframe_link = \dash\url::kingdom();
			$iframe_link .= '/s'. $result['quran_start_sura'];
			$iframe_link .= '/'. $result['quran_start_aya'];
			$iframe_link .= '-'. $result['quran_end_aya'];
			$iframe_link .= '?fixframe=1&mode=pagedesign';
			$result['iframe_link'] = $iframe_link;
		}

		return $result;

	}
}
?>