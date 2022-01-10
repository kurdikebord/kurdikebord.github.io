<?php
namespace content\random;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Random load quran'));

		$child = \dash\url::child();

		$detail = null;
		if($child === 'aya')
		{
			$detail = \lib\app\quran::random_aya();

			if(isset($detail['index']))
			{
				\dash\redirect::to(\dash\url::kingdom(). '/a'. $detail['index']. '?mode=onepage');
			}
		}
		elseif($child === 'page')
		{
			$detail = \lib\app\quran::random_page();

			if(isset($detail['url']))
			{
				\dash\redirect::to($detail['url']. '?mode=onepage');
			}
		}


	}
}
?>