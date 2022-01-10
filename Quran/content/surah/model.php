<?php
namespace content\surah;


class model
{
	public static function post()
	{
		$post          = [];
		$post['desc']  = \dash\request::post('desc');
		$post['type']  = \dash\request::post('type');
		$post['page']  = \dash\request::post('page');
		$post['aya']   = \dash\request::post('aya');
		$post['sura'] = \dash\request::post('sura');
		$add           = \lib\app\fav::add($post);

		\dash\redirect::pwd();
	}
}
?>