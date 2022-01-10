<?php
namespace content_m\level;


class main
{
	public static function view()
	{
		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to level list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\lm_level::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid level id"));
		}


		if(isset($result['lm_group_id']))
		{
			$lm_group_id = \lib\app\lm_group::get($result['lm_group_id']);
			$result['group_detail'] = $lm_group_id;
		}

		\dash\data::dataRow($result);


		\dash\data::page_title(\dash\data::page_title() . ' | '. \dash\data::dataRow_title());

	}
}
?>