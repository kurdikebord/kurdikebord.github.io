<?php
namespace content_m\mistake\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit learn mistake"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to mistake list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\lm_mistake::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid mistake id"));
		}

		\dash\data::dataRow($result);

	}
}
?>