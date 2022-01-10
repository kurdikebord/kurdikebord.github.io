<?php
namespace content_m\audiobank\manage;

class model
{
	public static function post()
	{
		if(\dash\request::post('run') == 'run')
		{

			exec("php ". root. "/includes/lib/audiobank_json.php", $result);
			\dash\notif::info($result);
			\dash\notif::ok(T_('Ok, file created'));
			return;
		}
	}
}
?>