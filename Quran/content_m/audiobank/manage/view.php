<?php
namespace content_m\audiobank\manage;

class view
{
	public static function config()
	{
		$addr = root. 'includes/lib/audioconfig.me.json';
		if(is_file($addr))
		{
			\dash\data::dataMeJson_config(\dash\file::read($addr));
		}

		$addr = root. 'content/audio/data.me.json';

		if(is_file($addr))
		{
			// \dash\data::dataMeJson_me(\dash\file::read($addr));
			\dash\data::dataMeJson_me('file exist and can not be display (site of this file is '. filesize($addr). ')');
		}
	}
}
?>