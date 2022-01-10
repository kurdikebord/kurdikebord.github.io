<?php
namespace lib\app\log\caller;

class lmsNewAudio
{
	public static function site($_args = [])
	{

		$code = isset($_args['data']['audio_id']) ? $_args['data']['audio_id'] : null;

		$result              = [];
		$result['title']     = T_("New audio");
		$result['icon']      = 'bullhorn';
		$result['cat']       = T_("LMS");
		$result['iconClass'] = 'fc-blue';

		$excerpt = '';

		$excerpt = T_("User add new audio");
		$excerpt .=	'<a href="'.\dash\url::kingdom(). '/m/reading/edit?id='. $code. '">';
		$excerpt .= ' ';
		$excerpt .= T_("Show");
		$excerpt .= ' ';
		$excerpt .= '</a>';

		$result['txt'] = $excerpt;

		return $result;
	}


	public static function send_to()
	{
		return ['admin'];
	}

	public static function is_notif()
	{
		return true;
	}

	public static function expire()
	{
		return date("Y-m-d H:i:s", strtotime("+100 days"));
	}

	public static function telegram()
	{
		return true;
	}

	public static function telegram_text($_args, $_chat_id)
	{

		$tg_msg = '';
		$tg_msg .= "#NewAudio";


		$tg_msg .= "\n—————\n📬 ";
		$tg_msg .= T_("User add new audio");
		$tg_msg .= "\n—————\n📬 ";

		$tg_msg .= "\n⏳ ". \dash\datetime::fit(date("Y-m-d H:i:s"), true);

		$tg                 = [];
		$tg['chat_id']      = $_chat_id;
		$tg['text']         = $tg_msg;


		// $tg = json_encode($tg, JSON_UNESCAPED_UNICODE);

		return $tg;
	}
}
?>