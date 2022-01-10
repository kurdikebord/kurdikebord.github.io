<?php
namespace lib\app;


class page_day
{

	public static function get()
	{
		$result    = self::day_page();
		$load      = [];
		$temp_file = self::temp_file();

		if(isset($temp_file['date']) && $temp_file['date'] == date("Y-m-d"))
		{
			$load = $temp_file;
		}
		else
		{
			if(isset($result['page']))
			{
				$load                = $result;
				$load['date']        = date("Y-m-d");
				self::temp_file($load);
			}
		}

		return $load;
	}


	private static function temp_file($_save = null)
	{
		return self::load_file($_save, 'current-page-day.me.json');
	}


	private static function day_page()
	{
		$date      = date("Y-m-d");
		$saved_page = self::load_file();

		if(isset($saved_page[$date]))
		{
			return $saved_page[$date];
		}
		else
		{
			return self::get_random();
		}
	}


	private static function get_random()
	{
		$saved_page     = self::load_file();
		$loaded_before = array_column($saved_page, 'page');
		$random = [];
		for ($i=1; $i <= 604 ; $i++)
		{
			if(!in_array($i, $loaded_before))
			{
				$random[] = $i;
			}
		}

		if(!$random)
		{
			$random = rand(1, 604);
			\dash\file::rename(\lib\app\json_folder::addr('page-day.me.json') , \lib\app\json_folder::addr('page-day.me.json.old.'.rand(1,200)));
		}
		else
		{
			$random = $random[array_rand($random)];
		}

		$detail =
		[
			'page'  => $random,
		];

		$save_file[date("Y-m-d")] = $detail;
		\dash\file::write(\lib\app\json_folder::addr('current-page-day.me.json'), '');
		self::load_file($save_file);
		return $detail;

	}


	private static function load_file($_save = null, $_fine_name = 'page-day.me.json')
	{
		$addr = \lib\app\json_folder::addr($_fine_name);
		$get  = [];

		if(is_file($addr))
		{
			$get = \dash\file::read($addr);
			$get = json_decode($get, true);
			if(!is_array($get))
			{
				$get = [];
			}
		}


		if($_save && is_array($_save))
		{
			$get = array_merge($get, $_save);
			$get_json = json_encode($get, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			\dash\file::write($addr, $get_json);
		}

		return $get;
	}

}
?>