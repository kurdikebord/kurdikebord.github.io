<?php
namespace content_m\khatm\edit;


class model
{
	public static function post()
	{
		$post =
		[
			// 'repeat'  => \dash\request::post('repeat'),
			'niyat'   => \dash\request::post('niyat'),
			'status'  => \dash\request::post('status'),
			'privacy' => \dash\request::post('privacy'),
			// 'range'   => \dash\request::post('range'),
			// 'type'    => \dash\request::post('type'),
			// 'sura'    => \dash\request::post('sura'),
			'status'    => \dash\request::post('status'),
		];



		\lib\app\khatm::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::to(\dash\url::this());

			// \dash\redirect::pwd();
		}


	}
}
?>