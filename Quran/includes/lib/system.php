<?php
namespace lib;


class system
{
	private static $dir      = root. 'public_html/files/data/';
	private static $filename = 'homepagenumber';
	private static $ext      = '.json';


	public static function set()
	{

		$result =
		[
			'user'       =>  \dash\db\users::get_count(),
			'post'       =>  \dash\db\posts::get_count(['type' => 'post']),
			'lms_lesson' =>  \dash\db\config::public_get_count('lm_level',['status' => 'enable']),
			'khatmusage' =>  \dash\db\config::public_get_count('khatmusage',['status' => 'done']),
			'khatm'      =>  \dash\db\config::public_get_count('khatm',[]),
			'history'    =>  \dash\db\config::public_get_count('history',[]),
		];

		$result = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

		$url    = self::$dir;
		if(!\dash\file::exists($url))
		{
			\dash\file::makeDir($url, null, true);
		}

		$url .= self::$filename. self::$ext;
		if(!\dash\file::exists($url))
		{
			\dash\file::write($url, $result);
		}
		else
		{
			\dash\file::write($url, $result);
		}
	}


	public static function status()
	{
		$url = self::$dir. self::$filename. self::$ext;

		if(is_file($url))
		{
			$filemtime = filemtime($url);
			if(time() - $filemtime > 60*2)
			{
				self::set();
			}
		}
		else
		{
			self::set();
		}

		$result = \dash\file::read($url);
		$result = json_decode($result, true);
		return $result;
	}
}
?>