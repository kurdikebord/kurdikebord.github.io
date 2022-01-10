<?php
namespace lib\app;


class khatmusage
{

	public static function start($_id)
	{
		$check = \lib\app\khatm::check_valid($_id);
		if(!$check)
		{
			\dash\notif::error(T_("Can not start this khatm"));
			return false;
		}

		if(!isset($check['range']) || !isset($check['repeat']))
		{
			\dash\notif::error(T_("Range or repeat not set"));
			return false;
		}

		$id           = \dash\coding::decode($_id);
		$update_khatm = [];
		$repeat       = intval($check['repeat']);
		$new_useage   = false;
		$sura         = null;
		$page         = null;
		$juz          = null;
		$is_complete = false;

		if($check['range'] === 'quran')
		{
			if($check['type'] === 'page')
			{
				$page   = \lib\db\khatmusage::find_last_page($id);
				if(!$page)
				{
					$is_complete = true;
				}
				$repeat = $repeat * 604;
			}
			elseif($check['type'] === 'juz')
			{
				$juz    = \lib\db\khatmusage::find_last_juz($id);
				if(!$juz)
				{
					$is_complete = true;
				}
				$repeat = $repeat * 30;
			}
		}
		elseif($check['range'] === 'sura')
		{
			$sura = $check['sura'];
			// $repeat = $repeat; nothing
		}

		if($is_complete)
		{
			$update_khatm['status'] = 'done';
		}
		else
		{

			$count_done   = \lib\db\khatmusage::get_count_done_quran($id);

			if(intval($count_done) >= $repeat)
			{
				$update_khatm['status'] = 'done';
			}
			else
			{
				$count_reserved = \lib\db\khatmusage::get_count_reserved_quran($id);
				if(intval($count_reserved) >= $repeat)
				{
					$update_khatm['status'] = 'reserved';
				}
				else
				{
					$new_useage = true;
					if($check['status'] === 'awaiting')
					{
						$update_khatm['status'] = 'running';
					}
				}
			}
		}

		if($new_useage)
		{
			$check_duplicate_user_khatm = \lib\db\khatmusage::user_have_running_khatm(\dash\user::id());

			if(isset($check_duplicate_user_khatm['khatm_id']))
			{
				$msg = T_("You have one not complete khatm and can not start new khatm");
				$msg .= ' <a href="'. \dash\url::this(). '/usage/'.\dash\coding::encode($check_duplicate_user_khatm['khatm_id']). '">'. T_("Click here to complete it"). "</a>";
				\dash\notif::error($msg);
				return false;
			}

			$insert =
			[
				'user_id'     => \dash\user::id(),
				'khatm_id'    => $id,
				'sura'        => $sura,
				'page'        => $page,
				'juz'         => $juz,
				'status'      => 'request',
				'datecreated' => date("Y-m-d H:i:s"),
			];
			\lib\db\khatmusage::insert($insert);
		}

		if(!empty($update_khatm))
		{
			\lib\db\khatm::update($update_khatm, $id);
		}

		return true;
	}


	public static function remain($_id)
	{
		$list = \lib\db\khatmusage::in_use($_id);
		if(!$list)
		{
			return true;
		}
		j($list);
		return true;
	}


	public static function check_remain($_id)
	{
		$check = \lib\app\khatm::check_valid($_id);

		if(!$check)
		{
			return false;
		}

		$id           = \dash\coding::decode($_id);
		$update_khatm = [];
		$repeat       = intval($check['repeat']);
		$remain   = false;
		$sura         = null;
		$page         = null;
		$juz          = null;
		$is_complete = false;

		if($check['range'] === 'quran')
		{
			if($check['type'] === 'page')
			{
				$page   = \lib\db\khatmusage::find_last_page($id);
				if(!$page)
				{
					$is_complete = true;
				}
				$repeat = $repeat * 604;
			}
			elseif($check['type'] === 'juz')
			{
				$juz    = \lib\db\khatmusage::find_last_juz($id);
				if(!$juz)
				{
					$is_complete = true;
				}
				$repeat = $repeat * 30;
			}
		}
		elseif($check['range'] === 'sura')
		{
			$sura = $check['sura'];
			// $repeat = $repeat; nothing
		}

		if($is_complete)
		{
			$update_khatm['status'] = 'done';
		}
		else
		{

			$count_done   = \lib\db\khatmusage::get_count_done_quran($id);

			if(intval($count_done) >= $repeat)
			{
				$update_khatm['status'] = 'done';
			}
			else
			{
				$count_reserved = \lib\db\khatmusage::get_count_reserved_quran($id);
				if(intval($count_reserved) >= $repeat)
				{
					$update_khatm['status'] = 'reserved';
				}
				else
				{
					$remain = true;
					if($check['status'] === 'awaiting')
					{
						$update_khatm['status'] = 'running';
					}
				}
			}
		}



		if(!empty($update_khatm))
		{
			\lib\db\khatm::update($update_khatm, $id);
		}

		return $remain;
	}


	public static function edit_status($_status, $_id)
	{
		$check = \lib\app\khatm::check_valid($_id);
		if(!$check)
		{
			\dash\notif::error(T_("Can not start this khatm"));
			return false;
		}


		$check = \lib\db\khatmusage::get_last_record(\dash\user::id(), \dash\coding::decode($_id));

		if(!isset($check['id']))
		{
			\dash\notif::error(T_("This is not your data"));
			return false;
		}

		if(!in_array($_status, ['done', 'cancel']))
		{
			\dash\noti::error(T_("Invalid status"));
			return false;
		}

		\lib\db\khatmusage::update(['status' => $_status], $check['id']);

		self::check_remain($_id);

		if($_status === 'done')
		{
			$msg = T_("Thank you!");
		}
		else
		{
			$msg = T_("Your request was canceled");
		}

		\dash\notif::ok($msg);
		return true;

	}

	public static function usage($_id)
	{
		$check = \lib\app\khatm::check_valid($_id);
		if(!$check)
		{
			\dash\notif::error(T_("Can not start this khatm"));
			return false;
		}

		$check = \lib\db\khatmusage::get_last_record(\dash\user::id(), \dash\coding::decode($_id));


		if($check)
		{
			$desc = '';

			if(isset($check['sura']))
			{
				$sura_name = T_(\lib\app\sura::detail($check['sura'], 'name'));
				$desc .= T_("Your contribution is one time reading :val from the Holy Quran", ['val' => $sura_name]);
				$check['sura_name'] = $sura_name;
			}
			elseif($check['page'])
			{
				$desc .= T_("Your contribution is reading page :val from the Holy Quran", ['val' => \dash\utility\human::fitNumber($check['page'])]);
			}
			elseif($check['juz'])
			{
				$desc .= T_("Your contribution is reading juz :val from the Holy Quran", ['val' => \dash\utility\human::fitNumber($check['juz'])]);
			}

			$check['desc'] = $desc;
			return $check;
		}

		return false;
	}


	public static function my_list($_string = null, $_args = [])
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$_args['user_id'] = \dash\user::id();

		$result            = \lib\db\khatmusage::search(null, $_args);

		$temp              = [];

		foreach ($result as $key => $value)
		{
			$check = \lib\app\khatm::ready($value);
			if($check)
			{
				$temp[] = $check;
			}
		}

		return $temp;
	}





}
?>
