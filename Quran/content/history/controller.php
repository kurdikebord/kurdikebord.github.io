<?php
namespace content\history;

class controller
{
	public static function routing()
	{
		// save history
		if(\dash\request::is('post') && \dash\request::post('aye'))
		{
			\lib\app\history::save(\dash\request::post('aye'));
			\dash\code::end();
		}

		\dash\redirect::to(\dash\url::kingdom());
	}
}
