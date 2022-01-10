<?php
namespace content_m\group\add;


class model
{
	public static function post()
	{
		$post =
		[
			'title' => \dash\request::post('title'),
			'type'  => \dash\request::post('type'),
		];

		$result = \lib\app\lm_group::add($post);

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