<?php
namespace content_a\history\home;


class view
{
	public static function config()
	{

		\dash\data::page_title(T_("Your quran reading history"));

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_('Back to dashboard'));

		\dash\data::page_pictogram('history');

		$args =
		[
			'sort'  => \dash\request::get('sort'),
			'order' => \dash\request::get('order'),
		];

		if(!$args['order'])
		{
			$args['order'] = 'ASC';
		}


		if(!$args['sort'])
		{
			$args['sort'] = 'sort';
		}

		$args['user_id'] = \dash\user::id();



		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\history::$sort_field, \dash\url::this());
		$dataTable = \lib\app\history::list(null, $args);

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);

	}
}
?>