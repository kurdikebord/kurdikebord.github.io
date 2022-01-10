<?php
namespace content_a\khatm\start;


class controller
{
	public static function routing()
	{
		$subchild = \dash\url::subchild();
		$khatm = \lib\app\khatm::site_start($subchild);
		if($khatm)
		{
			\dash\data::khatmRow($khatm);
			\dash\open::get();
		}
		else
		{
			\dash\redirect::to(\dash\url::this());
		}

		\dash\open::post();

	}
}
?>