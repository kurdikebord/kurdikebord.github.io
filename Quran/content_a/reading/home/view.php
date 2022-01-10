<?php
namespace content_a\reading\home;


class view
{
	public static function config()
	{

		\dash\data::page_title(T_("Your audio file list"));


		\dash\data::page_pictogram('bullhorn');

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_('Back to dashboard'));

		$search_string            = \dash\request::get('q');
		if($search_string)
		{
			\dash\data::page_title(\dash\data::page_title(). ' | '. T_('Search for :search', ['search' => $search_string]));
		}

		$args =
		[
			'sort'  => \dash\request::get('sort'),
			'order' => \dash\request::get('order'),
		];

		if(!$args['order'])
		{
			$args['order'] = 'DESC';
		}


		if(!$args['sort'])
		{
			$args['sort'] = 'id';
		}

		$args['lm_audio.user_id'] = \dash\user::id();

		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\lm_audio::$sort_field, \dash\url::this());
		$dataTable = \lib\app\lm_audio::list(null, $args);

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);



	}
}
?>