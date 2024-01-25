<?php

namespace Taskovich\DonateExecute;

use Taskovich\DonateExecute\utils\DonatesInfo;

class DonateHandler
{

	public static function execute()
	{
		var_dump(DonatesInfo::$last_donate_id);
	}

}