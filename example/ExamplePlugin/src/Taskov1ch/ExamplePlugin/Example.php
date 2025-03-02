<?php

namespace Taskov1ch\ExamplePlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use Taskov1ch\DonateExecute\event\NewDonateEvent;

class Main extends PluginBase implements Listener
{

	public function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDonate(NewDonateEvent $event): void
	{
		$message = $event->getMessage() ?? "..."; // getMessage() will return null if the message is not specified.
		$amount = $event->getAmount();
		$currency = $event->getCurrency();
		$senderName = $event->getSenderName();

		$this->getServer()->broadcastTitle("+{$amount} {$currency}", "By {$senderName}", 20, 40, 20);

		$event->cancel(); // The cancellation of the event will also cancel the execution of commands.
	}

}
