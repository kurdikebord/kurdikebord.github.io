<?php
namespace content_m\level\media;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Multimedia"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('movie');

		\content_m\level\main::view();


	}
}
?>