<?php

namespace Taskovich\DonateExecute;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use Taskovich\DonateExecute\utils\Players;

class EventListener implements Listener
{

	/**
	 * @return void
	 */
	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		Players::checkAndAddPlayer($player);
	}

	/**
	 * @return void
	 */
	public function onQuit(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		Players::checkAndRemovePlayer($player);
	}

}