<?php
namespace content\audio;

class model
{

	public static function post()
	{
		if(\dash\request::post('updatedatabase') && \dash\permission::supervisor())
		{
			if(self::updatedatabase())
			{
				\dash\notif::ok(T_("Insert audiobank successfull"));
			}
			else
			{
				return false;
			}
		}
		else
		{
			\dash\notif::error(T_("Dont!"));
			return false;
		}

	}


	private static function updatedatabase()
	{
		$data = [];
		$addr = __DIR__. '/data.me.json';

		if(is_file($addr))
		{
			$data = \dash\file::read($addr);
			$data = json_decode($data, true);

			if(!is_array($data))
			{
				$data = [];
			}
		}
		\lib\db\audiobank::delete_all();


		$multi_insert = [];
		$count        = 0;

		foreach ($data['list'] as $key => $value)
		{
			if(
				!array_key_exists('qari', $value) ||
				!array_key_exists('style', $value) ||
				!array_key_exists('folder', $value) ||
				!array_key_exists('subfolder', $value) ||
				!array_key_exists('quality', $value)
			  )
			{
				continue;
			}

			$myFiles = [];

			if(isset($value['files']))
			{
				$myKey   = array_column($value['files'], 'name');
				$myKey   = array_map(function ($_a){return str_replace('.mp3', '', $_a);}, $myKey);
				$myFiles = array_combine($myKey, $value['files']);
			}

			$insert =
			[
				'qari'      => $value['qari'],
				'country'   => \lib\app\qari::get_by_slug($value['qari'], 'country'),
				'type'      => $value['style'],
				'readtype'  => $value['folder'],
				'addr'      => $value['folder']. '/'. $value['subfolder'],
				'quality'   => is_numeric($value['quality']) ? $value['quality'] : null,
				'countfile' => $value['countfile'],
				'size'      => $value['size'],
				'status'    => 'enable',
				'typedesc'  => isset($value['style_detail']['desc']) ? $value['style_detail']['desc'] : null,
				'qari2'     => isset($value['detail']['translate_qari']) ? $value['detail']['translate_qari'] : null,
				'language'  => isset($value['detail']['lang']) ? $value['detail']['lang'] : null,
				'reader'    => isset($value['detail']['reader']) ? $value['detail']['reader'] : null,
				'meta'      => json_encode($myFiles, JSON_UNESCAPED_UNICODE),
			];

			$count += intval(\lib\db\audiobank::insert($insert) !== 0);
		}
		\dash\notif::ok($count. " Record inserted");
		return true;
	}

}
?>