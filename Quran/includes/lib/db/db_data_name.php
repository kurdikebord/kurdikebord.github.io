<?php
namespace lib\db;


class db_data_name
{
	public static function get()
	{
		if(defined('db_data_name'))
		{
			return db_data_name;
		}
		return true;
	}
}
?>
