<?php
namespace content_a\fav\add;


class model
{
	public static function post()
	{
		if(\dash\request::post('mode') === 'edit')
		{
			$post         = [];
			$post['desc'] = \dash\request::post('desc');
			$post['id'] = \dash\request::post('id');
			$add          = \lib\app\fav::edit($post);
		}
		else
		{

			$post         = [];
			$post['desc'] = \dash\request::post('desc');
			$post['page'] = \dash\request::post('page');
			$post['aya']  = \dash\request::post('aya');
			$post['sura'] = \dash\request::post('sura');
			$post['type'] = \dash\request::post('type');
			$add          = \lib\app\fav::add($post);

		}
	}
}
?>