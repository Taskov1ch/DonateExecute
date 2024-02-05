<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\scheduler\AsyncTask;

use Taskovich\DonateExecute\donationalerts\DaRequests;
use Taskovich\DonateExecute\DonateHandler;
use Taskovich\DonateExecute\utils\DonatesInfo;

class AsyncCheckDonates extends AsyncTask
{
	
	/**
	 * @var string
	 */
	private string $data;

	public function __construct(
		private string $token
	) {}

	public function onRun(): void
	{
		$this->data = DaRequests::getDonationList($this->token, false);
	}

	public function onCompletion(): void
	{
		if(!DonatesInfo::checkDonate($this->data))
			return;
		
		DonateHandler::execute($this->data);
	}

}