<?php
namespace content_m\level\add;


class model
{
	public static function post()
	{
		$post =
		[
			'title'       => \dash\request::post('title'),
			'lm_group_id' => \dash\request::post('lm_group_id'),
			'type' => \dash\request::post('type'),
		];

		$result = \lib\app\lm_level::add($post);

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