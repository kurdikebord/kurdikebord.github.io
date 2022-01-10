<?php
namespace content_api\v6\mistake;


class controller
{
	public static function routing()
	{

		if(count(\dash\url::dir()) > 2)
		{
			\content_api\v6::no(404);
		}

		\content_api\v6::check_apikey();

		$child = \dash\url::child();

		if(!$child)
		{
			\content_api\v6::no(404);
		}


		switch ($child)
		{
			case 'mistake':
				$data = self::mistake();
				break;

			default:
				\content_api\v6::no(404);
				break;
		}

		\content_api\v6::bye($data);
	}


	private static function mistake()
	{
		\dash\permission::access('mMistakeView');
		$dataTable = \lib\app\lm_mistake::list(null, []);
		return $dataTable;
	}

}
?>