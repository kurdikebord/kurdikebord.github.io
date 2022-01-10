<?php
namespace content\contact;


class view extends \content_support\ticket\contact_ticket\view
{
	public static function config()
	{
		\dash\data::page_title(T_('Contact Us'));
		\dash\data::page_desc(T_("Help us improve SalamQuran project"));
		self::codeurl();
	}
}
?>