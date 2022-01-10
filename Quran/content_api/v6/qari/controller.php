<?php
namespace content_api\v6\qari;


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

		if($subchild)
		{
			\content_api\v6::no(404);
		}

		$data = self::qari_list();

		\content_api\v6::bye($data);
	}

	private static function qari_list()
	{
		$list = \lib\app\qari::site_list();
		$new_list = [];
		foreach ($list as $key => $value)
		{
			$temp       = $value;
			unset($temp['url']);
			$new_list[] = $temp;
		}

		return $new_list;
	}
}
?>