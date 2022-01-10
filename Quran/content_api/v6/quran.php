<?php
namespace content_api\v6;


class quran
{
	public static function check_limit()
	{
		\content_api\v6\access::check();

		$limit = \dash\request::get('limit');
		if($limit && !ctype_digit($limit))
		{
			\content_api\v6::no(400, T_("Invalid limit"));
		}

		if($limit)
		{
			$limit = intval($limit);

			if($limit < 0)
			{
				\content_api\v6::no(400, T_("Invalid limit"));
			}

			return $limit;
		}

		return null;
	}
}
?>