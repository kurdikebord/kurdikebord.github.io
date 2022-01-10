<?php
namespace lib\app\quran;


class find
{

	public static function find($_url, $_meta = [])
	{
		$default_meta =
		[
			'translate' => null,
			'qari'      => null,
			'mode'      => null,
		];

		if(!is_array($_meta))
		{
			$_meta = [];
		}


		$_meta = array_merge($default_meta, $_meta);
		if($_meta['mode'] && !\lib\app\read_mode::check_true($_meta['mode']))
		{
			$_meta['mode'] = \lib\app\read_mode::primary();
		}

		if(strpos($_url, '/') === false)
		{

			$first_character = mb_strtolower(substr($_url, 0, 1));
			$number          = substr($_url, 1);

			if($first_character === 's' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 114 )
				{
					return \lib\app\quran::load('sura', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'j' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 30 )
				{
					return \lib\app\quran::load('juz', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'h' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 60 )
				{
					return \lib\app\quran::load('hizb', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'p' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 604 )
				{
					return \lib\app\quran::load('page', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'a' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 6236 )
				{
					if(!$_meta['mode'])
					{
						$_meta['mode'] = 'aye';
					}
					return \lib\app\quran::load('aya', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'r' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 240 )
				{
					return \lib\app\quran::load('rub', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
			elseif($first_character === 'n' && ctype_digit($number))
			{
				if(intval($number) >= 1 && intval($number) <= 120 )
				{
					return \lib\app\quran::load('nim', $number, null, $_meta);
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			$split           = explode('/', $_url);
			$first_character = null;
			$number          = 0;

			if(isset($split[0]) && isset($split[1]))
			{
				$first_character = mb_strtolower(substr($split[0], 0, 1));
				$number          = substr($split[0], 1);
				$number2         = $split[1];

				if($first_character === 's' && ctype_digit($number) && ctype_digit($number2))
				{
					if(intval($number) >= 1 && intval($number) <= 114)
					{
						if(intval($number2) >= 1 && intval($number2) <= intval(\lib\app\sura::detail($number, 'ayas')))
						{
							if(!$_meta['mode'])
							{
								$_meta['mode'] = 'aye';
							}
							return \lib\app\quran::load('sura',$number, $number2, $_meta);
						}
					}
				}
				elseif($first_character === 's')
				{

					$from_to = explode('-', $split[1]);
					if(count($from_to) !== 2)
					{
						return false;
					}

					foreach ($from_to as $key => $value)
					{
						if(!is_numeric($value))
						{
							return false;
						}
					}
					$number          = substr($split[0], 1);

					$from_sura = intval($number);
					$from_aya  = intval($from_to[0]);
					$to_aya    = intval($from_to[1]);

					if($from_sura < 1 || $from_sura > 114 || $from_aya < 1 || $from_aya > $to_aya  || $to_aya > intval(\lib\app\sura::detail($from_sura, 'ayas')))
					{
						return false;
					}

					$_meta['from_to'] = ['sura' => $from_sura, 'from_aya' => $from_aya, 'to_aya' => $to_aya];

					return \lib\app\quran::load('from_to', null, null, $_meta);
				}

			}
		}

		return false;
	}
}
?>