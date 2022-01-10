<?php
namespace content_m\mag\home;


class view
{
	public static function config()
	{
		$get_post_counter_args = [];
		$filterArray           = [];

		$args   = [];

		$option =
		[
			'sort'  => \dash\request::get('sort'),
			'order' => \dash\request::get('order'),
		];

		if(!$option['order'])
		{
			$option['order'] = 'DESC';
		}

		if(!$option['sort'])
		{
			$option['sort'] = 'id';
		}

		if(\dash\request::get('status'))
		{
			$args['status'] = \dash\request::get('status');
			$filterArray['status'] = $args['status'];
		}

		if(\dash\request::get('type'))
		{
			$args['type']        = \dash\request::get('type');
			$filterArray['type'] = $args['type'];
		}

		if(\dash\request::get('magpage'))
		{
			$args['page']        = \dash\request::get('magpage');
			$filterArray['page'] = $args['page'];
		}

		if(\dash\request::get('sura'))
		{
			$args['sura']        = \dash\request::get('sura');
			$filterArray['sura'] = $args['sura'];
		}

		if(\dash\request::get('subtype'))
		{
			$args['subtype']        = \dash\request::get('subtype');
			$filterArray['Type'] = $args['subtype'];
		}

		if(\dash\request::get('aya'))
		{
			$args['aya']        = \dash\request::get('aya');
			$filterArray['aya'] = $args['aya'];
		}

		if(\dash\request::get('post_id'))
		{
			$args['post_id']        = \dash\coding::decode(\dash\request::get('post_id'));
			$filterArray['Post'] = 1;
		}

		if(\dash\request::get('type'))
		{
			$args['type'] = \dash\request::get('type');
		}

		$dataTable = \lib\app\mag::list(null, $args, $option);

		\dash\data::dataTable($dataTable);

		if(isset($filterArray['Post']) && isset($dataTable[0]['title']))
		{
			$filterArray['Post'] = $dataTable[0]['title'];
		}

		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg(null, $filterArray);
		\dash\data::dataFilter($dataFilter);

	}
}
?>