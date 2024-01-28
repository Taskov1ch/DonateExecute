<?php

namespace Taskovich\DonateExecute;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use Taskovich\DonateExecute\utils\Players;

class EventListener implements Listener
{

	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		Players::checkAndAddPlayer($player);
	}

	public function onQuit(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		Players::checkAndRemovePlayer($player);
	}

}