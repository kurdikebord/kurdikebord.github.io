<?php
namespace content_lms\level\quran;


class model
{
	public static function post()
	{
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		if(\dash\url::child() === 'quran')
		{
			return self::quran();
		}
		elseif(\dash\url::child() === 'iqra')
		{
			return self::iqra();
		}
	}

	private static function iqra()
	{
		$file = \dash\app\file::upload_quick('file');
		if($file)
		{
			\lib\app\lm_audio::add_new($file, \dash\request::get('id'));
			\lib\app\lm_star::set_star(\dash\request::get('id'), 0);
		}


		if(\dash\engine\process::status())
		{
			\dash\redirect::to(\dash\url::this(). '/result?id='. \dash\request::get('id'));
		}

	}


	private static function quran()
	{


		$session_key = 'lms_audio_record'. \dash\request::get('id');

		if(\dash\request::files('file'))
		{
			\dash\session::set($session_key, true);
		}

		$count_listen = intval(\lib\app\lm_level::count_listen(\dash\request::get('id'), \dash\session::get('lms_load_level'. \dash\request::get('id'))));

		$star = 0;
		if($count_listen >= 2)
		{
			$star = 2;
		}
		elseif($count_listen >= 1)
		{
			$star++;
		}

		if(\dash\session::get($session_key))
		{
			$star++;
			\dash\session::clean($session_key);
		}

		\lib\app\lm_star::set_star(\dash\request::get('id'), $star);

		if(\dash\engine\process::status())
		{
			\dash\redirect::to(\dash\url::this(). '/result?id='. \dash\request::get('id'));
		}


	}
}
?>
