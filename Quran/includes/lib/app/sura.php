<?php
namespace lib\app;


class sura
{
	public static $sort_field =
	[

		'index',
		'ayas',
		'start',
		'end',
		'name',
		'tname',
		'ename',
		'type',
		'order',
		'word',
		'theletter',
		'startjuz',
		'endjuz',
		'startpage',
		'endpage',
	];


	public static function load($_id)
	{
		$load             = \lib\db\quran::get(['sura' => $_id]);
		$result           = [];
		$result['aye']    = $load;
		$result['detail'] = \lib\db\sura::get(['index' => $_id, 'limit' => 1]);
		return $result;
	}


	public static function db_list($_string, $_args)
	{

		$default_args =
		[
			'order' => null,
			'sort'  => 'index',
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default_args, $_args);

		if($_args['order'])
		{
			if(!in_array($_args['order'], ['asc', 'desc']))
			{
				unset($_args['order']);
			}
		}

		if($_args['sort'])
		{
			if(!in_array($_args['sort'], self::$sort_field))
			{
				$_args['sort'] = 'index';
			}
		}


		$result = \lib\db\sura::search($_string, $_args);

		return $result;
	}



	public static function list()
	{
		$addr = root. '/content_api/v6/sura/sura.json';
		if(is_file($addr))
		{
			$get = \dash\file::read($addr);
			$get = json_decode($get, true);
			if(is_array($get))
			{
				return $get;
			}
		}
		return null;
	}


	public static function detail($_id, $_field = null)
	{
		$get = self::list();
		if(is_array($get))
		{
			if(isset($get[$_id]))
			{
				if(!$_field)
				{
					return $get[$_id];
				}
				elseif(isset($get[$_id][$_field]))
				{
					return $get[$_id][$_field];
				}
				else
				{
					return null;
				}
			}
		}

		return null;
	}


	public static function quick_list()
	{
		$list =
		[
			"1"   => \dash\utility\human::fitNumber("1"). ' - '. T_("Al-Fatihah"),
			"2"   => \dash\utility\human::fitNumber("2"). ' - '. T_("Al-Baqarah"),
			"3"   => \dash\utility\human::fitNumber("3"). ' - '. T_("Ali 'Imran"),
			"4"   => \dash\utility\human::fitNumber("4"). ' - '. T_("An-Nisa"),
			"5"   => \dash\utility\human::fitNumber("5"). ' - '. T_("Al-Ma'idah"),
			"6"   => \dash\utility\human::fitNumber("6"). ' - '. T_("Al-An'am"),
			"7"   => \dash\utility\human::fitNumber("7"). ' - '. T_("Al-A'raf"),
			"8"   => \dash\utility\human::fitNumber("8"). ' - '. T_("Al-Anfal"),
			"9"   => \dash\utility\human::fitNumber("9"). ' - '. T_("At-Tawbah"),
			"10"  => \dash\utility\human::fitNumber("10"). ' - '. T_("Yunus"),
			"11"  => \dash\utility\human::fitNumber("11"). ' - '. T_("Hud"),
			"12"  => \dash\utility\human::fitNumber("12"). ' - '. T_("Yusuf"),
			"13"  => \dash\utility\human::fitNumber("13"). ' - '. T_("Ar-Ra'd"),
			"14"  => \dash\utility\human::fitNumber("14"). ' - '. T_("Ibrahim"),
			"15"  => \dash\utility\human::fitNumber("15"). ' - '. T_("Al-Hijr"),
			"16"  => \dash\utility\human::fitNumber("16"). ' - '. T_("An-Nahl"),
			"17"  => \dash\utility\human::fitNumber("17"). ' - '. T_("Al-Isra"),
			"18"  => \dash\utility\human::fitNumber("18"). ' - '. T_("Al-Kahf"),
			"19"  => \dash\utility\human::fitNumber("19"). ' - '. T_("Maryam"),
			"20"  => \dash\utility\human::fitNumber("20"). ' - '. T_("Taha"),
			"21"  => \dash\utility\human::fitNumber("21"). ' - '. T_("Al-Anbya"),
			"22"  => \dash\utility\human::fitNumber("22"). ' - '. T_("Al-Haj"),
			"23"  => \dash\utility\human::fitNumber("23"). ' - '. T_("Al-Mu'minun"),
			"24"  => \dash\utility\human::fitNumber("24"). ' - '. T_("An-Nur"),
			"25"  => \dash\utility\human::fitNumber("25"). ' - '. T_("Al-Furqan"),
			"26"  => \dash\utility\human::fitNumber("26"). ' - '. T_("Ash-Shu'ara"),
			"27"  => \dash\utility\human::fitNumber("27"). ' - '. T_("An-Naml"),
			"28"  => \dash\utility\human::fitNumber("28"). ' - '. T_("Al-Qasas"),
			"29"  => \dash\utility\human::fitNumber("29"). ' - '. T_("Al-'Ankabut"),
			"30"  => \dash\utility\human::fitNumber("30"). ' - '. T_("Ar-Rum"),
			"31"  => \dash\utility\human::fitNumber("31"). ' - '. T_("Luqman"),
			"32"  => \dash\utility\human::fitNumber("32"). ' - '. T_("As-Sajdah"),
			"33"  => \dash\utility\human::fitNumber("33"). ' - '. T_("Al-Ahzab"),
			"34"  => \dash\utility\human::fitNumber("34"). ' - '. T_("Saba"),
			"35"  => \dash\utility\human::fitNumber("35"). ' - '. T_("Fatir"),
			"36"  => \dash\utility\human::fitNumber("36"). ' - '. T_("Ya-Sin"),
			"37"  => \dash\utility\human::fitNumber("37"). ' - '. T_("As-Saffat"),
			"38"  => \dash\utility\human::fitNumber("38"). ' - '. T_("Sad"),
			"39"  => \dash\utility\human::fitNumber("39"). ' - '. T_("Az-Zumar"),
			"40"  => \dash\utility\human::fitNumber("40"). ' - '. T_("Ghafir"),
			"41"  => \dash\utility\human::fitNumber("41"). ' - '. T_("Fussilat"),
			"42"  => \dash\utility\human::fitNumber("42"). ' - '. T_("Ash-Shuraa"),
			"43"  => \dash\utility\human::fitNumber("43"). ' - '. T_("Az-Zukhruf"),
			"44"  => \dash\utility\human::fitNumber("44"). ' - '. T_("Ad-Dukhan"),
			"45"  => \dash\utility\human::fitNumber("45"). ' - '. T_("Al-Jathiyah"),
			"46"  => \dash\utility\human::fitNumber("46"). ' - '. T_("Al-Ahqaf"),
			"47"  => \dash\utility\human::fitNumber("47"). ' - '. T_("Muhammad"),
			"48"  => \dash\utility\human::fitNumber("48"). ' - '. T_("Al-Fath"),
			"49"  => \dash\utility\human::fitNumber("49"). ' - '. T_("Al-Hujurat"),
			"50"  => \dash\utility\human::fitNumber("50"). ' - '. T_("Qaf"),
			"51"  => \dash\utility\human::fitNumber("51"). ' - '. T_("Adh-Dhariyat"),
			"52"  => \dash\utility\human::fitNumber("52"). ' - '. T_("At-Tur"),
			"53"  => \dash\utility\human::fitNumber("53"). ' - '. T_("An-Najm"),
			"54"  => \dash\utility\human::fitNumber("54"). ' - '. T_("Al-Qamar"),
			"55"  => \dash\utility\human::fitNumber("55"). ' - '. T_("Ar-Rahman"),
			"56"  => \dash\utility\human::fitNumber("56"). ' - '. T_("Al-Waqi'ah"),
			"57"  => \dash\utility\human::fitNumber("57"). ' - '. T_("Al-Hadid"),
			"58"  => \dash\utility\human::fitNumber("58"). ' - '. T_("Al-Mujadila"),
			"59"  => \dash\utility\human::fitNumber("59"). ' - '. T_("Al-Hashr"),
			"60"  => \dash\utility\human::fitNumber("60"). ' - '. T_("Al-Mumtahanah"),
			"61"  => \dash\utility\human::fitNumber("61"). ' - '. T_("As-Saf"),
			"62"  => \dash\utility\human::fitNumber("62"). ' - '. T_("Al-Jumu'ah"),
			"63"  => \dash\utility\human::fitNumber("63"). ' - '. T_("Al-Munafiqun"),
			"64"  => \dash\utility\human::fitNumber("64"). ' - '. T_("At-Taghabun"),
			"65"  => \dash\utility\human::fitNumber("65"). ' - '. T_("At-Talaq"),
			"66"  => \dash\utility\human::fitNumber("66"). ' - '. T_("At-Tahrim"),
			"67"  => \dash\utility\human::fitNumber("67"). ' - '. T_("Al-Mulk"),
			"68"  => \dash\utility\human::fitNumber("68"). ' - '. T_("Al-Qalam"),
			"69"  => \dash\utility\human::fitNumber("69"). ' - '. T_("Al-Haqqah"),
			"70"  => \dash\utility\human::fitNumber("70"). ' - '. T_("Al-Ma'arij"),
			"71"  => \dash\utility\human::fitNumber("71"). ' - '. T_("Nuh"),
			"72"  => \dash\utility\human::fitNumber("72"). ' - '. T_("Al-Jinn"),
			"73"  => \dash\utility\human::fitNumber("73"). ' - '. T_("Al-Muzzammil"),
			"74"  => \dash\utility\human::fitNumber("74"). ' - '. T_("Al-Muddaththir"),
			"75"  => \dash\utility\human::fitNumber("75"). ' - '. T_("Al-Qiyamah"),
			"76"  => \dash\utility\human::fitNumber("76"). ' - '. T_("Al-Insan"),
			"77"  => \dash\utility\human::fitNumber("77"). ' - '. T_("Al-Mursalat"),
			"78"  => \dash\utility\human::fitNumber("78"). ' - '. T_("An-Naba"),
			"79"  => \dash\utility\human::fitNumber("79"). ' - '. T_("An-Nazi'at"),
			"80"  => \dash\utility\human::fitNumber("80"). ' - '. T_("'Abasa"),
			"81"  => \dash\utility\human::fitNumber("81"). ' - '. T_("At-Takwir"),
			"82"  => \dash\utility\human::fitNumber("82"). ' - '. T_("Al-Infitar"),
			"83"  => \dash\utility\human::fitNumber("83"). ' - '. T_("Al-Mutaffifin"),
			"84"  => \dash\utility\human::fitNumber("84"). ' - '. T_("Al-Inshiqaq"),
			"85"  => \dash\utility\human::fitNumber("85"). ' - '. T_("Al-Buruj"),
			"86"  => \dash\utility\human::fitNumber("86"). ' - '. T_("At-Tariq"),
			"87"  => \dash\utility\human::fitNumber("87"). ' - '. T_("Al-A'la"),
			"88"  => \dash\utility\human::fitNumber("88"). ' - '. T_("Al-Ghashiyah"),
			"89"  => \dash\utility\human::fitNumber("89"). ' - '. T_("Al-Fajr"),
			"90"  => \dash\utility\human::fitNumber("90"). ' - '. T_("Al-Balad"),
			"91"  => \dash\utility\human::fitNumber("91"). ' - '. T_("Ash-Shams"),
			"92"  => \dash\utility\human::fitNumber("92"). ' - '. T_("Al-Layl"),
			"93"  => \dash\utility\human::fitNumber("93"). ' - '. T_("Ad-Duhaa"),
			"94"  => \dash\utility\human::fitNumber("94"). ' - '. T_("Ash-Sharh"),
			"95"  => \dash\utility\human::fitNumber("95"). ' - '. T_("At-Tin"),
			"96"  => \dash\utility\human::fitNumber("96"). ' - '. T_("Al-'Alaq"),
			"97"  => \dash\utility\human::fitNumber("97"). ' - '. T_("Al-Qadr"),
			"98"  => \dash\utility\human::fitNumber("98"). ' - '. T_("Al-Bayyinah"),
			"99"  => \dash\utility\human::fitNumber("99"). ' - '. T_("Az-Zalzalah"),
			"100" => \dash\utility\human::fitNumber("100"). ' - '. T_("Al-'Adiyat"),
			"101" => \dash\utility\human::fitNumber("101"). ' - '. T_("Al-Qari'ah"),
			"102" => \dash\utility\human::fitNumber("102"). ' - '. T_("At-Takathur"),
			"103" => \dash\utility\human::fitNumber("103"). ' - '. T_("Al-'Asr"),
			"104" => \dash\utility\human::fitNumber("104"). ' - '. T_("Al-Humazah"),
			"105" => \dash\utility\human::fitNumber("105"). ' - '. T_("Al-Fil"),
			"106" => \dash\utility\human::fitNumber("106"). ' - '. T_("Quraysh"),
			"107" => \dash\utility\human::fitNumber("107"). ' - '. T_("Al-Ma'un"),
			"108" => \dash\utility\human::fitNumber("108"). ' - '. T_("Al-Kawthar"),
			"109" => \dash\utility\human::fitNumber("109"). ' - '. T_("Al-Kafirun"),
			"110" => \dash\utility\human::fitNumber("110"). ' - '. T_("An-Nasr"),
			"111" => \dash\utility\human::fitNumber("111"). ' - '. T_("Al-Masad"),
			"112" => \dash\utility\human::fitNumber("112"). ' - '. T_("Al-Ikhlas"),
			"113" => \dash\utility\human::fitNumber("113"). ' - '. T_("Al-Falaq"),
			"114" => \dash\utility\human::fitNumber("114"). ' - '. T_("An-Nas"),
		];
		return $list;
	}
}
?>