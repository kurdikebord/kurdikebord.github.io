<?php
namespace content_m\level\quran;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mLevelAdd');
	}
}
?>