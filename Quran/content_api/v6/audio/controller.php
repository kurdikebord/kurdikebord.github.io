<?php
namespace content_api\v6\audio;


class controller
{
	public static function routing()
	{
		if(count(\dash\url::dir()) > 3)
		{
			\content_api\v6::no(404);
		}

		if(!in_array(\dash\url::subchild(), ['edit']))
		{
			\content_api\v6::no(404);
		}

		\content_api\v6::check_apikey();

		$child = \dash\url::child();

		if(!$child)
		{
			\content_api\v6::no(404);
		}


		switch ($child)
		{
			case 'audio':
				if(!\dash\url::subchild())
				{
					$data = self::audio();
				}
				elseif(\dash\url::subchild() === 'edit')
				{
					if(!\dash\request::is('post'))
					{
						\content_api\v6::no(404);
					}
					else
					{
						$data = self::edit_audio();
					}
				}
				break;

			default:
				\content_api\v6::no(404);
				break;
		}

		\content_api\v6::bye($data);
	}


	private static function audio()
	{
		\dash\permission::access('mAudioFileView');
		$args          = [];
		$args['order'] = 'DESC';
		$args['sort']  = 'id';
		$dataTable = \lib\app\lm_audio::list(null, $args);
		return $dataTable;
	}


	private static function edit_audio()
	{
		\dash\permission::access('mAudioFileEdit');
		if(!\dash\request::post())
		{
			\dash\notif::error(T_("Your request was empty!"));
			return false;
		}
		$quality = intval(\dash\request::post('quality'));

		$post =
		[
			'teachertxt' => \dash\request::post('answer'),
			'status'     => \dash\request::post('status'),
			'quality'    => $quality,
			'teacher'    => \dash\user::id(),
		];

		$all_post    = \dash\request::post();
		$mistake = [];

		foreach ($all_post as $key => $value)
		{
			if(substr($key, 0, 8) === 'mistake_')
			{
				$mistake[substr($key, 8)] = $value;
			}
		}


		$teacheraudio = \dash\app\file::upload_quick('teacheraudio');

 		if($teacheraudio)
		{
			$post['teacheraudio'] = $teacheraudio;
		}

		if($mistake)
		{
			\lib\app\lm_audiomistake::set($mistake, \dash\request::post('id'));
		}

		$post['mistake'] = $mistake;

		$post['set_star'] = true;

		\lib\app\lm_audio::edit($post, \dash\request::post('id'));
	}

}
?>