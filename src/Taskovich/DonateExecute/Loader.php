<?php

namespace Taskovich\DonateExecute;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Taskovich\DonateExecute\utils\Environment;
use Taskovich\DonateExecute\network\DaRequests;
use Taskovich\DonateExecute\utils\DonatesInfo;
use Taskovich\DonateExecute\task\CheckDonates;

class Loader extends PluginBase
{

	private $env;

	public function onEnable(): void
	{
		$this->saveResource("config.yml");
		$config = new Config($this->getDataFolder() . "config.yml");

		$env = new Environment([$this->getDataFolder() . ".env"]);

		if(!$env->isOk()) {
			$this->getLogger()->error("Config file .env not found");
			return;
		}

		$token = $env->get("token");
		$test = DaRequests::getDonationList($token);

		if(!$test) {
			$this->getLogger()->error("Token verification failed");
			return;
		}

		DonatesInfo::$last_donate_id = $test["data"][0]["id"];

		$this->getScheduler()->scheduleRepeatingTask(new CheckDonates($token), 20 * 10);

	}

}