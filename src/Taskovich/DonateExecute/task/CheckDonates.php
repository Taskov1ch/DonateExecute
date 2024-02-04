<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\Server;
use pocketmine\scheduler\Task;

use Taskovich\DonateExecute\DonateHandler;

class CheckDonates extends Task
{

	public function __construct(
		private string $token
	) {}

	public function onRun(): void
	{
		if(DonateHandler::$cancel)
			return;
		
		Server::getInstance()->getAsyncPool()->submitTask(new AsyncCheckDonates($this->token));
	}

}