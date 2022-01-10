<?php
namespace content_a\reading\view;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("View audio"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('bullhorn');

		\dash\data::badge_link(\dash\url::this());

		\dash\data::badge_text(T_('Back to audio list'));

		$id     = \dash\request::get('id');
		$result = \lib\app\lm_audio::get($id);
		if(!$result)
		{
			\dash\header::status(403, T_("Invalid audio id"));
		}

		if(intval(\dash\coding::decode($result['user_id'])) !== intval(\dash\user::id()))
		{
			\dash\header::status(404);
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