<?php
namespace content\mag;

class view
{
	public static function config()
	{
		\lib\badge::set('OpenMag');

		\dash\data::display_magAdmin('content/mag/dashboard.html');

		$mag_count = \dash\app\posts::category_count();
		\dash\data::categoryCount($mag_count);
	}
}
?>