<?php
namespace content\present;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Present Quran'));
		\dash\data::page_desc(T_('Present like TV'));

		\dash\data::include_js(false);
		\dash\data::bodyclass('present cover');

	}
}
?>