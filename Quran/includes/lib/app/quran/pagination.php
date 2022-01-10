<?php
namespace lib\app\quran;


trait pagination
{

	public static function pagination_current()
	{
		$pagination = self::pagination(...func_get_args());
		foreach ($pagination as $key => $value)
		{
			if(isset($value['current']) && $value['current'])
			{
				return $value['limit_array'];
			}
		}
		return [1, 2];
	}

	public static function pagination($_type, $_id, $_meta)
	{
		$this_link  = \dash\url::that();
		$pagination = [];
		if($_type === 'sura')
		{
			$ayas = intval(\lib\app\sura::detail($_id, 'ayas'));
			$ayas_split = ceil($ayas / 20);

			$pagination = [];

			$get       = \dash\request::get();
			unset($get['autoplay']);
			$currnet_get = isset($get['a']) ? intval($get['a']) : 1;
			if($currnet_get > $ayas_split || $currnet_get < 1)
			{
				$currnet_get = 1;
			}

			unset($get['a']);

			for ($myAya = 0; $myAya < $ayas_split; $myAya++)
			{
				$end   = ($myAya + 1) * 20;
				$end   = $end > $ayas ? $ayas : $end;
				$start = ($myAya * 20) + 1;

				$title = $start;
				$title .= '-';
				$title .= $end;

				$get['a'] = $myAya + 1;
				$link     = $this_link. '?'. http_build_query($get);
				$current = $myAya + 1 === $currnet_get ? true : false;
				$pagination[] =
				[
					'current'     => $current,
					'class'       => $current ? 'active' : null,
					'limit_array' => [$start, $end],
					'link'        => $link,
					'text'        => \dash\utility\human::fitNumber($title),
				];
			}
		}
		elseif($_type === 'juz')
		{
			$startpage   = intval(\lib\app\juz::detail($_id, 'startpage'));
			$endpage     = intval(\lib\app\juz::detail($_id, 'endpage'));

			$get         = \dash\request::get();
			$currnet_get = isset($get['p']) ? intval($get['p']) : $startpage;

			if($currnet_get > $endpage || $currnet_get < $startpage)
			{
				$currnet_get = $startpage;
			}


			for ($page = $startpage; $page <= $endpage ; $page++)
			{
				$title    = $page;
				$get['p'] = $page;
				$link     = $this_link. '?'. http_build_query($get);
				$current  = $page === $currnet_get ? true : false;

				$pagination[] =
				[
					'current'     => $current,
					'class'       => $current ? 'active' : null,
					'limit_array' => [$page, $page],
					'link'        => $link,
					'text'        => \dash\utility\human::fitNumber($title),
				];
			}
		}

		return $pagination;
	}
}
?>