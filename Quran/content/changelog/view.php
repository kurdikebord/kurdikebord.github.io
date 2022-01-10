<?php
namespace content\changelog;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Change log of SalamQuran'));
		\dash\data::page_desc(T_('We were born to do Best!'). ' ' . T_("We are Developers, please wait!"));
	}
}
?>