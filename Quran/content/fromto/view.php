<?php
namespace content\fromto;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Load quran from ... to ...'));
		$sura = \lib\app\sura::list();
		\dash\data::quranListQuick($sura);

		if(array_key_exists('surah', $_GET) && array_key_exists('startaya', $_GET) && array_key_exists('endaya', $_GET))
		{
			$sura     = \dash\request::get('surah');
			$startaya = \dash\request::get('startaya');
			$endaya   = \dash\request::get('endaya');

			if(is_numeric($sura) && is_numeric($startaya) && is_numeric($endaya))
			{

				if($sura < 1 || $sura > 114 || $startaya < 1 || $startaya > $endaya  || $endaya > intval(\lib\app\sura::detail($sura, 'ayas')))
				{
					\dash\notif::error(T_("Invalid value"), ['element' => ['startaya', 'endaya']]);
					return false;
				}
				else
				{
					\dash\data::fromtoLink('s'. $sura. '/'. $startaya. '-'. $endaya);
				}

			}
			else
			{
				\dash\notif::error(T_("Please set the value as a number"), ['element' => ['startaya', 'endaya']]);
			}

		}

	}
}

?>