<?php
namespace lib\app;

/**
 * Class for lm_group.
 */

class lm_group
{
	public static $sort_field =
	[
		'id',
		'title',
		'type',
		'desc',
		'sort',
		'status',
		'datecreated',
		'file',
	];


	public static function type_list($_check = null, $_get_field = null)
	{
		$list            = [];
		$list['learn']   = ['title' => T_("Learn")];
		$list['exam']    = ['title' => T_("Exam")];

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


	public static function public_list()
	{
		return self::list(null, ['lm_group.status' => 'enable', 'pagenation' => false]);
	}


	public static function site_list()
	{
		$list = self::list(null, ['pagenation' => false]);
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



		$lm_group_id = \lib\db\lm_group::insert($args);

		if(!$lm_group_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($lm_group_id);

		if(\dash\engine\process::status())
		{
			\dash\log::set('addNewLearnGroup', ['code' => $lm_group_id]);
			\dash\notif::ok(T_("Learn group successfuly added"));
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


		if(!\dash\app::isset_request('title')) unset($args['title']);
		if(!\dash\app::isset_request('desc')) unset($args['desc']);
		if(!\dash\app::isset_request('sort')) unset($args['sort']);
		if(!\dash\app::isset_request('type')) unset($args['type']);
		if(!\dash\app::isset_request('status')) unset($args['status']);
		if(!\dash\app::isset_request('file')) unset($args['file']);


		if(!empty($args))
		{
			$update = \lib\db\lm_group::update($args, $id);

			$title = isset($args['title']) ? $args['title'] : T_("LearnGroup");

			\dash\log::set('editLearnGroup', ['code' => $id]);

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
			\dash\notif::error(T_("lm_group id not set"));
			return false;
		}

		$get = \lib\db\lm_group::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid lm_group id"));
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

		$result            = \lib\db\lm_group::search($_string, $_args);
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
		if(!$title)
		{
			\dash\notif::error(T_("Please fill the title"), 'title');
			return false;
		}

		if(mb_strlen($title) > 300)
		{
			\dash\notif::error(T_("Please fill the title less than 300 character"), 'title');
			return false;
		}

		$check_duplicate = \lib\db\lm_group::get(['title' => $title, 'limit' => 1]);
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

		$status = \dash\app::request('status');
		if($status && !in_array($status, ['enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire']))
		{
			\dash\notif::error(T_("Invalid status"), 'status');
			return false;
		}

		$desc    = \dash\app::request('desc');
		$file    = \dash\app::request('file');

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



		$args           = [];
		$args['title']  = $title;
		$args['status'] = $status;
		$args['desc']   = $desc;
		$args['file']   = $file;
		$args['sort']   = $sort;
		$args['type']   = $type;

		return $args;
	}



	public static function ready($_data)
	{
		$result = [];
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
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
					if(\dash\url::content() != 'm')
					{
						if(!$value)
						{
							$value = \dash\app::static_logo_url();
						}
					}
					$result[$key] = $value;
					break;

				case 'type':
					$result[$key] = $value;
					if($value)
					{
						$result['type_title'] = self::type_list($value, 'title');
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

		return $result;
	}
}
?>