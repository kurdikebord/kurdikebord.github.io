<?php
namespace content_lms\level\result;


class model
{
	public static function post()
	{
		if(\dash\request::post('audioid') && \dash\request::post('status'))
		{
			\lib\app\lm_audio::user_change_status(\dash\request::post('audioid'), \dash\request::post('status'));

			if(\dash\engine\process::status())
			{
				\dash\redirect::pwd();
			}
		}
	}
}
?>
