<?php
namespace lib\app;


class hefz
{
	public static function calculator($_args)
	{
		\dash\app::variable($_args);

		$mytime = \dash\app::request('mytime');
		if(!$mytime)
		{
			\dash\notif::error(T_("Please set your time"), 'mytime');
			return false;
		}

		$mytime = intval($mytime);
		if($mytime < 0)
		{
			\dash\notif::error(T_("Plase set your time larger than zero!"), 'mytime');
			return false;
		}

		// 60 * 24 = 1440
		if($mytime > (60*24))
		{
			\dash\notif::error(T_("Plase set your time in one day"), 'mytime');
			return false;
		}

		$result = self::hefz($mytime);

		return $result;
	}


	private static function hefz($_time)
	{
		// every line need 15 minutes to hefz it
		$hefz_every_line = 15;

		// quran have 604 page
		// every page have 15 line
		// every sura have one besmellah and one header - title of sura - (114 * 2)
		// one sura have not besmellah (s9) ... - 1);
		// @check j30 page 603 and 604 to set count of line
		$quran_line_count = (604 * 15) - ((114 * 2) - 1);

		$hefz_in_days = $quran_line_count / ($_time / $hefz_every_line);

		if($hefz_in_days > 365)
		{
			$year = intval($hefz_in_days / 365);
			$month = floor(($hefz_in_days % 365) / 30);
		}
		else
		{
			$year = 0;
			$month = floor(($hefz_in_days) / 30);
		}

		if($month == 12)
		{
			$year++;
			$month = 0;
		}

		$result =
		[
			'year'  => $year,
			'month' => $month,
		];

		return $result;

	}
}
?>