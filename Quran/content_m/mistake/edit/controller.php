<?php
namespace content_m\mistake\edit;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mMistakeEdit');
	}
}
?>