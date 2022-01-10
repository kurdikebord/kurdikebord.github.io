<?php
namespace content_m\mag\home;


class model
{
	public static function post()
	{
		\dash\permission::access('mMagConnectRemove');
		if(\dash\request::post('action') === 'remove')
		{
			$id     = \dash\request::post('id');

			$remove = \lib\app\mag::remove($id);

			if($remove)
			{
				\dash\redirect::pwd();
			}
		}
	}
}
?>
