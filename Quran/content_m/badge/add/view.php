<?php
namespace content_m\badge\add;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Add new badge"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('plus-circle');

		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to dashboard'));
	}
}
?>