<?php
namespace content_api\v6\aya;


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
		if($subchild && !in_array($subchild, ['day']))
		{
			\content_api\v6::no(404);
		}

		switch ($subchild) {
			case 'day':
				$data = self::aya_day();
				break;

			default:
				$data = self::aya();
				break;
		}

		\content_api\v6::bye($data);
	}


	private static function aya_day()
	{
		return \lib\app\aya_day::get(false);
	}


	private static function aya()
	{
		$index = \dash\request::get('index');
		if(!$index)
		{
			\dash\notif::error(T_("Parameter index is required"));
			return false;
		}

		if(!is_numeric($index))
		{
			\dash\notif::error(T_("Id must be a number"));
			return false;
		}

		$index = intval($index);
		if($index < 1 || $index > 6236)
		{
			\dash\notif::error(T_("Id is out of range, Id must between 1 and 6236"));
			return false;
		}

		$load = \lib\db\quran::get(['index' => $index]);
		if(isset($load[0]))
		{
			$load                = $load[0];
			$load['sura_detail'] = \lib\app\sura::detail($load['sura']);

			if(isset($load['sura']) && isset($load['aya']))
			{
				$translate = \lib\app\translate::aya_translate(\dash\language::current(), $load['sura'], $load['aya']);
				$load['translate'] = $translate;
			}
			return $load;
		}
		else
		{
			\dash\notif::error(T_("Id not found"));
			\dash\log::set('apiBugAyaNotFound');
			return false;
		}

	}

}
?>