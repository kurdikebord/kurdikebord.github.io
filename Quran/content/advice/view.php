<?php
namespace content\advice;


class view
{
	public static function config()
	{
		\dash\data::page_title('دریافت مشاوره قرآنی');
		\dash\data::page_desc('ما می‌توانیم به شما مشاوره دهیم!');
		// \dash\data::page_special(true);

		if(\dash\request::get('token'))
		{
			$get_msg = \dash\utility\pay\setting::final_msg(\dash\request::get('token'));
			if($get_msg)
			{
				if(isset($get_msg['condition']) && $get_msg['condition'] === 'ok' && isset($get_msg['plus']))
				{
					\dash\data::paymentVerifyMsg(T_("Thanks for your holy payment, :amount sucsessfully recived", ['amount' => \dash\utility\human::fitNumber($get_msg['plus'])]));
					\dash\data::paymentVerifyMsgTrue(true);
					\dash\data::logoLink(true);
				}
				else
				{
					\dash\data::paymentVerifyMsg(T_("Payment unsuccessfull"));
				}
			}
			else
			{
				\dash\redirect::to(\dash\url::this());
			}
		}


		$doners = \lib\app\donate::doners_list();
		\dash\data::doners($doners);


	}
}
?>