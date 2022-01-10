<?php
namespace content_m\level\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit learn level"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\content_m\level\main::view();

		\dash\data::lmGroupList(\lib\app\lm_group::site_list());
		\dash\data::typeList(\lib\app\lm_level::type_list());
	}
}
?>