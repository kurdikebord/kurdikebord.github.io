<?php
namespace content_a\badge\home;


class view
{
	public static function config()
	{

		\dash\data::page_title(T_("Your badge list"));


		\dash\data::page_pictogram('medal');

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_('Back to dashboard'));



		$args =
		[
			'sort'  => \dash\request::get('sort'),
			'order' => \dash\request::get('order'),
		];

		if(!$args['order'])
		{
			$args['order'] = 'DESC';
		}


		if(!$args['sort'])
		{
			$args['sort'] = 'id';
		}

		$args['user_id'] = \dash\user::id();

		$dataTable = \lib\badge::user_list($args);

		\dash\data::dataTable($dataTable);


		$count = \dash\db\config::public_get_count('history', ['user_id' => \dash\user::id()]);

		$count = intval($count);
		$count = $count * 2;
		\dash\data::myScore($count);

	}
}
?>