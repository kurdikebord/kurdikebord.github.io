<?php
namespace lib\app\quran;


class translate
{


	public static $load_translate = false;
	public static $translate      = [];





	public static function load($_data, $_meta)
	{
		if(!is_array($_data))
		{
			return null;
		}

		$get = \dash\request::get();
		if(array_key_exists('t', $get))
		{
			if(!$get['t'])
			{
				return false;
			}
		}

		if(!isset($_meta['translate']) || (isset($_meta['translate']) && !$_meta['translate']))
		{
			$get = [\lib\app\translate::get_default_lang_key()];
		}
		else
		{
			$get = explode('-', $_meta['translate']);
		}

		$result = [];
		$i         = 0;

		foreach ($get as $key => $value)
		{
			$translate = \lib\app\translate::table_name($value);

			if(!isset($translate['table_name']))
			{
				continue;
			}

			$i++;
			if($i > 20)
			{
				\dash\notif::warn(T_("Only 20 translate can be show"));
				break;
			}
			$sura = array_column($_data, 'sura');
			$sura = array_filter($sura);
			$sura = array_unique($sura);

			$aya = array_column($_data, 'aya');
			$aya = array_filter($aya);
			$aya = array_unique($aya);

			if($sura && $aya)
			{
				$load = \lib\db\translate::load($translate['table_name'], ['sura' => ["IN", "(". implode(',', $sura).")"], 'aya' => ["IN", "(". implode(',', $aya).")"]]);
				$data = [];

				unset($translate['table_name']);
				unset($translate['id']);

				if(!\dash\url::content())
				{
					$get       = \dash\request::get();
					unset($get['autoplay']);
					$getTrans  = isset($get['t']) ? $get['t'] : '';
					$getTrans  = explode('-', $getTrans);
					$getTrans  = array_filter($getTrans);
					$getTrans  = array_unique($getTrans);

					$thisTransKey = $translate['language']. $translate['index'];

					if(in_array($thisTransKey, $getTrans))
					{
						unset($getTrans[array_search($thisTransKey, $getTrans)]);
					}

					if(!isset($get['mode']) || (isset($get['mode']) && $get['mode'] !== 'aye'))
					{
						$get['mode'] = 'aye';
					}

					$get['t']        = implode('-', $getTrans);
					$url             = \dash\url::that(). '?'. http_build_query($get);
					$translate['remove_url'] = $url;

				}

				foreach ($load as $key => $value)
				{
					if(!isset($data[$value['sura'].'_'. $value['aya']]))
					{
						$data[$value['sura'].'_'. $value['aya']]['text']   = $value['text'];
						$data[$value['sura'].'_'. $value['aya']]['detail'] = $translate;
					}
				}

				$translate['data'] = $data;

				$result[] = $translate;
			}
		}

		self::$load_translate = true;
		self::$translate = $result;
		return $result;
	}


	public static function get_translation($_sura, $_aya, $_meta = null)
	{
		if(!self::$load_translate)
		{
			return false;
		}

		$result = [];
		foreach (self::$translate as $key => $value)
		{
			if(isset($value['data'][$_sura. '_'. $_aya]))
			{
				$result[] = $value['data'][$_sura. '_'. $_aya];
			}
		}

		return $result;
	}

}
?>