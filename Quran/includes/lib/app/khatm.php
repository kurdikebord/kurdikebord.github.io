<?php
namespace lib\app;

/**
 * Class for khatm.
 `user_id` int(10) UNSIGNED NOT NULL,
`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`niyat` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` enum('page', 'juz') NULL DEFAULT NULL,
`range` enum('quran', 'sura') NULL DEFAULT NULL,
`privacy` enum('public', 'private') NULL DEFAULT NULL,
`repeat` int(10) UNSIGNED NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire', 'done')  NULL DEFAULT NULL,
`sura` smallint(3) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
 */

class khatm
{
	public static $sort_field =
	[
		'id',
		'user_id',
		'title',
		'niyat',
		'type',
		'range',
		'privacy',
		'repeat',
		'status',
		'sura',
	];


	public static function check_valid($_id)
	{
		if(!$_id)
		{
			return false;
		}

		$id = \dash\coding::decode($_id);

		if(!$id)
		{
			return false;
		}

		$load = \lib\db\khatm::get(['id' => $id, 'limit' => 1]);
		if(!$load || !isset($load['id']))
		{
			return false;
		}


		// khatm is private
		if($load['privacy'] === 'private')
		{
			// khatm for another
			if(intval($load['user_id']) !== intval(\dash\user::id()))
			{
				// user not supevisor
				return false;
			}
		}

		$desc = '';
		if($load['range'] === 'sura')
		{
			$desc .= ' '. T_('To take part in the khatm, you must read one time :val of the Holy Quran', ['val' => T_(\lib\app\sura::detail($load['sura'], 'tname'))]);
		}
		elseif($load['range'] === 'quran')
		{
			$desc .= ' '. T_('To take part in the khatm, you must read a :val of the Holy Quran', ['val' => T_(ucfirst($load['type']))]);
		}

		$load['desc'] = $desc;

		$load = self::ready($load);
		return $load;
	}


	public static function site_start($_id)
	{
		$load = self::check_valid($_id);

		if(!$load)
		{
			return false;
		}

		if(!in_array($load['status'], ['awaiting', 'running']))
		{
			return false;
		}

		$id          = \dash\coding::decode($_id);

		$check_uages = \lib\app\khatmusage::check_remain($_id, $load);

		if(!$check_uages)
		{
			return false;
		}

		return $load;
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
			$args['status']  = 'awaiting';
		}

		$args['user_id'] = \dash\user::id();

		$check_duplicate = \lib\db\khatm::check_user_active_record(\dash\user::id(), $args['range'], $args['type']);

		if($check_duplicate)
		{
			\dash\notif::error(T_("You can not have more than 1 active khatm"));
			return false;
		}

		$khatm_id = \lib\db\khatm::insert($args);

		if(!$khatm_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($khatm_id);

		if(\dash\engine\process::status())
		{
			\dash\log::set('addNewKhatm', ['code' => $khatm_id]);
			\dash\notif::ok(T_("Khatm successfuly added"));
		}

		\lib\badge::set('AddFirstKhatm');

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

		if(!\dash\app::isset_request('user_id')) unset($args['user_id']);
		if(!\dash\app::isset_request('title')) unset($args['title']);
		if(!\dash\app::isset_request('niyat')) unset($args['niyat']);
		if(!\dash\app::isset_request('type')) unset($args['type']);
		if(!\dash\app::isset_request('range')) unset($args['range']);
		if(!\dash\app::isset_request('privacy')) unset($args['privacy']);
		if(!\dash\app::isset_request('repeat')) unset($args['repeat']);
		if(!\dash\app::isset_request('status')) unset($args['status']);
		if(!\dash\app::isset_request('sura')) unset($args['sura']);


		if(!empty($args))
		{
			$update = \lib\db\khatm::update($args, $id);

			$title = isset($args['title']) ? $args['title'] : T_("Khatm");

			\dash\log::set('editKhatm', ['code' => $id]);

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
			\dash\notif::error(T_("khatm id not set"));
			return false;
		}

		$get = \lib\db\khatm::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid khatm id"));
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

		$result            = \lib\db\khatm::search($_string, $_args);
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
		if($title && mb_strlen($title) > 300)
		{
			\dash\notif::error(T_("Please fill the title less than 300 character"), 'title');
			return false;
		}

		$niyat = \dash\app::request('niyat');
		if($niyat && mb_strlen($niyat) > 300)
		{
			\dash\notif::error(T_("Please fill the niyat less than 300 character"), 'niyat');
			return false;
		}

		$check_duplicate = \lib\db\khatm::get(['user_id' => \dash\user::id(), 'status' => ["IN", " ('awaiting') "], 'limit' => 1]);
		if(isset($check_duplicate['id']))
		{
			if(intval($_id) === intval($check_duplicate['id']))
			{
				// no problem to edit it
			}
			else
			{
				$code = \dash\coding::encode($check_duplicate['id']);
				$msg = T_("You can't expect more than one awaiting khatm");
				// \dash\notif::error($msg, 'title');
				// return false;
			}
		}

		$range = \dash\app::request('range');
		if(!$range)
		{
			\dash\notif::error(T_("Plase set range"));
			return false;
		}

		if($range && !in_array($range, ['quran', 'sura']))
		{
			\dash\notif::error(T_("Invalid range"), 'range');
			return false;
		}

		$type = \dash\app::request('type');
		if(!$type && $range === 'quran')
		{
			\dash\notif::error(T_("Plase set type"));
			return false;
		}

		if($type && !in_array($type, ['page', 'juz']))
		{
			\dash\notif::error(T_("Invalid type"), 'type');
			return false;
		}


		$privacy = \dash\app::request('privacy');
		if(!$privacy)
		{
			\dash\notif::error(T_("Plase set privacy"));
			return false;
		}

		if($privacy && !in_array($privacy, ['public', 'private']))
		{
			\dash\notif::error(T_("Invalid privacy"), 'privacy');
			return false;
		}


		$status = \dash\app::request('status');
		if($status && !in_array($status, ['enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire', 'done', 'running']))
		{
			\dash\notif::error(T_("Invalid status"), 'status');
			return false;
		}

		$repeat = \dash\app::request('repeat');
		$repeat = \dash\utility\convert::to_en_number($repeat);
		if($repeat && !is_numeric($repeat))
		{
			\dash\notif::error(T_("Please set the repeat as a number"), 'repeat');
			return false;
		}

		if($repeat)
		{
			$repeat = intval($repeat);
			$repeat = abs($repeat);
		}

		if($repeat && intval($repeat) > 40)
		{
			\dash\notif::error(T_("Repeat is out of range!"), 'repeat');
			return false;
		}

		if(!$repeat)
		{
			$repeat = 1;
		}



		$sura = \dash\app::request('sura');
		$sura = \dash\utility\convert::to_en_number($sura);
		if($sura && !is_numeric($sura))
		{
			\dash\notif::error(T_("Please set the sura as a number"), 'sura');
			return false;
		}

		if($sura)
		{
			$sura = intval($sura);
			$sura = abs($sura);
		}

		if($sura && intval($sura) > 114)
		{
			\dash\notif::error(T_("Sura index is out of range!"), 'sura');
			return false;
		}

		if($sura && intval($sura) < 1)
		{
			\dash\notif::error(T_("Sura index is out of range!"), 'sura');
			return false;
		}

		if($range === 'sura' && !$sura)
		{
			\dash\notif::error(T_("Please set surah"));
			return false;
		}

		if($range !== 'sura' && $sura)
		{
			$sura = null;
		}

		if($privacy === 'public' && $range === 'quran')
		{
			if(intval($repeat) > 3)
			{
				\dash\notif::error(T_("Maximum repeat in this mode is 3 (public khatm for whole quran)"), 'repeat');
				return false;
			}
		}


		if($range === 'quran')
		{
			$sura = null;
			$repeat = 1;
		}

		if($range === 'sura')
		{
			$type = null;
		}



		$args            = [];
		$args['title']   = $title;
		$args['niyat']   = $niyat;
		$args['type']    = $type;
		$args['range']   = $range;
		$args['privacy'] = $privacy;
		$args['status']  = $status;
		$args['repeat']  = $repeat;
		$args['sura']    = $sura;
		return $args;
	}


	public static function ready($_data)
	{
		$result         = [];
		$title          = '';
		$count_complete = 0;
		$repeat         = 0;

		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
					$title .= T_("Khatm"). ' #'. \dash\utility\human::fitNumber((100 + intval($value)));
				case 'user_id':
				case 'khatm_id':
					if(intval($value) === intval(\dash\user::id()))
					{
						$result['mykhatm'] = true;
					}
					else
					{
						$result['mykhatm'] = false;
					}

					if(isset($value))
					{
						$result[$key] = \dash\coding::encode($value);
					}
					else
					{
						$result[$key] = null;
					}
					break;

				case 'status':
					$result[$key] = $value;
					$result['t'. $key] = T_(ucfirst($value));
					break;

				case 'type':
					$result[$key] = $value;
					$result['t_'. $key] = T_(ucfirst($value));

					if($value === "page")
					{
						$result['t'. $key] = T_("Read quran page by page");
						$repeat = 604;
					}

					if($value === "juz")
					{
						$result['t'. $key] = T_("Read quran juz by juz");
						$repeat = 30;
					}
					break;

				case 'count_complete':
					$result[$key] = $value;
					$count_complete = intval($value);
					break;

				case 'repeat':
					$result[$key] = $value;
					if(!$repeat)
					{
						$repeat = intval($value);
					}
					break;

				case 'range':
					$result[$key] = $value;
					$result['t_'. $key] = T_(ucfirst($value));
					if($value === "quran") $result['t'. $key] = T_("The whole Quran");
					if($value === "sura")  $result['t'. $key] = T_("Special Surah");
					break;

				case 'privacy':
					$result[$key] = $value;
					$result['t_'. $key] = T_(ucfirst($value));
					if($value === "public")  $result['t'. $key] = T_("Public - Everyone can see it");
					if($value === "private") $result['t'. $key] = T_("Private - Only you can see it");
					break;

				case 'sura':
					$result[$key] = $value;
					if($value)
					{
						$result['sura_name'] = T_(\lib\app\sura::detail($value, 'tname'));
					}
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		// count_complete
		if($repeat)
		{
			$complete = floor((100* $count_complete) / $repeat);
			$result['complete_percent'] = $complete;
		}
		$result['title'] = $title;

		return $result;
	}



	public static function remove($_khatm_id)
	{
		$khatm_id = \dash\coding::decode($_khatm_id);
		if(!$_khatm_id)
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		$check = \lib\db\khatm::get(['id' => $khatm_id, 'user_id' => \dash\user::id(), 'limit' => 1]);
		if(!$check || !isset($check['id']))
		{
			\dash\notif::error(T_("This is not your data"));
			return false;
		}

		if(isset($check['status']) && !in_array($check['status'], ['awaiting', 'running']))
		{
			\dash\notif::error(T_("Can not delete this khatm"));
			return false;
		}

		\lib\db\khatm::update(['status' => 'deleted'], $khatm_id);
		\dash\notif::ok(T_("Your khatm was removed"));
		return true;
	}


	public static function public_list($_args = [])
	{
		if(isset($_args['my']) && $_args['my'])
		{
			$args =
			[
				'user_id'    => \dash\user::id(),
				'status'     => ["NOT IN", "('deleted')"],
				'pagenation' => false,
				'order'      => 'desc',
				'sort'       => 'id',
			];

		}
		else
		{

			$args =
			[
				'status'     => ["NOT IN", "('deleted')"],
				'privacy'    => 'public',
				'pagenation' => false,
				'order'      => 'desc',
				'sort'       => 'id',
			];

		}


		$list = self::list(null, $args);
		return $list;
	}
}
?>