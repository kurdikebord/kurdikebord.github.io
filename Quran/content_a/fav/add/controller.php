<?php
namespace content_a\fav\add;


class controller
{
	public static function routing()
	{
		if(!\dash\request::is('post'))
		{
			\dash\header::status(400);
		}

	}
}
?>