<?php
namespace lib\app;


class quran
{
	public static function load($_type, $_id, $_aye = null, $_meta = [])
	{
		if(in_array($_meta['mode'], ['onepage', 'twopage', 'translatepage']))
		{
			return \lib\app\quran\page::load(...func_get_args());
		}
		else
		{
			return \lib\app\quran\aya::load(...func_get_args());
		}
	}


	public static function day_aya()
	{
		return self::random_aya();
	}


	public static function day_page()
	{
		return self::random_page();
	}


	public static function random_aya()
	{
		$get = \lib\db\quran::get(['1.1' => 1.1, 'limit' => 1], ['order' => ' ORDER BY RAND() ']);
		if(isset($get['sura']) && isset($get['aya']))
		{
			$get['url'] = \dash\url::kingdom(). '/s'. $get['sura']. '/'. $get['aya']. '?mode=aye';
			return $get;
		}

		return null;
	}


	public static function random_page()
	{
		$page = rand(1, 604);
		$get = [];
		$get['url'] = \dash\url::kingdom(). '/p'. $page;
		return $get;
	}

	public static function load_aya_detail($_value, $_meta)
	{
		$verse_title           = null;
		$verse_title           .= T_("Quran");
		$verse_title           .= ' - ';
		$verse_title           .= T_("Sura");
		$verse_title           .= ' ';
		$verse_title           .= \dash\utility\human::fitNumber($_value['sura']). ' '. T_(\lib\app\sura::detail($_value['sura'], 'tname'));
		$verse_title           .= ' - ';
		$verse_title           .= T_("Aya");
		$verse_title           .= ' ';
		$verse_title           .= \dash\utility\human::fitNumber($_value['aya']);

		$verse_url             = \dash\url::kingdom();
		$verse_url             .= '/s'. $_value['sura'];
		$verse_url             .= '/'. $_value['aya']. '?mode=aye';

		$_value['verse_title'] = $verse_title;
		$_value['verse_url']   = $verse_url;
		$_value['audio']       = \lib\app\qari::get_aya_audio($_value['sura'], $_value['aya'], $_meta);
		$_value['audio_key']   = \lib\app\qari::get_aya_audio($_value['sura'], $_value['aya'], $_meta, true);

		return $_value;
	}

	public static function url_query()
	{
		$get = \dash\request::get();
		unset($get['autoplay']);
		if($get)
		{
			return '?'. http_build_query($get);
		}
		else
		{
			return null;
		}
	}


	public static function normalize($_text)
	{
		if(is_callable(['Normalizer', 'normalize']))
		{
			return \Normalizer::normalize($_text);
		}
		return $_text;
	}


	public static function page_juz($_page)
	{
		$get = \dash\file::read(root. 'content_api/v6/page/pagejuz.json');
		$get = json_decode($get, true);
		if(isset($get[$_page]))
		{
			return $get[$_page];
		}
		return null;
	}
}
?>