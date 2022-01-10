<?php
namespace content\audio;

class controller
{

	public static function routing()
	{
		$child    = \dash\url::child();
		$subchild = \dash\url::subchild();
		$get      = \lib\db\audiobank::get(['addr' => $child. '/'. $subchild, 'limit' => 1]);

		if(isset($get['id']))
		{
			$get = \lib\app\audiobank::ready($get);
			\dash\data::loadAudioFolder($get);
			\dash\open::get();
		}

	}
}
?>