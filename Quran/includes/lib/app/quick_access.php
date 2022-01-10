<?php
namespace lib\app;


class quick_access
{

	public static function list()
	{
		$list = [];

		$list[] =
		[
			'title' => T_('Ayatolkorsi'),
			'desc'  => null,
			'slug'  => 'ayatolkorsi',
			'url'   => \dash\url::kingdom(). '/s2/255-257',
		];

		$list[] =
		[
			'title' => T_('Al-Fatihah'),
			'desc'  => null,
			'slug'  => 'fatihah',
			'url'   => \dash\url::kingdom(). '/s1',
		];

		$list[] =
		[
			'title' => T_("Ar-Rahman"),
			'desc'  => null,
			'slug'  => 'rahman',
			'url'   => \dash\url::kingdom(). '/s55',
		];

		$list[] =
		[
			'title' => T_('Al-Mulk'),
			'desc'  => null,
			'slug'  => 'mulk',
			'url'   => \dash\url::kingdom(). '/s67',
		];

		$list[] =
		[
			'title' => T_('Ya-Sin'),
			'desc'  => null,
			'slug'  => 'yasin',
			'url'   => \dash\url::kingdom(). '/s36',
		];

		$list[] =
		[
			'title' => T_("Al-Waqi'ah"),
			'desc'  => null,
			'slug'  => 'waqia',
			'url'   => \dash\url::kingdom(). '/s56',
		];
		return $list;
	}


}
?>