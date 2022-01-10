<?php
namespace content\calculator;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Calculator hefz program'));

		$args = [];

		if(\dash\request::get('mytime'))
		{
			$args['mytime'] = \dash\request::get('mytime');
		}

		$calculator = \lib\app\hefz::calculator($args);
		\dash\data::hefzCalculator($calculator);
	}
}
?>