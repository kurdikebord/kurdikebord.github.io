<?php
namespace content_lms\level\quran;


class view
{
	public static function config()
	{

		\content_lms\level\main::view();

		$quranLoaded = \lib\app\lm_level::load_quran(\dash\request::get('id'));
		\dash\data::quranLoaded($quranLoaded);
		\dash\data::pageStyle('uthmani');
		\dash\session::set('lms_load_level'. \dash\request::get('id'), time());

	}
}
?>