<?php
namespace content_api\v6\fav;


class controller
{
	public static function routing()
	{

		if(count(\dash\url::dir()) > 3)
		{
			\content_api\v6::no(404);
		}

		\content_api\v6::check_apikey();

		$subchild = \dash\url::subchild();

		if(in_array($subchild, ['add', 'edit', 'remove']))
		{
			if(!\dash\request::is('post'))
			{
				\content_api\v6::no(400);
			}
		}

		switch ($subchild)
		{
			case 'add':
				$data = self::add();
				break;

			case 'edit':
				$data = self::edit();
				break;

			case 'remove':
				$data = self::remove();
				break;

			case 'list':
				if(!\dash\request::is('get'))
				{
					\content_api\v6::no(400);
				}

				$data = self::list();
				break;

			default:
				\content_api\v6::no(404);
				break;
		}

		\content_api\v6::bye($data);
	}


	private static function list()
	{
		$list = \lib\app\fav::myfav();
		return $list;
	}


	private static function remove()
	{
		$id     = \dash\request::post('id');
		$remove = \lib\app\fav::remove($id);
		return $remove;
	}


	private static function edit()
	{
		$post         = [];
		$post['desc'] = \dash\request::post('desc');
		$post['id']   = \dash\request::post('id');
		$edit         = \lib\app\fav::edit($post);
		return $edit;
	}


	private static function add()
	{
		$post         = [];
		$post['desc'] = \dash\request::post('desc');
		$post['page'] = \dash\request::post('page');
		$post['aya']  = \dash\request::post('aya');
		$post['sura'] = \dash\request::post('sura');
		$post['type'] = \dash\request::post('type');
		$add          = \lib\app\fav::add($post);
		return $add;
	}
}
?>