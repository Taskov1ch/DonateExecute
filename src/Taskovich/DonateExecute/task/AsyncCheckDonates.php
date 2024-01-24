<?php

namespace Taskovich\DonateExecute\task;

use pocketmine\scheduler\AsyncTask;

use Taskovich\DonateExecute\donationalerts\DonationAlertsSession;
use Taskovich\DonateExecute\DonateHandler;

class AsyncCheckDonates extends AsyncTask {

	private string $donate;

	public function __construct() {}

	public function onRun(): void {
		$this->donate = DonationAlertsSession::getDonationList(false);
	}

	public function onCompletion(): void {
		// code...
	}

}