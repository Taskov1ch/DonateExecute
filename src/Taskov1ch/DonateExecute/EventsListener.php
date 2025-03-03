<?php

namespace Taskov1ch\DonateExecute;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventsListener implements Listener
{

	public function __construct(private DonateExecute $main)
	{
	}

	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		$this->main->addPlayer($player);
	}

	public function onQuit(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		$this->main->removePlayer($player);
	}
}
