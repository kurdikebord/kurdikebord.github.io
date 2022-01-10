<?php
namespace content\rub;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Rub list'));
		$args =
		[
			'order' => \dash\request::get('order'),
			'pagenation' => false ,
			'type' => 'rub',
		];

		if(\dash\request::get('sort'))
		{
			$args['sort'] = \dash\request::get('sort');
		}

		$dataTable = \lib\app\quran\detail::db_list(null, $args);
		\dash\data::dataTable($dataTable);

		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\quran\detail::$sort_field, \dash\url::this());
		\dash\data::sortLink($sortLink);
	}
}
?>