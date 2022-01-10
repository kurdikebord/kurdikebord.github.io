<?php
namespace content_a\khatm\share;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("My share in khatm quran"));
		\dash\data::page_pictogram('book');

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_('Back to dashboard'));


		$dataTable = \lib\app\khatmusage::my_list();
		\dash\data::dataTable($dataTable);
	}
}
?>