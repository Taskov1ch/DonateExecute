<?php

namespace Taskovich\DonateExecute;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Taskovich\DonateExecute\donationalerts\DonationAlertsSession;

class DonateExecute extends PluginBase
{

	/**
	 * @var DonationAlertsSession
	 */
	private ?DonationAlertsSession $das;

	/**
	 * @var array
	 */
	private array $env = [];

	/**
	 * @return void
	 */
	public function onEnable(): void
	{
		if((!$this->loadConfigs()) or (!$this->initDas()))
			return;

		
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
			if(!empty($line) && strpos($line, "=") !== false && $line[0] !== "#") {
				list($key, $value) = explode("=", $line, 2);
				$key = trim($key);
				$value = trim($value);
				$this->env[$key] = $value;
			}
		}

		@mkdir($this->getDataFolder());
		$test = new Config($this->getDataFolder() . "testing.yml", Config::YAML);

		return true;
	}

	/**
	 * @return void
	 */
	private function initDas(): bool
	{
		$this->das = new DonationAlertsSession($this->env["token"]);
		$test = $this->das->getDonationList();

		if(!$test) {
			$this->getLogger()->error("Token verification failed");
			return false;
		}

		return true;
	}

}