<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\scheduler\AsyncTask;

use Taskovich\DonateExecute\network\DaRequests;
use Taskovich\DonateExecute\DonateHandler;

class AsyncCheckDonates extends AsyncTask
{

	public function __construct(
		private string $token
	) {}

	public function onRun(): void
	{
		$this->data = DaRequests::getDonationList($this->token, false);
	}

	public function onCompletion(): void
	{
		DonateHandler::execute();
	}

}