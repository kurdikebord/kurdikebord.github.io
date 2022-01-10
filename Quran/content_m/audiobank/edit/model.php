<?php
namespace content_m\audiobank\edit;


class model
{
	public static function post()
	{

		$post             = [];
		$post['qari']     = \dash\request::post('qari');
		$post['type']     = \dash\request::post('type');
		$post['readtype'] = \dash\request::post('readtype');
		$post['filetype'] = \dash\request::post('filetype');
		$post['country']  = \dash\request::post('country');
		$post['quality']  = \dash\request::post('quality');
		$post['addr']     = \dash\request::post('addr');

		$file = \dash\app\file::upload_quick('avatar');

		if($file)
		{
			$post['avatar'] = $file;
		}

		\lib\app\audiobank::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::to(\dash\url::this());
		}

	}
}
?>