<?php
namespace content_a\khatm\usage;


class controller
{
	public static function routing()
	{
		$subchild = \dash\url::subchild();
		$khatmusage = \lib\app\khatmusage::usage($subchild);
		if($khatmusage)
		{
			\dash\open::get();
			\dash\data::khatmUsageRow($khatmusage);
		}
		else
		{
			\dash\redirect::to(\dash\url::this());
		}

		\dash\open::post();

	}
}
?>