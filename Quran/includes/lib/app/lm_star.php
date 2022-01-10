<?php
namespace lib\app;

/**
 * Class for lm_star.
 */

class lm_star
{
	private static function alert_start($_star)
	{
		if(is_numeric($_star))
		{
			// $star = str_repeat('⭐️', $_star);
			$text = T_("Step successfully saved");
		}
		else
		{
			$text = T_("Step successfully saved");
		}

		\dash\notif::ok($text, ['alerty' => true, 'timeout' => 1000]);

	}


	public static function get_user_group_score($_group_id)
	{
		$id = \dash\coding::decode($_group_id);
		if(!$id)
		{
			return null;
		}

		if(!\dash\user::id())
		{
			return null;
		}

		$group_star = \lib\db\lm_star::group_star($id, \dash\user::id());
		if(is_array($group_star))
		{
			$group_star = array_sum($group_star);
		}
		return intval($group_star);
	}

	public static function level_learn($_type, $_level_id, $_star = null)
	{

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}

		$star = 0;
		switch ($_type)
		{
			case 'showvideo':
				$star = 1;
				break;

			case 'exam':
				$star = intval($_star);
				break;

			default:
				return false;
				break;
		}

		$load_level = \lib\app\lm_level::get($_level_id);

		if(!$load_level || !isset($load_level['lm_group_id']))
		{
			return false;
		}

		$group_id = \dash\coding::decode($load_level['lm_group_id']);

		$args                = [];
		$args['user_id']     = \dash\user::id();
		$args['lm_group_id'] = $group_id;
		$args['lm_level_id'] = \dash\coding::decode($_level_id);
		$args['star']        = $star;
		$args['score']       = 0;
		$args['status']      = 'enable';
		$args['datecreated']  = date("Y-m-d H:i:s");

		$lm_star_id = \lib\db\lm_star::insert($args);

		if(!$lm_star_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($lm_star_id);

		if(\dash\engine\process::status())
		{
			self::alert_start($star);
		}

		return $return;
	}


	public static function set_star_inline($_group_id, $_level_id, $_user_id, $_star)
	{
		$star = intval($_star);
		if($star < 0 || $star > 3)
		{
			\dash\notif::error(T_("Only 1,2,3 can set as star"));
			return false;
		}

		if($star)
		{
			\lib\badge::set('LmsFirstScore');
		}

		if($star === 3)
		{
			\lib\badge::set('LmsFirstFullScore');
		}



		$args                = [];
		$args['user_id']     = $_user_id;
		$args['lm_group_id'] = $_group_id;
		$args['lm_level_id'] = $_level_id;
		$args['star']        = $star;
		$args['score']       = 0;
		$args['status']      = 'enable';
		$args['datecreated']  = date("Y-m-d H:i:s");

		$lm_star_id = \lib\db\lm_star::insert($args);

	}

	public static function set_star($_level_id, $_star = null)
	{

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}


		$star = intval($_star);

		if($star < 0 || $star > 3)
		{
			\dash\notif::error(T_("Only 1,2,3 can set as star"));
			return false;
		}

		if($star)
		{
			\lib\badge::set('LmsFirstScore');
		}


		$load_level = \lib\app\lm_level::get($_level_id);

		if(!$load_level || !isset($load_level['lm_group_id']))
		{
			\dash\notif::error(T_("Invalid level"));
			return false;
		}

		if($star === 3)
		{
			\lib\badge::set('LmsFirstFullScore');
			if(isset($load_level['badge']))
			{
				\lib\badge::set($load_level['badge']);
			}
		}

		$group_id = \dash\coding::decode($load_level['lm_group_id']);

		$args                = [];
		$args['user_id']     = \dash\user::id();
		$args['lm_group_id'] = $group_id;
		$args['lm_level_id'] = \dash\coding::decode($_level_id);
		$args['star']        = $star;
		$args['score']       = 0;
		$args['status']      = 'enable';
		$args['datecreated']  = date("Y-m-d H:i:s");

		$lm_star_id = \lib\db\lm_star::insert($args);

		if(!$lm_star_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		$return['id'] = \dash\coding::encode($lm_star_id);

		if(\dash\engine\process::status())
		{
			self::alert_start($star);
		}

		return $return;
	}



	public static function user_level_star($_level_id, $_last = false)
	{
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}

		$user_id = \dash\user::id();

		$load_level = \lib\app\lm_level::get($_level_id);

		if(!$load_level || !isset($load_level['lm_group_id']))
		{
			return false;
		}

		$level_id = \dash\coding::decode($_level_id);

		if($_last)
		{
			$user_star = \lib\db\lm_star::get_user_star_last($level_id, $user_id);
		}
		else
		{
			$user_star = \lib\db\lm_star::get_user_star($level_id, $user_id);
		}

		$result = [];

		if(isset($user_star['star']))
		{
			$result['star'] = intval($user_star['star']);
		}

		return $result;

	}


}
?>