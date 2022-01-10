<?php
namespace content_a\fav\home;


class model
{
	public static function post()
	{
		if(\dash\request::post('action') === 'remove')
		{
			$id     = \dash\request::post('id');

			$remove = \lib\app\fav::remove($id);

			if($remove)
			{
				\dash\redirect::pwd();
			}
		}
	}
}
?>
