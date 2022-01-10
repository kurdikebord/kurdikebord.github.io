<?php
namespace content_m\mistake\add;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mMistakeAdd');
	}
}
?>