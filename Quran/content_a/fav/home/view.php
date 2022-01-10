<?php
namespace content_a\fav\home;


class view
{
	public static function config()
	{

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

		if(\dash\request::get('type'))
		{
			$args['type']        = \dash\request::get('type');
			$filterArray['type'] = $args['type'];
		}

		if(\dash\request::get('favpage'))
		{
			$args['page']        = \dash\request::get('favpage');
			$filterArray['page'] = $args['page'];
		}

		if(\dash\request::get('sura'))
		{
			$args['sura']        = \dash\request::get('sura');
			$filterArray['sura'] = $args['sura'];
		}

		if(\dash\request::get('aya'))
		{
			$args['aya']        = \dash\request::get('aya');
			$filterArray['aya'] = $args['aya'];
		}

		$args['user_id']        = \dash\user::id();

		if(\dash\request::get('type'))
		{
			$args['type'] = \dash\request::get('type');
		}

		$dataTable = \lib\app\fav::list(null, $args, $option);

		\dash\data::dataTable($dataTable);

		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg(null, $filterArray);
		\dash\data::dataFilter($dataFilter);

	}
}
?>