<?php
namespace lib\app;


class fav
{

	public static function add($_args)
	{
		\dash\app::variable($_args);

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		$user_id = \dash\user::id();
		if(!$user_id)
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		$type  = \dash\app::request('type');
		if(!in_array($type, ['aya', 'sura', 'page']))
		{
			\dash\notif::error(T_("Invalid type"));
			return false;
		}

		$desc = \dash\app::request('desc');
		if($desc && mb_strlen($desc) > 200)
		{
			\dash\notif::error(T_("Plase set the description less than 200 character"), 'desc');
			return false;
		}

		$insert               = [];
		$duplicate            = [];

		$insert['user_id']    = \dash\user::id();
		$duplicate['user_id'] = $insert['user_id'];
		$insert['type']       = $type;
		$duplicate['type']    = $insert['type'];

		$insert['desc']       = $desc;

		switch ($type)
		{
			case 'sura':
				$sura = \dash\app::request('sura');
				$sura = intval($sura);
				if($sura < 1 || $sura > 114)
				{
					\dash\notif::error(T_("Invalid sura id"));
					return false;
				}
				$insert['sura']    = $sura;
				$duplicate['sura'] = $insert['sura'];
				break;

			case 'aya':
				$sura = \dash\app::request('sura');
				$sura = intval($sura);
				if($sura < 1 || $sura > 114)
				{
					\dash\notif::error(T_("Invalid sura id"));
					return false;
				}

				$aya       = \dash\app::request('aya');
				$aya       = intval($aya);
				$sura_ayas = \lib\app\sura::detail($sura, 'ayas');

				if($aya < 1 || $aya > $sura_ayas)
				{
					\dash\notif::error(T_("This sura have :val aya", ['val' => \dash\utility\human::fitNumber($sura_ayas)]));
					return false;
				}

				$insert['sura']    = $sura;
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


			default:
				\dash\notif::error(T_("This method is not supported"));
				return false;
				break;
		}

		if(!empty($insert))
		{
			$duplicate['limit'] = 1;
			$check_duplicate    = \lib\db\fav::get($duplicate);
			if(isset($check_duplicate['id']))
			{
				\dash\notif::error(T_("Duplicate favorites"), ['element' => ['user_id', 'page', 'aya', 'sura']]);
				return false;
			}

			$insert_ok          = \lib\db\fav::insert($insert);
			if($insert_ok)
			{
				\dash\notif::ok(T_("Favorites saved"));
				return true;
			}
		}
	}


	public static function edit($_args)
	{
		\dash\app::variable($_args);

		$id = \dash\app::request('id');
		$id = \dash\coding::decode($id);
		if(!$id)
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		$desc = \dash\app::request('desc');
		if(!$desc && (string) $desc !== '0')
		{
			return null;
		}

		$check =
		[
			'id'      => $id,
			'user_id' => \dash\user::id(),
			'limit'   => 1
		];

		$check = \lib\db\fav::get($check);
		if(!isset($check['id']))
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		$update = ['desc' => $desc];
		\lib\db\fav::update($update, $id);
		\dash\notif::ok(T_("Description saved"));
		return true;
	}

	public static function myfav($_args = null, $_option = null)
	{
		if(!\dash\user::id())
		{
			return null;
		}

		$_args['user_id'] = \dash\user::id();

		return self::list(null, $_args, $_option);
	}


	public static function list($_string = null, $_args = null, $_option = null)
	{
		$list = \lib\db\fav::search($_string, $_args, $_option);
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
				case 'id':
				case 'user_id':
					$result[$key] = \dash\coding::encode($value);
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

		$load = \lib\db\fav::get_to_remove($id);
		if($load)
		{
			\lib\db\fav::remove($id);
			\dash\notif::ok(T_("Favorites removed"));
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