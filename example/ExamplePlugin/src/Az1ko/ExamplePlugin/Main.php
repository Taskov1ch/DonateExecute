<?php

namespace Az1ko\ExamplePlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use Taskovich\DonateExecute\event\NewDonateEvent; // Importing the event class

class Main extends PluginBase implements Listener {

	public function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDonate(NewDonateEvent $event): void { // Processing the event
		$message = $event->getMessage() ?? "idk :)";
		$this->getLogger()->info(
			"NEW DONATE!\n" .
			"From: " . $event->getSenderName() . "\n" .
			"Amount: " . $event->getAmount() . "\n" .
			"Currency: " . $event->getCurrency() . "\n" .
			"Message: " . $message
		);

		$event->cancel(); // Cancellation of the event will lead to the cancellation of the actions for the donation
	}

}
