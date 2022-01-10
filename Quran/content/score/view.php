<?php
namespace content\score;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('User score list'));
		\dash\data::userScoreList(\lib\app\score::user_score_list());
	}
}
?>