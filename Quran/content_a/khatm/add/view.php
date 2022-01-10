<?php
namespace content_a\khatm\add;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Add new khatm"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('book');

		\dash\data::badge_link(\dash\url::here());

		\dash\data::badge_text(T_('Back to dashboard'));

		\dash\data::quranListQuick(\lib\app\sura::quick_list());

		\dash\data::haveBadge(\lib\badge::have('JoinFirstKhatm'));
	}
}
?>