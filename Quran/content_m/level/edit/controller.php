<?php
namespace content_m\level\edit;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelAdd');
	}
}
?>