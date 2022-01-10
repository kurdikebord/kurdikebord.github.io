<?php
namespace content_m\reading\edit;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Edit audio"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('edit');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to audio list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\lm_audio::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid audio id"));
		}

		\dash\data::dataRow($result);

		$mistakeList = \lib\app\lm_mistake::list(null, ['pagenation' => false]);

		\dash\data::mistakeList($mistakeList);

		$savedMistake = \lib\app\lm_audiomistake::saved(\dash\request::get("id"));
		if(is_array($savedMistake))
		{
			$savedMistake = array_column($savedMistake, 'lm_mistake_id');
			\dash\data::savedMistake($savedMistake);
		}

	}
}
?>