<?php

namespace Taskovich\DonateExecute;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Taskovich\DonateExecute\donationalerts\DonationAlertsSession;
use Taskovich\DonateExecute\utils\Languages;
use Taskovich\DonateExecute\task\CheckDonates;

class DonateExecute extends PluginBase
{

	/**
	 * @var DonationAlertsSession
	 */
	private ?DonationAlertsSession $das;

	/**
	 * @var Config
	 */
	private ?Config $config;

	/**
	 * @var array
	 */
	private array $env = [];

	/**
	 * @var int
	 */
	private int $last_donate_id = 0;

	/**
	 * @return void
	 */
	public function onEnable(): void
	{
		if((!$this->loadConfigs()) or (!$this->initDas()))
			return;

		$this->loadLang();
		$this->initHandler();
	}

	/**
	 * @return void
	 */
	private function loadConfigs(): bool
	{
		$env_files = [
			__DIR__ . "/.env",
			__DIR__ . "/../../../.env",
			$this->getDataFolder() . ".env"
		];

		foreach($env_files as $option) {
			if(file_exists($option)) {
				$env_file = $option;
				break;
			}
		}

		if(!isset($env_file) or !file_exists($env_file)) {
			$this->getLogger()->error("Config file .env not found");
			return false;
		}

		$env_content = file_get_contents($env_file);
		$env_lines = explode("\n", $env_content);

		foreach($env_lines as $line) {
			if(!empty($line) and strpos($line, "=") !== false and $line[0] !== "#") {
				list($key, $value) = explode("=", $line, 2);
				$key = trim($key);
				$value = trim($value);
				$this->env[$key] = $value;
			}
		}

		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml");

		return true;
	}

	/**
	 * @return void
	 */
	private function initDas(): bool
	{
		$this->das = new DonationAlertsSession($this->env["token"]);
		$test = DonationAlertsSession::getDonationList();

		if(!$test) {
			$this->getLogger()->error("Token verification failed");
			return false;
		}

		$this->last_donate_id = $test["data"][0]["id"];
		return true;
	}

	/**
	 * @return void
	 */
	private function loadLang(): void
	{

		$lang_config = $this->config->get("lang") . ".yml";
		$this->saveResource($lang_config);
		new Languages(
			(new Config($this->getDataFolder() . $lang_config, Config::YAML))->getAll()
		);
	}

	/**
	 * @return void
	 */
	private function initHandler(): void
	{
		new DonateHandler($this->last_donate_id);
		$this->getScheduler()->scheduleRepeatingTask(new CheckDonates(), 20 * 10);
	}

}