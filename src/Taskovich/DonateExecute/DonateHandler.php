<?php

namespace Taskovich\DonateExecute;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\console\ConsoleCommandSender;

use Taskovich\DonateExecute\utils\DonatesInfo;
use Taskovich\DonateExecute\utils\Configs;
use Taskovich\DonateExecute\utils\Language;
use Taskovich\DonateExecute\utils\Players;
use Taskovich\DonateExecute\event\NewDonateEvent;

class DonateHandler
{

	/**
	 * @var bool
	 */
	public static bool $cancel = true;

	/**
	 * @param string $donate_data 
	 * @return void
	 */
	public static function execute(string $donate_data): void
	{
		if(self::$cancel)
			return;
		$data = json_decode($donate_data, true)["data"][0];

		$event = new NewDonateEvent($data);
		$event->call();

		if($event->isCancelled())
			return;

		$amount = strval($data["amount"]) . $data["currency"];
		$donate = Configs::getPriceList()->get($amount);

		if($donate === null)
			return;

		$notices = Configs::getConfig()->get("notice");

		if($notices["enable"]) {
			$notices = self::formatter($notices[$amount] ?? $notices["default"], $data);
			$commands = [
				self::formatter($donate["by_player"], $data),
				self::formatter($donate["by_console"], $data)
			];

			foreach(Players::getPlayers() as $name => $player) {
				foreach($notices as $type => $notice)
					match($type) {
						"title" => $player->sendTitle($notice, 20, 60, 20),
						"subtitle" => $player->sendSubTitle($notice),
						"actionbar" => $player->sendActionBarMessage($notice),
						"message" => $player->sendMessage($notice),
					};

				foreach($commands[0] as $command) {
					$command = str_replace("{player}", $player->getName(), $command);
					Server::getInstance()->dispatchCommand($player, $command);
				}

				foreach($commands[1] as $command) {
					$command = str_replace("{player}", $player->getName(), $command);
					Server::getInstance()->dispatchCommand(
						new ConsoleCommandSender(
							Server::getInstance(),
							Server::getInstance()->getLanguage()
						),
						$command
					);
				}
			}
		}

	}

	/**
	 * @param string[] $strings 
	 * @return string[]
	 */
	private static function formatter(array $strings, array $data): array
	{
		foreach($strings as $type => $string)
			$strings[$type] = str_replace(
				[
					"{sender}",
					"{amount}",
					"{currency}",
					"{message}"
				],
				[
					$data["username"],
					$data["amount"],
					$data["currency"],
					$data["message"] ?? Language::translate("notice.null")
				],
				$string
			);

		return $strings;
	}

}