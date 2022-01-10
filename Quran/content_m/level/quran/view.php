<?php
namespace content_m\level\quran;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Set start and end of quran to load"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('book');

		\content_m\level\main::view();

		$sura = \lib\app\sura::list();

		\dash\data::quranListQuick($sura);

	}
}
?>