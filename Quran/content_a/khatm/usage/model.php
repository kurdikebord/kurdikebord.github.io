<?php
namespace content_a\khatm\usage;


class model
{
	public static function post()
	{
		if(\dash\request::post('status'))
		{
			\lib\app\khatmusage::edit_status(\dash\request::post('status'), \dash\url::subchild());

			if(\dash\engine\process::status())
			{
				\dash\redirect::to(\dash\url::this());
			}
		}
	}
}
?>