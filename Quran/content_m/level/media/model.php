<?php
namespace content_m\level\media;


class model
{
	public static function post()
	{
		if(\dash\request::post('type') === 'master')
		{
			self::master();
		}
		elseif(\dash\request::post('type') === 'pic')
		{
			self::pic();

		}
		else
		{
			\dash\notif::error(T_("This method is not supported"));
			return false;
		}
	}


	public static function master()
	{
		$post = [];

		if(\dash\request::post('removeFile'))
		{
			$post =	['file' => null];
		}
		else
		{
			$file = \dash\app\file::upload_quick('file1');

			if($file)
			{
				$post['file'] = $file;
			}
			else
			{
				if(\dash\request::post('fileurl'))
				{
					$post['file'] = \dash\request::post('fileurl');
				}
				else
				{
					\dash\notif::error(T_("Please upload a file"));
					return false;
				}
			}
		}

		\lib\app\lm_level::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}


	public static function pic()
	{
		$post = [];

		if(\dash\request::post('removeFile'))
		{
			$post =	['filepic' => null];
		}
		else
		{
			$filepic = \dash\app\file::upload_quick('file1');

			if($filepic)
			{
				$post['filepic'] = $filepic;
			}
			else
			{
				if(\dash\request::post('filepicurl'))
				{
					$post['filepic'] = \dash\request::post('filepicurl');
				}
				else
				{
					\dash\notif::error(T_("Please upload a file"));
					return false;
				}
			}
		}

		\lib\app\lm_level::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}

	}
}
?>