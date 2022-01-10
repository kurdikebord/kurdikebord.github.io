<?php
namespace lib\db;


class translate
{
	public static function load($_table_name, $_where)
	{
		$where = \dash\db\config::make_where($_where);
		if(!$where)
		{
			return null;
		}

		$query = "SELECT * FROM `$_table_name` WHERE $where ";
		$result = \dash\db::get($query, null, false, \lib\db\db_data_name::get());
		return $result;
	}
}
?>
