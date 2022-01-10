<?php
namespace content_m\mag\connect;

class view
{
	public static function config()
	{
		$args             = [];
		$args['type']     = 'post';
		$args['language'] = \dash\language::current();
		$post_list = \dash\app\posts::all_post_title($args);

		\dash\data::postList($post_list);

		\dash\data::badge_text(T_("Back"));
		\dash\data::badge_link(\dash\url::this());

		\dash\data::listSubType(\lib\app\mag::subtype_list());

		if(\dash\url::subchild() === 'word')
		{
			$sura = \dash\request::get('surah');
			$aya  = \dash\request::get('aya');
			if($sura && $aya && is_numeric($sura) && is_numeric($aya))
			{
				$word = \lib\db\quran_word::get(['sura' => $sura, 'aya' => $aya]);
				if($word)
				{
					\dash\data::quranWords($word);
				}
				else
				{
					\dash\notif::error(T_("This sura have not this aya"));
					\dash\redirect::to(\dash\url::that());
				}
			}
		}
	}
}
?>