<?php
namespace content_m\level\exam;


class model
{
	public static function post()
	{
		if(\dash\request::post('ActionType') === 'remove' && \dash\request::post('id'))
		{
			\lib\app\lm_question::remove(\dash\request::post('id'));
			\dash\redirect::pwd();
			return;
		}

		$post                = [];
		$post['title']       = \dash\request::post('title');
		$post['opt1']        = \dash\request::post('opt1');
		$post['opt1file']    = \dash\app\file::upload_quick('opt1file');
		$post['opt2']        = \dash\request::post('opt2');
		$post['opt2file']    = \dash\app\file::upload_quick('opt2file');
		$post['opt3']        = \dash\request::post('opt3');
		$post['opt3file']    = \dash\app\file::upload_quick('opt3file');
		$post['opt4']        = \dash\request::post('opt4');
		$post['opt4file']    = \dash\app\file::upload_quick('opt4file');
		$post['lm_level_id'] = \dash\request::get('id');
		$post['trueopt']     = \dash\request::post('trueopt');

		if(\dash\request::get('qid'))
		{
			\lib\app\lm_question::edit($post, \dash\request::get('qid'));
			if(\dash\engine\process::status())
			{
				\dash\redirect::to(\dash\url::this(). '/exam?id='. \dash\request::get('id'));
			}
		}
		else
		{
			\lib\app\lm_question::add($post);
			if(\dash\engine\process::status())
			{
				\dash\redirect::pwd();
			}
		}


	}
}
?>