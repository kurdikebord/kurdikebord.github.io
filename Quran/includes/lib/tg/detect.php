<?php
namespace lib\tg;
use \dash\social\telegram\tg as bot;


class detect
{
	public static function run($_cmd)
	{
		$myCommand = $_cmd['text'];
		if(bot::isCallback())
		{
			$myCommand = substr($myCommand, 3);
		}
		elseif(bot::isInline())
		{
			$myCommand = substr($myCommand, 3);
		}
		// remove command from start
		if(substr($myCommand, 0, 1) == '/')
		{
			$myCommand = substr($myCommand, 1);
		}

		// switch based on user enter
		switch ($myCommand)
		{
			case 'Quran':
			case 'quran':
			case T_('Quran'):
			case 'list':
			case T_('List'):
			case T_('list'):
			case T_('$'):
			case 'how':
			case '؟':
			case '?':
			case T_('how'):
			case T_('howto'):
				// show list of survey
				Quran::start();
				return true;
				break;

			case T_('pdf'):
			case 'pdf':
			case 'Pdf':
			case 'PDF':
			case 'pdf1':
				Quran::pdf1();
				return true;
				break;

			default:
				// do nothing and continue
				break;
		}

		$firstChar = mb_substr($myCommand, 0, 1);
		$args      = mb_substr($myCommand, 1);
		$args      = str_replace('_', '', $args);
		$args      = str_replace('-', '', $args);
		$args      = trim($args);
		// convert fa and ar number to en
		$args      = \dash\utility\convert::to_en_number($args);
		// $args      = intval($args);
		if( (is_numeric($args) && $args !== 0) || $args === 'today' || $args === '?' || $args === '؟' || $args === 'random' || $args === '')
		{
			// we have a number
			switch ($firstChar)
			{

				case 'P':
				case 'p':
				case T_('Page'):
				case 'ص':
				case 'صفحه':
				case 'page':
					Quran::page($args);
					return true;
					break;

				case 'J':
				case 'j':
				case T_('Juz'):
				case 'ج':
				case 'جز':
				case 'جزء':
				case 'juz':
					Quran::juz($args);
					return true;
					break;

				case 'S':
				case 's':
				case T_('Surah'):
				case 'س':
				case 'سوره':
				case 'surah':
				case 'sura':
				case 'sore':
					Quran::surah($args);
					return true;
					break;

				case 'A':
				case 'a':
				case T_('Aya'):
				case 'آ':
				case 'آیه':
				case 'Ayah':
				case 'ayah':
				case 'aya':
				case 'aye':
					Quran::aya($args);
					return true;
					break;

				default:
					// return true;
					break;
			}
		}
	}


	public static function mainmenu($_onlyMenu = false)
	{
		$menu = ['remove_keyboard' => true];
		// on private chat add keyboard
		if(bot::isPrivate())
		{
			$menu =
			[
				'keyboard' =>[],
				'resize_keyboard' => true,
			];

			$menu['keyboard'][] = [T_("Quran")];
			// add about and contact link
			$menu['keyboard'][] = [T_("About"), T_("Contact")];

			// add sync
			if(\dash\user::detail('mobile'))
			{
				$menu['keyboard'][] =
				[
					T_("Website"). ' '. T_(\dash\option::config('site', 'title')),
					T_("News")
				];
			}
			else
			{
				$menu['keyboard'][] = [T_("Sync with website")];
			}
		}

		if($_onlyMenu)
		{
			return $menu;
		}

		$txt_text = T_("Main menu");

		$result =
		[
			'text'         => $txt_text,
			'reply_markup' => $menu,
		];

		bot::sendMessage($result);
		bot::ok();
	}
}
?>