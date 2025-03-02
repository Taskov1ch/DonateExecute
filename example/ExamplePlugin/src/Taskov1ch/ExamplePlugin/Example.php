<?php

namespace Taskov1ch\ExamplePlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use Taskov1ch\DonateExecute\events\NewDonateEvent;

class Example extends PluginBase implements Listener
{

	public function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDonate(NewDonateEvent $event): void
	{
		$message = $event->getMessage() ?? "..."; // getMessage() will return null if the message is not specified.
		$senderName = $event->getSenderName() ?? "Anonymous"; // getSenderName() will return null if the sender is not specified.
		$amount = $event->getAmount();
		$currency = $event->getCurrency();

		$this->getServer()->broadcastTitle("+{$amount} {$currency}", "By {$senderName}", 20, 40, 20);
		$this->getServer()->broadcastMessage("{$senderName} donated {$amount} {$currency} with message: {$message}");

		$event->cancel(); // The cancellation of the event will also cancel the execution of commands.
	}

}
