<?php
namespace content\donate;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_('Donate to SalamQuran'));
		\dash\data::page_desc(T_('Help this project grow faster and better.'));
		\dash\data::page_special(true);

		if(\dash\request::get('token'))
		{
			$get_msg = \dash\utility\pay\setting::final_msg(\dash\request::get('token'));
			if($get_msg)
			{
				if(isset($get_msg['condition']) && $get_msg['condition'] === 'ok' && isset($get_msg['plus']))
				{
					\dash\data::paymentVerifyMsg(T_("Thanks for your payment, :amount sucsessfully recived", ['amount' => \dash\utility\human::fitNumber($get_msg['plus'])]));
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
		// j($doners);
	}
}
?>