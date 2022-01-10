<?php
namespace content_m\group\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit learn group"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to group list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\lm_group::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid group id"));
		}

		\dash\data::dataRow($result);

		\dash\data::typeList(\lib\app\lm_group::type_list());


	}
}
?>