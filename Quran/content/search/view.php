<?php
namespace content\search;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Search in Quran'));

		\dash\data::page_special(true);

		$query_string = \dash\request::get('q');
		$args         = [];

		$query_string = str_replace(['ک','ی'], ['ك', 'ي'], $query_string);

		$dataTable = \lib\app\quran\search::search($query_string, $args);

		\dash\data::dataTable($dataTable);

	}
}
?>