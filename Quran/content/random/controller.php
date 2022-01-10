<?php
namespace content\random;


class controller
{
	public static function routing()
	{

		$child = \dash\url::child();

		if(in_array($child, ['aya', 'page']))
		{
			\dash\open::get();
		}
	}
}
?>