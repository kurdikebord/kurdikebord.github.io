<?php
namespace content_a\khatm\add;


class model
{
	public static function post()
	{
		$post =
		[
			'repeat'  => \dash\request::post('repeat'),
			'niyat'   => \dash\request::post('niyat'),
			'status'  => \dash\request::post('status'),
			'privacy' => \dash\request::post('privacy'),
			'range'   => \dash\request::post('range'),
			'type'    => \dash\request::post('type'),
			'sura'    => \dash\request::post('sura'),
		];



		\lib\app\khatm::add($post);

		if(\dash\engine\process::status())
		{
			\dash\redirect::to(\dash\url::this() . '?user=my');

			// \dash\redirect::pwd();
		}

	}
}
?>