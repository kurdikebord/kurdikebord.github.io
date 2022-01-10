<?php
namespace content_a\chart;

class view
{
	public static function config()
	{
		\dash\data::page_title(T_("History chart"));
		$data = \lib\app\history::chart();
		\dash\data::chartData($data);
	}
}
?>