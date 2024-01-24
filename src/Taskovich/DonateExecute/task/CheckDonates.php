<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\scheduler\Task;
use Taskovich\DonateExecute\DonateExecute;

class CheckDonates extends Task {

	public function __construct() {}

	public function onRun(): void {
		Server::getInstance()->getAsyncPool()->submitTask(new AsyncCheckDonates($this->main));
	}

}