<?php
namespace content_m\level\media;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelAdd');
	}
}
?>