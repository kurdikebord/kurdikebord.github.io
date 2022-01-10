<?php
namespace content_api\v6;


class access
{
	public static function check()
	{
		\dash\app\apilog::save_detail(false);
	}
}
?>