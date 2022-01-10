<?php
namespace content\home;

class controller
{
	public static function routing()
	{
		$module   = \dash\url::module();
		$url      = $module;
		$child    = \dash\url::child();
		$subchild = \dash\url::subchild();
		$counDir = \dash\url::dir();
		if(is_array($counDir))
		{
			if(count($counDir) >= 4)
			{
				\dash\header::status(404);
			}
		}


		$frame = false;
		if($subchild)
		{
			if($subchild === 'frame')
			{
				if(is_numeric($child))
				{
					$frame = true;
				}
				else
				{
					\dash\header::status(403);
				}
			}
			else
			{
				\dash\header::status(403);
			}
		}

		if($child === 'frame')
		{
			if($subchild)
			{
				\dash\header::status(403);
			}
			elseif($subchild != '0')
			{
				$child = null;
				$frame = true;
			}
		}

		if($frame)
		{
			$subchild = null;
			\dash\open::get();
		}


		if($child)
		{
			$url .= '/'. $child;
		}


		$meta = [];

		if(\dash\request::get('t'))
		{
			$meta['translate'] = \dash\request::get('t');
		}

		if(\dash\request::get('qari'))
		{
			$meta['qari'] = \dash\request::get('qari');
		}

		if(\dash\request::get('mode'))
		{
			$meta['mode'] = \dash\request::get('mode');
		}

		// $meta = \lib\app\user_setting::get($meta);

		$quran = \lib\app\quran\find::find($url, $meta);

		if($quran)
		{
			\dash\data::sureLoaded(true);
			\dash\data::quranLoaded($quran);
			\dash\open::get();

			// \lib\app\user_setting::save();

			if(isset($quran['detail']))
			{
				\dash\data::suraDetail($quran['detail']);
			}


			if(isset($quran['translate']))
			{
				\dash\data::translateList($quran['translate']);
			}
		}

		if(!$url)
		{
			\dash\data::ayaDay(\lib\app\aya_day::get());
		}
	}
}
?>