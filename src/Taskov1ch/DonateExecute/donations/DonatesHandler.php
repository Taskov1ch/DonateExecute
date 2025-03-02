<?php

namespace Taskov1ch\DonateExecute\donations;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\Server;
use Taskov1ch\DonateExecute\DonateExecute;
use Taskov1ch\DonateExecute\events\NewDonateEvent;
use Taskov1ch\DonateExecute\task\AsyncGetDonates;

class DonatesHandler
{
	private array $donates = [];
	private int $lastDonateId;
	private Requests $requests;
	private TaskHandler $task;

	public function __construct(private DonateExecute $main)
	{
		$this->requests = new Requests($main->getConfig()->get("token"));
	}

	public function checkToken(): void
	{
		$donates = $this->requests->getDonationList();

		if (isset($donates["error"])) {
			$this->main->getLogger()->critical($this->main->getTranslator()->translate(
				null, $donates["error"] === "broken_token" ? "donations.broken_token" : "donations.unknown_error"
			));
			$this->main->getServer()->getPluginManager()->disablePlugin($this->main);
			return;
		}
	}

	public function schedule(): void
	{
		$this->task = $this->main->getScheduler()->scheduleRepeatingTask(new ClosureTask(
			fn() => $this->asyncGetDonations()
		), 20 * 10);
	}

	public function stop(): void
	{
		$this->task->remove();
	}

	public function asyncGetDonations(): void
	{
		Server::getInstance()->getAsyncPool()->submitTask(new AsyncGetDonates(serialize($this->requests)));
	}

	public function addDonates(array $data): void
	{
		$newDonations = array_filter($data,
			function ($donation) {
				$sum = $donation["amount"] + $donation["currency"];
				return $donation["id"] > $this->lastDonateId and
					isset($this->main->getPriceList()[$sum]);
			}
		);

		if (empty($newDonations)) {
			return;
		}

		$this->lastDonateId = $newDonations[0]["id"];
		$this->donates = array_merge($this->donates, $newDonations);
	}


	public function execute(?array $data = null): void {
		$donate = $data ?? array_shift($this->donates);

		$event = new NewDonateEvent($donate);
		$event->call();

		if ($event->isCancelled()) {
			return;
		}

		$sum = strval($donate["amount"]) . $donate["currency"];
		$actions = $this->main->getPriceList()[$sum] ?? null;

		if ($actions === null) {
			return;
		}

		$replacements = ["{sender}", "{amount}", "{currency}", "{message}"];
		$values = [
			$donate["username"] ?? $this->main->getConfig()->get("default_sender"),
			$donate["amount"],
			$donate["currency"],
			$donate["message"] ?? $this->main->getConfig()->get("default_message")
		];

		$actions["chat"] = array_map(
			fn($msg) => str_replace($replacements, $values, $msg), $actions["chat"]
		);

		$actions["commands"] = array_map(
			fn($cmd) => str_replace($replacements, $values, $cmd), $actions["commands"]
		);

		$server = Server::getInstance();

		foreach ($this->main->getPlayers() as $player) {
			foreach ($actions["chat"] as $message) {
				$message = str_replace("{player}", $player->getName(), $message);
				$player->chat($message);
			}

			foreach ($actions["commands"] as $command) {
				$command = str_replace("{player}", $player->getName(), $command);
				$server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), $command);
			}
		}
	}
}