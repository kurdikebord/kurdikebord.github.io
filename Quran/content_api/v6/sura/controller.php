<?php
namespace content_api\v6\sura;


class controller
{

	public static function routing()
	{
		\content_api\v6\access::check();

		if(!\dash\request::is('get'))
		{
			\content_api\v6::no(400);
		}

		$wbw = false;

		if(\dash\url::subchild() === 'list')
		{
			if(\dash\url::dir(3))
			{
				\content_api\v6::no(404);
			}

			\content_api\v6::bye(self::list());
		}
		elseif(\dash\url::subchild() === 'wbw')
		{
			if(\dash\url::dir(3))
			{
				\content_api\v6::no(404);
			}

			$wbw = true;
		}
		else
		{
			if(\dash\url::subchild())
			{
				\content_api\v6::no(404);
			}
		}

		$index = \dash\request::get('index');
		if(!$index)
		{
			\content_api\v6::no(400, T_("Index not set"));
		}

		if(!ctype_digit($index) || intval($index) < 1 || intval($index) > 114)
		{
			\content_api\v6::no(400, T_("Invalid index"));
		}

		$limit = \content_api\v6\quran::check_limit();

		$count_aya = 0;

		$get_sure_detail = \lib\db\sura::get(['index' => $index, 'limit' => 1]);

		if(isset($get_sure_detail['ayas']))
		{
			$count_aya = intval($get_sure_detail['ayas']);
		}

		if(!$limit || $limit > 20)
		{
			if($count_aya < 30 )
			{
				$limit = $count_aya;
			}
			else
			{
				$limit = 20;
			}
		}

		$start = \dash\request::get('start');
		if($start && !ctype_digit($start))
		{
			\content_api\v6::no(400, T_("Invalid start"));
		}

		if(!$start)
		{
			$start = 0;
		}

		if($start)
		{
			$start = intval($start);
			if($start < 0)
			{
				\content_api\v6::no(400, T_("Invalid start"));
			}

			if($start > $count_aya)
			{
				\content_api\v6::no(400, T_("Start larger than count aya"));
			}
		}


		if($wbw)
		{
			$sura = \lib\db\quran_word::get(['sura' => $index, 'limit' => $limit, '1.1' => [' = 1.1 AND ', " `aya` >= $start "]]);
		}
		else
		{
			$sura = \lib\db\quran::get(['sura' => $index, 'limit' => $limit, '1.1' => [' = 1.1 AND ', " `aya` >= $start "]]);
		}

		\content_api\v6::bye($sura);
	}


	private static function list()
	{
		$dir = __DIR__. '/sura.json';

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