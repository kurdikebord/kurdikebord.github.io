<?php
/**
 *
 */
class audiobank
{
	private static $json_addr         = null;
	private static $audio_folder_addr = null;
	private static $folder            = [];


	// sample file by name audioconfig.me.json
	// to config this library
	// {
	//   "fileName": "audioconfig.me.json",
	//   "json_addr": null,
	//   "audio_folder_addr": null,
	//   "folder": []
	// }

	private static function config()
	{

		if(is_file(__DIR__. '/audioconfig.me.json'))
		{
			$get = file_get_contents(__DIR__. '/audioconfig.me.json');
			$get = json_decode($get, true);

			if(isset($get['json_addr']))
			{
				self::$json_addr = $get['json_addr'];
			}

			if(isset($get['audio_folder_addr']))
			{
				self::$audio_folder_addr = $get['audio_folder_addr'];
			}

			if(isset($get['folder']))
			{
				self::$folder = $get['folder'];
			}
		}
		else
		{
			self::end('file audioconfig.me.json not exist');
			exit();
		}

	}

	private static function load()
	{
		if(is_file(self::$json_addr))
		{
			$load = @file_get_contents(self::$json_addr);
			$load = json_decode($load, true);
			return $load;
		}
		return null;
	}

	private static function check_last_update()
	{
		$load = self::load();
		if(isset($load['lastupdate']))
		{
			if(time() - strtotime($load['lastupdate']) < 10)
			{
				return false;
			}
		}
		return true;
	}


	private static function save_json()
	{
		$json               = [];
		$json['lastupdate'] = date("Y-m-d H:i:s");
		$json['list']       = self::make_json();
		$json               = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		$save_addr          = self::$json_addr;
		@file_put_contents($save_addr, $json);
		return true;
	}


	private static function make_json()
	{
		// the addr
		$addr        = self::$audio_folder_addr;
		$result      = [];

		foreach (self::$folder as $folder)
		{
			$folder_addr = $addr .'/'. $folder;
			$list        = glob($folder_addr. '/*', GLOB_ONLYDIR);

			foreach ($list as $key => $value)
			{
				$folder_name = str_replace($folder_addr. '/', '', $value);
				$split       = explode('-', $folder_name);
				$meta        = [];

				if(count($split) !== 3)
				{
					continue;
				}

				foreach ($split as $k => $v)
				{
					if(strpos($v, '.') !== false)
					{
						$myMeta   = substr($v, strpos($v, '.') + 1);
						$meta[$k] = explode('.', $myMeta);
						$split[$k] = substr($v, 0, strpos($v, '.'));
					}

				}


				$temp              = [];
				$temp['folder']    = $folder;
				$temp['subfolder'] = $folder_name;
				$temp['qari']      = isset($split[0]) ? $split[0] : null;

				if(isset($meta[0]))
				{
					$temp['detail'] =
					[
						'lang'           => isset($meta[0][0]) ? $meta[0][0] : null,
						'reader'         => isset($meta[0][1]) ? $meta[0][1] : null,
						'translate_qari' => isset($meta[0][2]) ? $meta[0][2] : null,
					];
				}

				$temp['style']   = isset($split[1]) ? $split[1] : null;
				if(isset($meta[1]))
				{
					$temp['style_detail'] =
					[
						'desc'       => isset($meta[1][0]) ? $meta[1][0] : null,
					];
				}

				$temp['quality']  = isset($split[2]) ? $split[2] : null;

				list($temp['size'], $temp['countfile'])   = self::child_size($value);
				$temp['readtype'] = $folder;

				if($folder !== 'ayat')
				{
					$temp['files']    = self::files($value);
				}

				$result[]        = $temp;

			}

		}

		return $result;
	}


	private static function child_size($_addr)
	{
		$size = 0;
		$count = 0;
		if(is_dir($_addr))
		{
			$list = glob($_addr. '/*');

			foreach ($list as $key => $value)
			{
				if(is_dir($value))
				{
					$temp  = self::child_size($value);
					$size  += $temp[0];
					$count += $temp[1];
				}
				elseif(is_file($value))
				{
					$size += filesize($value);
					$count++;
				}
			}
		}
		elseif(is_file($_addr))
		{
			$size += filesize($_addr);
			$count++;
		}

		return [$size, $count];
	}

	private static function files($_addr)
	{
		$file_list = array_filter(glob($_addr. '/*'), 'is_file');
		$new_list = [];
		foreach ($file_list as $key => $value)
		{
			$new_list[] =
			[
				'name' => basename($value),
				'size' => filesize($value),
			];
		}
		return $new_list;

	}

	public static function run()
	{
		self::config();
		if(self::check_last_update())
		{
			self::save_json();

			self::end('The operation successfully completed');
			// self::end(self::load());
		}
		else
		{
			self::end('Too many tries');
			// self::end(self::load());
		}
	}


	private static function end($_a)
	{
		echo '<pre>';
		print_r($_a);
		echo '</pre>';
		echo '<hr>';
	}
}

\audiobank::run();

?>