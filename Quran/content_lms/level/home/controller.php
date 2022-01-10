<?php
namespace content_lms\level\home;

class controller
{

	public static function routing()
	{
		if(\dash\url::child() === 'next' && \dash\request::get('id'))
		{
			$find_next_level = \lib\app\lm_level::find_next_level(\dash\request::get('id'));

			if(isset($find_next_level['id']) && isset($find_next_level['xtype']))
			{
				\dash\redirect::to(\dash\url::this(). '/'. $find_next_level['xtype']. '?id='. $find_next_level['id']);
			}
		}

		if(\dash\url::child() === 'repeat' && \dash\request::get('id'))
		{
			$repeat = \lib\app\lm_level::get(\dash\request::get('id'));
			if(isset($repeat['id']) && isset($repeat['xtype']))
			{
				\dash\redirect::to(\dash\url::this(). '/'. $repeat['xtype']. '?id='. $repeat['id']);
			}
		}

		\dash\redirect::to(\dash\url::here());
	}
}
?>