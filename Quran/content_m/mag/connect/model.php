<?php
namespace content_m\mag\connect;

class model
{
	public static function post()
	{
		$post          = [];
		$post['post']  = \dash\request::post('post');
		$post['page']  = \dash\request::post('page');
		$post['aya']   = \dash\request::post('aya');
		$post['surah'] = \dash\request::post('surah');
		$post['type']  = \dash\request::post('connectType');
		$post['word']  = \dash\request::post('word');
		$post['subtype']  = \dash\request::post('subtype');

		$add = \lib\app\mag::add($post);
		if($add)
		{
			\dash\redirect::to(\dash\url::this());
		}

	}
}
?>