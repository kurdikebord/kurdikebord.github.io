<?php
namespace content\audio;

class view
{

	public static function config()
	{
		\dash\data::dlLink('https://dl.salamquran.com');

		\lib\badge::set('OpenAudioBank');

		if(\dash\data::loadAudioFolder())
		{
			\dash\data::myDisplayName('content/audio/load.html');
			self::audiobank_load();
		}
		else
		{
			\dash\data::myDisplayName('content/audio/list.html');
			self::audiobank_list();
		}
	}


	private static function audiobank_load()
	{
		$audio_list      = [];
		$file_list       = [];
		$sura            = \lib\app\sura::list();
		$loadAudioFolder = \dash\data::loadAudioFolder();

		if(isset($loadAudioFolder['readtype']) && $loadAudioFolder['readtype'] === 'surah' && isset($loadAudioFolder['meta']) && is_array($loadAudioFolder['meta']))
		{
			$file_list = $loadAudioFolder['meta'];
		}
		if($loadAudioFolder['readtype'] === 'surah')
		{

			foreach ($file_list as $key => $value)
			{
				$temp             = [];

				$sura_index = intval($key);
				$sura_detail = \lib\app\sura::detail($sura_index);

				if($sura_index < 10)
				{
					$sura_index = '00'. $sura_index;
				}
				elseif($sura_index < 100)
				{
					$sura_index = '0'. $sura_index;
				}

				$temp               = array_merge($temp, $value, $sura_detail);
				$temp['sura_index'] = $sura_index;
				$audio_list[]       = $temp;

			}

		}

		\dash\data::audioList($audio_list);

		$myTitle  = \dash\data::loadAudioFolder_name();
		$myTitle .= ' ';
		// $myTitle .= \dash\data::loadAudioFolder_type();
		\dash\data::page_title($myTitle);

		$myDesc = \dash\data::loadAudioFolder_name();
		$myDesc .= ' - ';
		$myDesc .= \dash\data::loadAudioFolder_type();
		$myDesc .= ' - ';
		$myDesc .= \dash\data::loadAudioFolder_quality();
		$myDesc .= 'kbps';

		\dash\data::page_desc($myDesc);
	}



	private static function audiobank_list()
	{
		\dash\data::page_title(T_("Audio Database"));
		\dash\data::page_desc(T_('Free audio bank of quran qiraat of famous qari around the world'));


		$search_string            = \dash\request::get('q');
		// if($search_string)
		// {
		// 	\dash\data::page_title(\dash\data::page_title(). ' | '. $search_string);
		// }

		$filterArgs = [];

		$args =
		[
			'sort'       => \dash\request::get('sort'),
			'order'      => \dash\request::get('order'),
			'pagenation' => false,
		];

		if(!$args['order'])
		{
			$args['order'] = 'desc';
		}


		if(!$args['sort'])
		{
			$args['sort'] = 'readtype';
		}

		if(\dash\request::get('status'))
		{
			$args['status']       = \dash\request::get('status');
			$filterArgs['status'] = \dash\request::get('status');
		}

		if(\dash\request::get('type'))
		{
			$args['type']       = \dash\request::get('type');
			$filterArgs['type'] = \dash\request::get('type');
		}

		if(\dash\request::get('qari'))
		{
			$args['qari']       = \dash\request::get('qari');
			$filterArgs[T_('qari')] = \lib\app\qari::get_by_slug(\dash\request::get('qari'), 'name');
		}

		if(\dash\request::get('readtype'))
		{
			$args['readtype']       = \dash\request::get('readtype');
			$filterArgs[T_("Style")] = \dash\request::get('readtype');
		}

		if(\dash\request::get('filetype'))
		{
			$args['filetype']       = \dash\request::get('filetype');
			$filterArgs['filetype'] = \dash\request::get('filetype');
		}

		if(\dash\request::get('country'))
		{
			$args['country']       = \dash\request::get('country');
			$filterArgs[T_('country')] = \dash\utility\location\countres::get_localname(\dash\request::get('country'), true);
		}

		if(\dash\request::get('quality'))
		{
			$args['quality']       = \dash\request::get('quality');
			$filterArgs['quality'] = \dash\request::get('quality');
		}


		$sortLink  = \dash\app\sort::make_sortLink(\lib\app\audiobank::$sort_field, \dash\url::this());
		$dataTable = \lib\app\audiobank::list(\dash\request::get('q'), $args);

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);



		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg($search_string, $filterArgs);
		\dash\data::dataFilter($dataFilter);



	}
}
?>