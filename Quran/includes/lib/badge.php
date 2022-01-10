<?php
namespace lib;


class badge
{
	public static function list($_key = null)
	{
		$list                      = [];

		$list['OpenMag']           = ['score' => 10, 'title' => T_("First open magazine"), 'class' => 'warn'];
		$list['OpenAudioBank']     = ['score' => 20, 'title' => T_("First open audio bank"), 'class' => 'warn'];
		$list['ReadFirstAya']      = ['score' => 30, 'title' => T_("Read first aya"), 'class' => 'warn'];
		// $list['ReadFirstSura']     = ['score' => 2, 'title' => T_("Read first sura"), 'class' => 'warn'];
		$list['AddFirstKhatm']     = ['score' => 40, 'title' => T_("Add first khatm"), 'class' => 'warn'];
		$list['JoinFirstKhatm']    = ['score' => 50, 'title' => T_("First join khatm"), 'class' => 'warn'];
		$list['LmsStartLevel']     = ['score' => 15, 'title' => T_("Start first level in LMS"), 'class' => 'warn'];
		$list['LmsFirstScore']     = ['score' => 25, 'title' => T_("Get first score in LMS"), 'class' => 'warn'];
		$list['LmsFirstFullScore'] = ['score' => 35, 'title' => T_("Get first full score in LMS"), 'class' => 'warn'];

		if($_key)
		{
			if(isset($list[$_key]))
			{
				return $list[$_key];
			}
			else
			{
				return null;
			}
		}
		else
		{
			return $list;
		}
	}


	public static function set($_caller)
	{
		// user not login
		if(!\dash\user::id())
		{
			return false;
		}

		$list = self::list();

		// invalid caller
		if(!array_key_exists($_caller, $list))
		{
			return false;
		}

		// this user get this badge before
		if(self::get_before($_caller))
		{
			return false;
		}

		$insert =
		[
			'badge'       => $_caller,
			'user_id'     => \dash\user::id(),
			'datecreated' => date("Y-m-d H:i:s"),
		];

		// add new badge
		\lib\db\badgeusage::insert($insert);

		self::update_user_badge();

	}

	public static function have($_caller)
	{
		return self::get_before($_caller);
	}


	public static function person_list()
	{
		$count = \lib\db\badgeusage::get_group_by();
		$list = self::list();
		foreach ($list as $key => $value)
		{
			if(isset($count[$key]))
			{
				$list[$key]['person'] = $count[$key];
			}
		}

		return $list;
	}


	private static function get_before($_caller)
	{
		if(!isset($_SESSION['USER_BADGE']))
		{
			self::update_user_badge();
		}

		if(isset($_SESSION['USER_BADGE']) && is_array($_SESSION['USER_BADGE']) && in_array($_caller, $_SESSION['USER_BADGE']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	private static function update_user_badge()
	{
		$list                   = \lib\db\badgeusage::get_user_list(\dash\user::id());
		$_SESSION['USER_BADGE'] = $list;
	}


	public static function user_list($_args)
	{
		$list = \lib\db\badgeusage::search(null, $_args);
		if(!is_array($list))
		{
			$list = [];
		}

		foreach ($list as $key => $value)
		{
			$list[$key]['badge_detail'] = self::list($value['badge']);
		}

		return $list;
	}

}
?>