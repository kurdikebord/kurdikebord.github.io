<?php
namespace content_m\level\setting;


class model
{
	public static function post()
	{
		$post =
		[
			'ratio'             => \dash\request::post('ratio'),
			'unlockscore'       => \dash\request::post('unlockscore'),
			'desc'              => \dash\request::post('desc'),
			'status'            => \dash\request::post('status'),
			'badge'            => \dash\request::post('badge'),
			'questionrandcount' => \dash\request::post('questionrandcount'),
		];

		\lib\app\lm_level::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}

	}
}
?>