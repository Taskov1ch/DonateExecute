<?php

namespace Taskov1ch\DonateExecute\task;

use pocketmine\scheduler\AsyncTask;
use Taskov1ch\DonateExecute\DonateExecute;

class AsyncGetDonates extends AsyncTask
{

	public function __construct(private string $serializedRequests) {}

	public function onRun(): void
	{
		$requests = unserialize($this->serializedRequests);
		$this->setResult($requests->getDonationList());
	}

	public function onCompletion(): void
	{
		$data = $this->getResult();
		DonateExecute::getInstance()->getDonatesHandler()->addDonates($data["data"]);
		DonateExecute::getInstance()->getDonatesHandler()->schedule();
	}
}