<?php
namespace content\nav;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Quran Navigation'));

		$quick_access = \lib\app\quick_access::list();

		\dash\data::quranQuickAccess($quick_access);
	}
}
?>