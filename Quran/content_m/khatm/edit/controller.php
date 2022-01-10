<?php
namespace content_m\group\edit;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mKhatmEdit');
	}
}
?>