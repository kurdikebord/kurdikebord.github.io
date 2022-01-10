<?php
namespace lib\app\posts;


class template
{

	public static function find(&$_data)
	{
		$id = null;
		if(isset($_data['id']))
		{
			$id = \dash\coding::decode($_data['id']);
		}

		if(!$id)
		{
			return null;
		}

		$mag = \lib\app\mag::find_by_post($id);

		if($mag)
		{
			$_data['maglist'] = $mag;
		}

	}

}
?>