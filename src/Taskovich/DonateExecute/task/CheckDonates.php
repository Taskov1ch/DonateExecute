<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\Server;
use pocketmine\scheduler\Task;

class CheckDonates extends Task
{

	public function __construct(
		private string $token
	) {}

	public function onRun(): void
	{
		Server::getInstance()->getAsyncPool()->submitTask(new AsyncCheckDonates($this->token));
	}

}