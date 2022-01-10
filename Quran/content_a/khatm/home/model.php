<?php
namespace content_a\khatm\home;


class model
{
	public static function post()
	{
		if(\dash\request::post('type') === 'remove' && \dash\request::post('id'))
		{
			$update = \lib\app\khatm::remove(\dash\request::post('id'));
			\dash\redirect::pwd();
			return;
		}



	}
}
?>