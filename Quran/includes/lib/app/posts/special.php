<?php
namespace lib\app\posts;


class special
{

	public static function list()
	{
		$list             = [];

		$list['mustread'] = T_("Must read");
		$list['editor']   = T_("Editor selection");
		$list['special']  = T_("Special");
		return $list;
	}


}
?>