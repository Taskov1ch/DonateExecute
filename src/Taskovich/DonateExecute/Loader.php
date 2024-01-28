<?php

namespace Taskovich\DonateExecute;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Taskovich\DonateExecute\utils\Language;
use Taskovich\DonateExecute\utils\Environment;
use Taskovich\DonateExecute\utils\DonatesInfo;
use Taskovich\DonateExecute\utils\Configs;
use Taskovich\DonateExecute\donationalerts\DaRequests;
use Taskovich\DonateExecute\task\CheckDonates;

class Loader extends PluginBase
{

	/**
	 * @var mixed[]
	 */
	private $env;

	/**
	 * @return void
	 */
	public function onEnable(): void
	{
		$this->saveResource("config.yml");
		$config = new Config($this->getDataFolder() . "config.yml");

		$lang_config = $config->get("lang") . ".yml";
		$this->saveResource($lang_config);

		new Language((new Config($this->getDataFolder() . $lang_config, Config::YAML))->getAll());

		$env = new Environment([$this->getDataFolder() . ".env"]);

		if(!$env->isOk()) {
			$this->getLogger()->error(
				Language::translate("loader.env_not_found") ??
				"The .env configuration file was not found"
			);
			return;
		}

		$token = $env->get("token");
		$test = DaRequests::getDonationList($token);

		if(!$test) {
			$this->getLogger()->error(
				Language::translate("loader.token_failed") ??
				"The token failed verification"
			);
			return;
		}

		DonatesInfo::updateLastDonate($test["data"][0]);

		$this->saveResource("pricelist.yml");
		Configs::init(
			$config,
			new Config($this->getDataFolder() . "pricelist.yml")
		);

		$this->getScheduler()->scheduleRepeatingTask(new CheckDonates($token), 20 * 10);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

	}

}