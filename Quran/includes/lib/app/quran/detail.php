<?php
namespace lib\app\quran;


class detail
{
	public static $sort_field =
	[
		'type',
		'index',
		'startjuz',
		'endjuz',
		'juzcount',
		'startpage',
		'endpage',
		'pagecount',
		'startaya',
		'endaya',
		'ayacount',
		'startsura',
		'endsura',
		'suracount',
		'starthizb',
		'endhizb',
		'hizbcount',
		'startnim',
		'endnim',
		'nimcount',
		'startrub',
		'endrub',
		'rubcount',
		'words',
	];



	public static function db_list($_string, $_args)
	{

		$default_args =
		[
			'order' => null,
			'sort'  => 'index',
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default_args, $_args);

		if($_args['order'])
		{
			if(!in_array($_args['order'], ['asc', 'desc']))
			{
				unset($_args['order']);
			}
		}

		if($_args['sort'])
		{
			if(!in_array($_args['sort'], self::$sort_field))
			{
				$_args['sort'] = 'index';
			}
		}


		$result = \lib\db\quran::search_detail($_string, $_args);

		return $result;
	}



}
?>