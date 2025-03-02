<?php

namespace Taskov1ch\DonateExecute\commands;

use IvanCraft623\languages\Translator;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use Taskov1ch\DonateExecute\DonateExecute;

class AllowDisallowDonates extends Command implements PluginOwned
{
	private Translator $translator;

	public function __construct(
		private DonateExecute $main
	) {
		$this->translator = $this->main->getTranslator();

		parent::__construct("don", $this->translator->translate(null, "command.allow_disallow.description"));
		$this->setPermission("donate_execute.allow_disallow_donates");
	}

	# The most useless method in my opinion
	public function getOwningPlugin(): DonateExecute
	{
		return $this->main;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		if ($this->main->taskIsRunning()) {
			$sender->sendMessage(
				$this->translator->translate($sender, $this->main->stopDonations() ? "command.allow_disallow.disallowed" : "command.allow_disallow.anti_spam")
			);
			return;
		}

		$sender->sendMessage(
			$this->translator->translate($sender, $this->main->startDonations() ? "command.allow_disallow.allowed" : "command.allow_disallow.anti_spam")
		);

	}
}