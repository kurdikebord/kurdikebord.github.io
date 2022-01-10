<?php
namespace content_m\badge\add;


class model
{
	public static function post()
	{
		$post =
		[
			'title'      => \dash\request::post('title'),
		];

		$result = \lib\app\badge::add($post);

		if(\dash\engine\process::status())
		{
			if(isset($result['id']))
			{
				\dash\redirect::to(\dash\url::this(). '/edit?id='.$result['id']);
			}
			else
			{
				\dash\redirect::to(\dash\url::this());
			}
		}

	}
}
?>