<?php
namespace content_m\level\home;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelView');
	}
}
?>