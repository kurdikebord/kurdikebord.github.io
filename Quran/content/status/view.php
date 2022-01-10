<?php
namespace content\status;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('System status'));
		$status = \lib\system::status();

		\dash\data::sysStatus($status);
	}
}
?>