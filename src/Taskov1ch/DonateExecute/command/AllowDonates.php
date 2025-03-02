<?php

namespace Taskov1ch\DonateExecute\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use Taskov1ch\DonateExecute\DonateExecute;

class AllowDonates extends Command implements PluginOwned
{
	public function __construct(
		private DonateExecute $main
	) {
		parent::__construct("don", $main->getTranslator()->translate(null, "command.description"));
		$this->setPermission("de.al_or_dis");
	}

	# The most useless method in my opinion
	public function getOwningPlugin(): DonateExecute
	{
		return $this->main;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		$translator = $this->main->getTranslator();

		if ($this->main->taskIsRunning()) {
			$sender->sendMessage(
				$translator->translate($sender, $this->main->stopDonations() ? "command.disallowed" : "command.anti_spam")
			);
			return;
		}

		$sender->sendMessage(
			$translator->translate($sender, $this->main->startDonations() ? "command.allowed" : "command.anti_spam")
		);

	}
}