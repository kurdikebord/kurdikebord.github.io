<?php
namespace content\donate;


class model
{
	public static function post()
	{
		$post           = [];
		$post['name']   = \dash\request::post('name');
		$post['mobile'] = \dash\request::post('mn');
		$post['amount'] = \dash\request::post('amount');
		$post['url']    = \dash\request::post('url');

		\lib\app\donate::add($post);
	}
}
?>