<?php
namespace lib\app;


class rub
{
	public static function list()
	{
		$addr = root. '/content_api/v6/hizb/rub.json';
		$get = [];
		if(is_file($addr))
		{
			$get = \dash\file::read($addr);
			$get = json_decode($get, true);
			if(!is_array($get))
			{
				$get = [];
			}
		}
		return $get;
	}


	public static function detail($_id, $_field = null)
	{
		$get = self::list();

		if(isset($get[$_id]))
		{
			if(!$_field)
			{
				return $get[$_id];
			}
			elseif(isset($get[$_id][$_field]))
			{
				return $get[$_id][$_field];
			}
			else
			{
				return null;
			}
		}

		return null;
	}
}
?>