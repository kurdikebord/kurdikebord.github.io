<?php
namespace content_api\v6\nav;


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

		if(!$subchild || !in_array($subchild, ['quick']))
		{
			\content_api\v6::no(404);
		}

		if(count(\dash\url::dir()) > 3)
		{
			\content_api\v6::no(404);
		}

		$data = self::quick_access();

		\content_api\v6::bye($data);
	}

	private static function quick_access()
	{
		return \lib\app\quick_access::list();
	}



}
?>