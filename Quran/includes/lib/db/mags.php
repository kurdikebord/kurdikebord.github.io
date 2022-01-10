<?php
namespace lib\db;


class mags
{

	public static function insert()
	{
		\dash\db\config::public_insert('mags', ...func_get_args());
		return \dash\db::insert_id();
	}

	public static function get_to_remove($_id)
	{
		$query  = "SELECT mags.id FROM mags WHERE mags.id = $_id LIMIT 1";
		$result = \dash\db::get($query, 'id', true);
		return $result;
	}


	public static function remove($_id)
	{
		$query  = "DELETE FROM mags WHERE mags.id = $_id LIMIT 1";
		$result = \dash\db::query($query);
		return $result;
	}



	public static function find_by_post($_post_id, $_lang)
	{
		$query  =
		"
			SELECT
				mags.*,
				posts.title,
				posts.url
			FROM
				mags
			INNER JOIN posts ON posts.id = mags.post_id
			WHERE
				posts.status = 'publish' AND
				posts.language = '$_lang' AND
				mags.post_id = $_post_id
		";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function get_by_page($_in, $_lang)
	{
		$query  =
		"
			SELECT
				mags.*,
				posts.title,
				posts.url
			FROM
				mags
			INNER JOIN posts ON posts.id = mags.post_id
			WHERE
				posts.status = 'publish' AND
				posts.language = '$_lang' AND
				mags.page IN ($_in)
		";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function update()
	{
		return \dash\db\config::public_update('mags', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('mags', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('mags', ...func_get_args());
	}


	public static function search($_string = null, $_where = null, $_option = null)
	{
		$q = [];

		if(isset($_string))
		{
			$_string = \dash\db\safe::value($_string);
			$q[]     = " posts.title LIKE '%$_string%' ";
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
				mags

				$q
		";

		$limit = \dash\db::pagination_query($pagination_query);

		$query =
		"
			SELECT
				mags.id AS `mag_id`,
				mags.*,
				posts.title,
				posts.url
			FROM
				mags
			INNER JOIN posts ON posts.id = mags.post_id
			$q
			$limit
		";
		$result = \dash\db::get($query);

		return $result;
	}




}
?>
