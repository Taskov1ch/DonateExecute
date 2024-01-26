<?php

namespace Taskovich\DonateExecute\utils;

use pocketmine\player\Player;

class Players
{

	/**
	 * @var mixed[]
	 */
	private static array $players = [];

	/**
	 * @param Player $player 
	 * @return void
	 */
	public static function addPlayer(Player $player): void
	{
		$name = strtolower($player->getName());
		self::$players[$name] = $players;
	}

	/**
	 * @param Player $player 
	 * @return void
	 */
	public static function removePlayer(Player $player): void
	{
		$name = strtolower($player->getName());
		unset(self::$players[$name]);
	}

	/**
	 * @return Player[]
	 */
	public static function getPlayers(): array
	{
		return array_values(self::$players);
	}

}