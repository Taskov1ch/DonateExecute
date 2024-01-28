<?php

namespace Taskovich\DonateExecute;

use Taskovich\DonateExecute\utils\DonatesInfo;
use Taskovich\DonateExecute\utils\Configs;
use Taskovich\DonateExecute\utils\Language;
use Taskovich\DonateExecute\utils\Players;
use Taskovich\DonateExecute\event\NewDonateEvent;

class DonateHandler
{

	/**
	 * @param string $donate_data 
	 * @return void
	 */
	public static function execute(string $donate_data): void
	{
		$data = json_decode($donate_data, true)["data"][0];

		$event = new NewDonateEvent($data);
		$event->call();

		if($event->isCancelled())
			return;

		$amount = strval($data["amount"]) . $data["currency"];
		$donate = Configs::getPriceList()->get($amount);

		if(!$donate)
			return;

		$notices = Configs::getConfig()->get("notice");

		if($notices["enable"]) {
			$notices = self::formatter($notices[$amount] ?? $notices["default"], $data);

			foreach(Players::getPlayers() as $name => $player)
				foreach($notices as $type => $notice)
					match($type) {
						"title" => $player->sendTitle($notice, 20, 60, 20),
						"subtitle" => $player->sendSubTitle($notice),
						"actionbar" => $player->sendActionBarMessage($notice),
						"message" => $player->sendMessage($notice),
					};
		}

		// TODO COMMANDS
	}

	/**
	 * @param string[] $notices 
	 * @return string[]
	 */
	private static function formatter(array $notices, array $data): array
	{
		foreach($notices as $type => $notice)
			$notices[$type] = str_replace(
				[
					"{username}",
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
				$notice
			);

		return $notices;
	}

}