<?php
namespace lib\app\quran;


class search
{
	public static function search($_string, $_args = [])
	{
		if(!$_string || !is_string($_string))
		{
			return null;
		}

		$result = \lib\db\quran::search($_string);
		if(is_array($result))
		{
			foreach ($result as $key => $value)
			{
				if(isset($value['sura']))
				{
					$result[$key]['sura_title'] = T_(\lib\app\sura::detail($value['sura'], 'tname'));
				}
			}
		}

		return $result;
	}
}
?>
