<?php
namespace lib\app;


class json_folder
{

	public static function addr($_file_name = null)
	{
		$addr = __DIR__.  DIRECTORY_SEPARATOR. 'json_temp_file'. DIRECTORY_SEPARATOR;

		$addr = \autoload::fix_os_path($addr);

		if(!is_dir($addr))
		{
			\dash\file::makeDir($addr, null, true);
		}

		return $addr. $_file_name;
	}
}
?>