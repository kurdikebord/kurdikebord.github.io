<?php
namespace lib\app;


class read_mode
{

	public static function site_list()
	{
		$list = self::list();
		return $list;
	}


	public static function load($_mode)
	{
		$list = self::list();
		if(!isset($list[$_mode]) || !$_mode)
		{
			foreach ($list as $key => $value)
			{
				if($value['default'])
				{
					return $value;
				}
			}
			return null;
		}

		return $list[$_mode];
	}


	public static function check_true($_mode)
	{
		$list = self::list();
		if($_mode && isset($list[$_mode]))
		{
			return true;
		}

		return false;
	}


	public static function primary()
	{
		$list = self::list();
		foreach ($list as $key => $value)
		{
			if(isset($value['default']) && $value['default'])
			{
				return $key;
			}
		}
		return null;
	}



	public static function list()
	{
		$get = \dash\request::get();
		unset($get['autoplay']);

		$master = \dash\url::that(). '?';

		$read_mode =
		[
			'aye' =>
			[
				'default' => true,
				'name'    => T_('Aye Block'),
				'font'    => null,
				'class'   => 'align-right',
				'url'     => \dash\url::that(),
			],

			'onepage' =>
			[
				'default' => false,
				'name'    => T_('One page'),
				'font'    => null,
				'class'   => 'book',
				'url'     => $master. http_build_query(array_merge($get, ['mode' => 'onepage'])),
			],

			'twopage' =>
			[
				'default' => false,
				'name'    => T_('Two page'),
				'font'    => null,
				'class'   => 'book',
				'url'     => $master. http_build_query(array_merge($get, ['mode' => 'twopage'])),
			],

			'translatepage' =>
			[
				'default' => false,
				'name'    => T_('Translate page'),
				'font'    => null,
				'class'   => 'language',
				'url'     => $master. http_build_query(array_merge($get, ['mode' => 'translatepage'])),
			],
			'pagedesign' =>
			[
				'hidden'  => true,
				'default' => false,
				'name'    => T_('Page design'),
				'font'    => null,
				'class'   => 'align-justify',
				'url'     => $master. http_build_query(array_merge($get, ['mode' => 'pagedesign'])),
			],
		];
		return $read_mode;
	}
}
?>