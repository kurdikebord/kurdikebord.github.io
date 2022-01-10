<?php
namespace lib\app;

/**
 * Class for lm_question.
 */

class lm_question
{
	public static $sort_field =
	[
		'lm_level_id',
		'title',
		'desc',
		'type',
		'model',
		'opt1',
		'opt1file',
		'opt2',
		'opt2file',
		'opt3',
		'opt3file',
		'opt4',
		'opt4file',
		'trueopt',
		'status',
	];


	public static function public_load($_type, $_level_id)
	{
		$level_detail = \lib\app\lm_level::public_load_level($_level_id);
		if(!isset($level_detail['type']))
		{
			return false;
		}

		$limit = 100;
		if($level_detail['type'] === 'learn')
		{
			$limit = 2;
		}
		elseif($level_detail['type'] === 'exam')
		{
			//
		}

		if(isset($level_detail['questionrandcount']))
		{
			$limit = intval($level_detail['questionrandcount']);
		}

		$level_id      = \dash\coding::decode($_level_id);

		$session_key = 'save_rand_question_'. $_level_id;


		if(\dash\session::get('show_lms_exam_result_'. $_level_id))
		{
			\dash\session::clean($session_key);
			\dash\session::clean('show_lms_exam_result_'. $_level_id);
		}

		if(\dash\session::get($session_key))
		{
			$load_question = \dash\session::get($session_key);
		}
		else
		{
			$load_question = \lib\db\lm_question::get_rand($level_id, $limit);
			\dash\session::set($session_key, $load_question);
		}

		if(is_array($load_question))
		{
			$load_question = array_map(['self', 'ready'], $load_question);
		}

		return $load_question;
	}


	public static function remove($_id)
	{
		$get = self::get($_id);
		if($get)
		{
			$id = \dash\coding::decode($_id);
			\lib\db\lm_question::update(['status' => 'deleted'], $id);
			\dash\notif::ok(T_("Question removed"));
			return true;
		}
		else
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}
	}

	public static function site_list($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			return false;
		}

		$args =
		[
			'pagenation'              => false,
			'lm_question.lm_level_id' => $id,
			'status'                  => 'enable',

		];

		$list = self::list(null, $args);

		return $list;
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

		$lm_level_id = \lib\db\lm_question::insert($args);

		if(!$lm_level_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($lm_level_id);

		if(\dash\engine\process::status())
		{
			\dash\log::set('addNewQuestion', ['code' => $lm_level_id]);
			\dash\notif::ok(T_("Question successfuly added"));
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

		if(!\dash\app::isset_request('lm_level_id')) unset($args['lm_level_id']);
		if(!\dash\app::isset_request('title')) unset($args['title']);
		if(!\dash\app::isset_request('desc')) unset($args['desc']);
		if(!\dash\app::isset_request('type')) unset($args['type']);
		if(!\dash\app::isset_request('model')) unset($args['model']);
		if(!\dash\app::isset_request('opt1')) unset($args['opt1']);
		if(!\dash\app::isset_request('opt1file')) unset($args['opt1file']);
		if(!\dash\app::isset_request('opt2')) unset($args['opt2']);
		if(!\dash\app::isset_request('opt2file')) unset($args['opt2file']);
		if(!\dash\app::isset_request('opt3')) unset($args['opt3']);
		if(!\dash\app::isset_request('opt3file')) unset($args['opt3file']);
		if(!\dash\app::isset_request('opt4')) unset($args['opt4']);
		if(!\dash\app::isset_request('opt4file')) unset($args['opt4file']);
		if(!\dash\app::isset_request('trueopt')) unset($args['trueopt']);
		if(!\dash\app::isset_request('status')) unset($args['status']);

		if(!empty($args))
		{
			$update = \lib\db\lm_question::update($args, $id);

			$title = isset($args['title']) ? $args['title'] : T_("Question");

			\dash\log::set('editQuestion', ['code' => $id]);

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
			\dash\notif::error(T_("lm_question id not set"));
			return false;
		}

		$get = \lib\db\lm_question::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid lm_question id"));
			return false;
		}

		$result = self::ready($get);

		return $result;
	}


	public static function list($_string = null, $_args = [])
	{
		if(!\dash\user::id())
		{
			return false;
		}

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

		$result            = \lib\db\lm_question::search($_string, $_args);
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

		if(mb_strlen($title) > 5000)
		{
			\dash\notif::error(T_("Please fill the title less than 5000 character"), 'title');
			return false;
		}

		$lm_level_id = \dash\app::request('lm_level_id');
		$lm_level_id = \dash\coding::decode($lm_level_id);
		if(!$lm_level_id && !$_id)
		{
			\dash\notif::error(T_("Please set group id"));
			return false;
		}

		if($lm_level_id)
		{
			$check_duplicate = \lib\db\lm_question::get(['title' => $title, 'lm_level_id' => $lm_level_id, 'limit' => 1]);
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

		$type = \dash\app::request('type');
		if(!$type && \dash\app::isset_request('type'))
		{
			\dash\notif::error(T_("Please choose type"), 'type');
			return false;
		}

		if($type && !in_array($type, ['level', 'public']))
		{
			\dash\notif::error(T_("Invalid type"), 'type');
			return false;
		}


		$model = \dash\app::request('model');
		if(!$model && \dash\app::isset_request('model'))
		{
			\dash\notif::error(T_("Please choose model"), 'model');
			return false;
		}

		if($model && !in_array($model, ['text', 'audio', 'video']))
		{
			\dash\notif::error(T_("Invalid model"), 'model');
			return false;
		}


		$trueopt = \dash\app::request('trueopt');
		if(!$trueopt && \dash\app::isset_request('trueopt'))
		{
			\dash\notif::error(T_("Please choose trueopt"), 'trueopt');
			return false;
		}

		if($trueopt)
		{
			$trueopt = intval($trueopt);
			$trueopt = abs($trueopt);
		}

		if($trueopt && !in_array($trueopt, [1,2,3,4]))
		{
			\dash\notif::error(T_("Invalid trueopt"), 'trueopt');
			return false;
		}

		$opt1     = self::opt('opt1');
		$opt1file = self::optfile('opt1file');

		if(\dash\app::isset_request('opt1') || \dash\app::isset_request('opt1file'))
		{
			if(!$opt1 && !$opt1file)
			{
				\dash\notif::error(T_("Option 1 is required"), 'opt1');
				return false;
			}
		}


		$opt2     = self::opt('opt2');
		$opt2file = self::optfile('opt2file');

		if(\dash\app::isset_request('opt2') || \dash\app::isset_request('opt2file'))
		{
			if(!$opt2 && !$opt2file)
			{
				\dash\notif::error(T_("Option 2 is required"), 'opt1');
				return false;
			}
		}
		$opt3     = self::opt('opt3');
		$opt3file = self::optfile('opt3file');
		$opt4     = self::opt('opt4');
		$opt4file = self::optfile('opt4file');

		if(!isset($opt3) && !isset($opt3file) && ($opt4 || $opt4file))
		{
			\dash\notif::error(T_("Please fill out the options in order"), ['element' => ['opt3', 'opt4']]);
			return false;
		}

		if(!isset($opt3) && !isset($opt3file) && $trueopt === 3)
		{
			\dash\notif::error(T_("Option 3 is empty and can not set true option on this"), 'trueopt');
			return false;
		}

		if(!isset($opt4) && !isset($opt4file) && $trueopt === 4)
		{
			\dash\notif::error(T_("Option 4 is empty and can not set true option on this"), 'trueopt');
			return false;
		}

		if(($opt1 || $opt1file || $opt2 || $opt2file) && !$trueopt && \dash\app::isset_request('trueopt'))
		{
			\dash\notif::error(T_("Please set the true option"), 'trueopt');
			return false;
		}


		$args                = [];
		$args['opt1']        = $opt1;
		$args['opt1file']    = $opt1file;
		$args['opt2']        = $opt2;
		$args['opt2file']    = $opt2file;
		$args['opt3']        = $opt3;
		$args['opt3file']    = $opt3file;
		$args['opt4']        = $opt4;
		$args['opt4file']    = $opt4file;
		$args['title']       = $title;
		$args['lm_level_id'] = $lm_level_id;
		$args['status']      = $status;
		$args['type']        = $type;
		$args['model']       = $model;
		$args['trueopt']     = $trueopt;

		return $args;
	}


	private static function opt($_name)
	{
		$opt = \dash\app::request($_name);
		if($opt && mb_strlen($opt) > 300)
		{
			\dash\notif::error(T_("Option length is out of range"));
		}

		if(!$opt && (string) $opt !== '0')
		{
			return null;
		}
		return $opt;
	}


	private static function optfile($_name)
	{
		return \dash\app::request($_name);
	}



	public static function ready($_data)
	{
		$result = [];
		$longopt = false;
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
				case 'lm_level_id':
					if(isset($value))
					{
						$result[$key] = \dash\coding::encode($value);
					}
					else
					{
						$result[$key] = null;
					}
					break;

				case 'opt1':
				case 'opt2':
				case 'opt3':
				case 'opt4':
					$result[$key] = $value;
					if(mb_strlen($value) > 30)
					{
						$longopt = true;
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

		$result['longopt'] = $longopt;

		return $result;
	}
}
?>