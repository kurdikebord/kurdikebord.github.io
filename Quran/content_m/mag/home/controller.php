<?php
namespace content_m\mag\home;

class controller
{

	public static function routing()
	{
		\dash\permission::access('mMagConnectView');
	}
}
?>