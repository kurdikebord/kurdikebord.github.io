<?php
namespace content_api\v6\juz;


class controller
{

	public static function routing()
	{
		\content_api\v6\access::check();

		if(!\dash\request::is('get'))
		{
			\content_api\v6::no(400);
		}

		$subchild = \dash\url::subchild();

		if(!$subchild || !in_array($subchild, ['hizb']))
		{
			\content_api\v6::no(404);
		}

		if(count(\dash\url::dir()) > 3)
		{
			\content_api\v6::no(404);
		}

		$data = self::juz_hizb();
		\content_api\v6::bye($data);
	}


	private static function juz_hizb()
	{
		$dir = __DIR__. '/juz-hizb.json';
		if(is_file($dir))
		{
			$get = \dash\file::read($dir);
			$get = json_decode($get, true);
			return $get;
		}

		return null;
	}

}
?>