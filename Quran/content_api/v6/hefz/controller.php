<?php
namespace content_api\v6\hefz;


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
		if($subchild && !in_array($subchild, ['time']))
		{
			\content_api\v6::no(404);
		}

		if(count(\dash\url::dir()) > 3)
		{
			\content_api\v6::no(404);
		}

		switch ($subchild) {
			case 'time':
				$data = self::by_time();
				break;

			default:
				\content_api\v6::no(404);
				break;
		}

		\content_api\v6::bye($data);
	}

	private static function by_time()
	{
		return \lib\app\hefz::calculator(\dash\request::get());
	}

}
?>