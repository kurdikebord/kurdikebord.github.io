<?php
namespace content_m\level\add;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelAdd');
	}
}
?>