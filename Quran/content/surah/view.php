<?php
namespace content\surah;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Sura list'));

		$args =
		[
			'order' => \dash\request::get('order'),
			'pagenation' => false,
		];

		if(\dash\request::get('sort'))
		{
			$args['sort'] = \dash\request::get('sort');
		}

		$dataTable = \lib\app\sura::db_list(null, $args);
		\dash\data::dataTable($dataTable);

		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\sura::$sort_field, \dash\url::this());
		\dash\data::sortLink($sortLink);

		$myfav = \lib\app\fav::myfav(['type' => 'sura']);
		if(is_array($myfav))
		{
			$myfav = array_combine(array_column($myfav, 'sura'), $myfav);
			\dash\data::myFav($myfav);
		}

	}
}
?>