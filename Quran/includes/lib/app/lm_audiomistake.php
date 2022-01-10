<?php
namespace lib\app;


class lm_audiomistake
{

	public static $sort_field =
	[
		'id',
	];


	public static function set($_mistake, $_audio_id)
	{

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}

		if(!is_array($_mistake))
		{
			return false;
		}

		if(!$_mistake)
		{
			return false;
		}

		$ids = array_map(['\\dash\\coding', 'decode'], $_mistake);

		if(count($ids) !== count($_mistake))
		{
			\dash\notif::error(T_("Data is invalid!"));
			return false;
		}

		$audio_id = \dash\coding::decode($_audio_id);
		if(!$audio_id)
		{
			\dash\notif::error(T_("Invalid audio id"));
			return false;
		}

		$get_mistake = \lib\db\lm_mistake::get_by_ids(implode(',', $ids));

		if(is_array($get_mistake) && count($get_mistake) === count($ids))
		{
			// nothing
		}
		else
		{
			\dash\notif::error(T_("Data is invalid!"));
			return false;
		}

		$multi_insert = [];

		foreach ($ids as $key => $value)
		{
			$multi_insert[] =
			[
				'lm_mistake_id' => $value,
				'lm_audio_id'   => $audio_id,
				'teacher'       => \dash\user::id(),
			];
		}

		\lib\db\lm_audiomistake::remove_all($audio_id);
		\lib\db\lm_audiomistake::multi_insert($multi_insert);

		return true;
	}


	public static function saved($_audio_id)
	{
		$_audio_id = \dash\coding::decode($_audio_id);
		if(!$_audio_id)
		{
			return false;
		}

		$list = \lib\db\lm_audiomistake::get(['lm_audio_id' => $_audio_id]);
		if(is_array($list))
		{
			$list = array_map(['self', 'ready'], $list);
		}
		return $list;
	}



	public static function ready($_data)
	{
		$result = [];
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
				case 'lm_audio_id':
				case 'lm_mistake_id':
					if(isset($value))
					{
						$result[$key] = \dash\coding::encode($value);
					}
					else
					{
						$result[$key] = null;
					}
					break;



				default:
					$result[$key] = $value;
					break;
			}
		}

		return $result;
	}



}
?>