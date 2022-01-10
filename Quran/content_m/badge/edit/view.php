<?php
namespace content_m\badge\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit badge"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to badge list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\badge::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid badge id"));
		}

		\dash\data::dataRow($result);

	}
}
?>