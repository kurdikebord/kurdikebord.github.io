<?php
namespace lib\app;


class score
{
	public static function user_score_list()
	{
		$list = \lib\db\query::user_score_list();
		if(is_array($list))
		{
			$list = array_map(['\\dash\\app', 'ready'], $list);
		}
		return $list;
	}
}
?>