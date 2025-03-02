<?php

namespace Taskov1ch\DonateExecute\commands;

use IvanCraft623\languages\Translator;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use Taskov1ch\DonateExecute\DonateExecute;

class FakeDonate extends Command implements PluginOwned
{
	private Translator $translator;

	public function __construct(private DonateExecute $main)
	{
		$this->translator = $this->main->getTranslator();

		parent::__construct("fakedonate", $this->translator->translate(null, "command.fake_donate.description"));
		$this->setPermission("donate_execute.fake_donate");
	}

	public function getOwningPlugin(): DonateExecute
	{
		return $this->main;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if (count($args) < 2) {
			$sender->sendMessage($this->translator->translate($sender, "command.fake_donate.usage"));
			return;
		}

		if (!filter_var($args[0], FILTER_VALIDATE_FLOAT)) {
			$sender->sendMessage($this->translator->translate($sender, "command.fake_donate.error"));
			return;
		}

		$amount = floatval(array_shift($args));
		$currency = array_shift($args);

		if (!in_array($currency, $this->main->getConfig()->get("allowed_currencies"))) {
			$sender->sendMessage($this->translator->translate($sender, "command.fake_donate.error"));
			return;
		}

		$username = $args ? array_shift($args) : null;
		$message = $args ? implode(" ", $args) : null;

		$this->main->getDonatesHandler()->execute([
			"amount" => $amount,
			"currency" => $currency,
			"username" => $username,
			"message" => $message
		]);
		$sender->sendMessage($this->translator->translate($sender, "command.fake_donate.success"));
	}

}