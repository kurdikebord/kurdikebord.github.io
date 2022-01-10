<?php
namespace content_m\level\home;


class view
{
	public static function config()
	{

		\dash\data::page_title(T_("Level list"));
		\dash\data::page_desc(T_('Check list and search or filter them.'). ' '. T_('Also add or edit specefic item.'));

		\dash\data::page_pictogram('coffee');

		\dash\data::badge_link(\dash\url::this(). '/add');
		\dash\data::badge_text(T_('Add new level'));

		$search_string            = \dash\request::get('q');
		if($search_string)
		{
			\dash\data::page_title(\dash\data::page_title(). ' | '. T_('Search for :search', ['search' => $search_string]));
		}

		$filterArgs = [];
		$args =
		[
			'sort'          => \dash\request::get('sort'),
			'order'         => \dash\request::get('order'),
		];

		if(!$args['order'])
		{
			$args['order'] = 'ASC';
		}


		if(!$args['sort'])
		{
			$args['sort'] = 'sort';
		}

		if(\dash\request::get('status'))
		{
			$args['lm_level.status'] = \dash\request::get('status');
			$filterArgs['status'] = $args['lm_level.status'];
		}

		if(\dash\request::get('type'))
		{
			$args['lm_level.type'] = \dash\request::get('type');
		}


		if(\dash\request::get('groupid'))
		{
			$groupid = \dash\coding::decode(\dash\request::get('groupid'));
			if($groupid)
			{
				$args['lm_level.lm_group_id'] = $groupid;
			}
		}

		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\lm_level::$sort_field, \dash\url::this());
		$dataTable = \lib\app\lm_level::list(\dash\request::get('q'), $args);

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);


		if(isset($args['lm_level.lm_group_id']) && isset($dataTable[0]['group_title']))
		{
			$filterArgs['Group'] = $dataTable[0]['group_title'];
		}

		if(isset($args['lm_level.type']) && isset($dataTable[0]['type_title']))
		{
			$filterArgs['type'] = $dataTable[0]['type_title'];
		}

		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg($search_string, $filterArgs);
		\dash\data::dataFilter($dataFilter);


		$groupCount = \lib\app\lm_level::get_count_group();
		\dash\data::groupCount($groupCount);

		if(is_array($groupCount))
		{
			\dash\data::groupCountAll(array_sum(array_column($groupCount, 'count')));
		}

	}

}
?>