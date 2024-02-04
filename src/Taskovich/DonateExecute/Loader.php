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
		$configs = array_diff(scandir($this->getResourceFolder()), [".", ".."]);

		foreach($configs as $config) {
			$this->saveResource($config);
		}

		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$lang = $config->get("lang") . ".yml";
		$this->saveResource($lang);

		new Language(
			(new Config($this->getDataFolder() . $lang, Config::YAML))->getAll()
		);

		$env = new Environment(
			[$this->getDataFolder() . ".env"]
		);

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
		new Configs(
			$config,
			new Config($this->getDataFolder() . "pricelist.yml")
		);

		$this->getScheduler()->scheduleRepeatingTask(new CheckDonates($token), 20 * 5);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

	}

}