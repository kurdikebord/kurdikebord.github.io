<?php
namespace content_m\reading\edit;


class controller
{
	public static function routing()
	{
		\dash\permission::access('mAudioFileEdit');

	}
}
?>