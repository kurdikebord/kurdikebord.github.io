<?php
namespace content_m\level\setting;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Setting"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('cogs');

		\content_m\level\main::view();

		\dash\data::badgeList(\lib\badge::list());
	}
}
?>