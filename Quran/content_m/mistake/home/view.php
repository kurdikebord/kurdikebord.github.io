<?php
namespace content_m\mistake\home;


class view
{
	public static function config()
	{
		\dash\permission::access('mMistakeView');
		\dash\data::page_title(T_("Mistake list"));
		\dash\data::page_desc(T_('Check list and search or filter them.'). ' '. T_('Also add or edit specefic item.'));

		\dash\data::page_pictogram('coffee');

		\dash\data::badge_link(\dash\url::this(). '/add');
		\dash\data::badge_text(T_('Add new mistake'));

		$search_string            = \dash\request::get('q');
		if($search_string)
		{
			\dash\data::page_title(\dash\data::page_title(). ' | '. T_('Search for :search', ['search' => $search_string]));
		}

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

		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\lm_mistake::$sort_field, \dash\url::this());
		$dataTable = \lib\app\lm_mistake::list(\dash\request::get('q'), $args);

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);

		$check_empty_datatable = $args;
		unset($check_empty_datatable['sort']);
		unset($check_empty_datatable['order']);

		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg($search_string, $check_empty_datatable);
		\dash\data::dataFilter($dataFilter);



	}
}
?>