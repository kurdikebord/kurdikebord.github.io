<?php
namespace content_m\group\add;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mGroupAdd');
	}
}
?>