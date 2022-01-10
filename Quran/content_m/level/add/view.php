<?php
namespace content_m\level\add;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Add new level to learn mechanism"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('plus-circle');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to dashboard'));

		\dash\data::lmGroupList(\lib\app\lm_group::site_list());
		\dash\data::typeList(\lib\app\lm_level::type_list());

	}
}
?>