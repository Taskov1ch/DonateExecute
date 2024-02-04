<?php

declare(strict_types=1);

namespace Taskovich\DonateExecute\command;

use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Taskovich\DonateExecute\Loader;
use Taskovich\DonateExecute\utils\Language;
use Taskovich\DonateExecute\DonateHandler;

class AllowDonates extends Command implements PluginOwned
{
	public function __construct(
		private Loader $loader
	)
	{
		parent::__construct("aldon");
		$this->setDescription(
			Language::translate("command.allow_or_disallow.description") ??
			"Allow or disallow actions for donations"
		);
		$this->setPermission("de.al_or_dis");
	}

	/**
	 * @return Loader
	 */
	public function getOwningPlugin(): Loader
	{
		return $this->loader;
	}

	/**
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if(DonateHandler::$cancel) {
			DonateHandler::$cancel = false;
			$sender->sendMessage(
				Language::translate("command.allow.success") ??
				"Actions for donations are allowed"
			);
			return true;
		}
		DonateHandler::$cancel = true;
		$sender->sendMessage(
			Language::translate("command.disallow.success") ??
			"Actions for donations are disallowed"
		);
		return true;
	}

}