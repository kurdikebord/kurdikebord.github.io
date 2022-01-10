<?php
namespace content_m\khatm\home;


class view
{
	public static function config()
	{
		\dash\permission::access('mKhatmView');

		\dash\data::page_title(T_("Khatm quran"));
		\dash\data::page_pictogram('bookmark-o');


		\dash\data::badge_text(T_("Back to dashboard"));
		\dash\data::badge_link(\dash\url::this());

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




		if(\dash\request::get('sura'))
		{
			$args['sura'] = \dash\request::get('sura');
		}



		if(\dash\request::get('status'))
		{
			$args['status'] = \dash\request::get('status');
		}


		if(\dash\request::get('range'))
		{
			$args['range'] = \dash\request::get('range');
		}

		if(\dash\request::get('type'))
		{
			$args['type'] = \dash\request::get('type');
		}

		if(\dash\request::get('repeat'))
		{
			$args['repeat'] = \dash\request::get('repeat');
		}

		if(\dash\request::get('privacy'))
		{
			$args['privacy'] = \dash\request::get('privacy');
		}



		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\khatm::$sort_field, \dash\url::this());
		$dataTable = \lib\app\khatm::list(\dash\request::get('q'), $args);

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