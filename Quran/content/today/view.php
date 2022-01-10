<?php
namespace content\today;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Quran for today'));

		$child = \dash\url::child();

		$detail = null;
		if($child === 'aya')
		{
			$detail = \lib\app\aya_day::get();

			if(isset($detail['index']))
			{
				\dash\redirect::to(\dash\url::kingdom(). '/a'. $detail['index']. '?mode=onepage');
			}
		}
		elseif($child === 'page')
		{
			$detail = \lib\app\page_day::get();

			if(isset($detail['page']))
			{
				\dash\redirect::to(\dash\url::kingdom(). '/p'. $detail['page']. '?mode=onepage');
			}
		}


	}
}
?>