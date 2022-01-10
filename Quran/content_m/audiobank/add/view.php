<?php
namespace content_m\audiobank\add;

class view
{
	public static function config()
	{
		$countryList = \dash\utility\location\countres::$data;
		\dash\data::countryList($countryList);
		\dash\data::qariList(\lib\app\audiobank::qari_list());
		\dash\data::typeList(\lib\app\audiobank::type_list());
		\dash\data::readtypeList(\lib\app\audiobank::readtype_list());


	}
}
?>