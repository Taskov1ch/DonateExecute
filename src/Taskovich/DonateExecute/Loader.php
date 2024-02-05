<?php

namespace Taskovich\DonateExecute;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\scheduler\TaskHandler;

use Taskovich\DonateExecute\utils\Language;
use Taskovich\DonateExecute\utils\Environment;
use Taskovich\DonateExecute\utils\DonatesInfo;
use Taskovich\DonateExecute\utils\Configs;
use Taskovich\DonateExecute\donationalerts\DaRequests;
use Taskovich\DonateExecute\task\CheckDonates;
use Taskovich\DonateExecute\command\AllowDonates;
use Taskovich\DonateExecute\DonateHandler;

class Loader extends PluginBase
{

	/**
	 * @return void
	 */
	public function onEnable(): void
	{
		// $configs = array_diff(scandir($this->getResourceFolder()), [".", ".."]);

		$configs = [
			"config.yml",
			"en_US.yml",
			"pricelist.yml",
			"ru_RU"
		];

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

		$map = $this->getServer()->getCommandMap();
		$map->register("DonateExecute", new AllowDonates($this));

		DonateHandler::$cancel = false;
		$this->getScheduler()->scheduleRepeatingTask(new CheckDonates($token), 20 * 5);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

}