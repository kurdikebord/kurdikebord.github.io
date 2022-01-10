<?php
namespace lib\app;


class font_style
{
	public static function zoom_in_url()
	{
		$get = \dash\request::get();
		if(isset($get['zoom']) && is_numeric($get['zoom']))
		{
			$zoom = intval($get['zoom']) + 1;
			if($zoom < 10)
			{
				if($zoom < 1)
				{
					$get['zoom'] = 6;
				}
				else
				{
					$get['zoom'] = $zoom;
				}
			}
			else
			{
				$get['zoom'] = 10;
			}
		}
		else
		{
			$get['zoom'] = 6;
		}

		unset($get['autoplay']);

		return \dash\url::that() . '?'. http_build_query($get);
	}

	public static function zoom_out_url()
	{
		$get = \dash\request::get();
		if(isset($get['zoom']) && is_numeric($get['zoom']))
		{
			$zoom = intval($get['zoom']) - 1;
			if($zoom <= 1)
			{
				$get['zoom'] = 1;
			}
			else
			{
				$get['zoom'] = $zoom;
			}
		}
		else
		{
			$get['zoom'] = 4;
		}

		unset($get['autoplay']);

		return \dash\url::that() . '?'. http_build_query($get);
	}



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

	public static function list()
	{
		$get = \dash\request::get();
		unset($get['autoplay']);
		$master = \dash\url::that(). '?';

		$font_style =
		[
			'uthmani' =>
			[
				'default' => true,
				'name'    => T_('Uthmani'),
				'font'    => null,
				'class'   => 'checkbox',
				'url'     => $master. http_build_query(array_merge($get, ['font' => 'uthmani'])),
			],
			'noorehuda' =>
			[
				'default' => false,
				'name'    => T_('Urdu'),
				'font'    => null,
				'class'   => 'checkbox',
				'url'     => $master. http_build_query(array_merge($get, ['font' => 'noorehuda'])),
			],
			'iransans' =>
			[
				'default' => false,
				'name'    => T_('IRANSans'),
				'font'    => null,
				'class'   => 'file-1',
				'url'     => $master. http_build_query(array_merge($get, ['font' => 'iransans'])),
			],
			// 'vazeh' =>
			// 	[
			// 		'default' => false,
			// 		'name'    => T_('Vazeh font'),
			// 		'font'    => null,
			// 		'class'   => 'magic',
			// 		'url'     => $master. http_build_query(array_merge($get, ['font' => 'vazeh'])),
			// 	],
		];
		return $font_style;
	}
}
?>