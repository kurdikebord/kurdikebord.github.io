<?php
namespace content_a\khatm\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Khatm quran"));
		\dash\data::page_pictogram('book');

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_('Back to dashboard'));
		// \dash\data::badge_text(T_("Add new khatm"));
		// \dash\data::badge_link(\dash\url::this(). '/add');

		$args = [];
		if(\dash\request::get('user') === 'my')
		{
			$args['my'] = true;
		}

		$dataTable = \lib\app\khatm::public_list($args);
		\dash\data::dataTable($dataTable);



	}
}
?>