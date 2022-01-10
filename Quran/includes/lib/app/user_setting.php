<?php
namespace lib\app;


class user_setting
{
	private static $user_setting = [];

	public static function save()
	{

		$mode = \dash\request::get('mode');
		if($mode)
		{
			self::save_for_user('mode', $mode);
		}

		$t = \dash\request::get('t');
		if($t)
		{
			self::save_for_user('translate', $t);
		}

		$qari = \dash\request::get('qari');
		if($qari)
		{
			self::save_for_user('qari', $qari);
		}



	}


	public static function get($_detail = null)
	{
		$saved_setting = \dash\session::get('quran_user_setting');
		if(!is_array($saved_setting) || !$saved_setting)
		{
			return $_detail;
		}

		if(is_array($saved_setting) && is_array($_detail))
		{
			foreach ($saved_setting as $key => $value)
			{
				if(!array_key_exists($key, $_detail))
				{
					$_detail[$key] = $value;
				}
			}
		}
		return $_detail;
	}


	private static function save_for_user($_key, $_value)
	{
		self::$user_setting[$_key] = $_value;
		\dash\session::set('quran_user_setting', self::$user_setting);
	}
}
?>