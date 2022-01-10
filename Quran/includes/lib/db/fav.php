<?php
namespace lib\db;


class fav
{

	public static function insert()
	{
		\dash\db\config::public_insert('fav', ...func_get_args());
		return \dash\db::insert_id();
	}

	public static function get_to_remove($_id)
	{
		$query  = "SELECT fav.id FROM fav WHERE fav.id = $_id LIMIT 1";
		$result = \dash\db::get($query, 'id', true);
		return $result;
	}


	public static function remove($_id)
	{
		$query  = "DELETE FROM fav WHERE fav.id = $_id LIMIT 1";
		$result = \dash\db::query($query);
		return $result;
	}


	public static function update()
	{
		return \dash\db\config::public_update('fav', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('fav', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('fav', ...func_get_args());
	}


	public static function search($_string = null, $_where = null, $_option = null)
	{
		$q = [];

		if(isset($_string))
		{
			$_string = \dash\db\safe::value($_string);
			$q[]     = " fav.desc LIKE '%$_string%' ";
		}

		if($_where)
		{
			$q[] = \dash\db\config::make_where($_where);
		}

		if(!empty($q))
		{
			$q = "WHERE ". implode(' AND ', $q);
		}
		else
		{
			$q = null;
		}

		$pagination_query =
		"
			SELECT
				COUNT(*) AS `count`
			FROM
				fav

				$q
		";

		$limit = \dash\db::pagination_query($pagination_query);

		$query =
		"
			SELECT
				*
			FROM
				fav
			$q
			$limit
		";
		$result = \dash\db::get($query);

		return $result;
	}




}
?>
