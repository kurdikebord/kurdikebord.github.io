<?php
namespace content_m\audiobank\add;

class model
{
	public static function post()
	{
		$post             = [];
		$post['qari']     = \dash\request::post('qari');
		$post['avatar']   = \dash\request::post('avatar');
		$post['type']     = \dash\request::post('type');
		$post['readtype'] = \dash\request::post('readtype');
		$post['filetype'] = \dash\request::post('filetype');
		$post['country']  = \dash\request::post('country');
		$post['quality']  = \dash\request::post('quality');
		$post['addr']     = \dash\request::post('addr');

		\lib\app\audiobank::add($post);
		\dash\redirect::to(\dash\url::this());
	}
}
?>