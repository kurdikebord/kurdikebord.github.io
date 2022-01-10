<?php
namespace content_m\level\exam;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelAdd');
	}
}
?>