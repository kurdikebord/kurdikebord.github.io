<?php
namespace content_m\khatm\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit khatm"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to khatm list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\khatm::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid khatm id"));
		}

		\dash\data::dataRow($result);

		\dash\data::quranListQuick(\lib\app\sura::quick_list());




	}
}
?>