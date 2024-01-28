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
	public static function checkAndAddPlayer(Player $player): void
	{
		if(!self::isAllowedPlayer($player))
			return;
		$name = strtolower($player->getName());
		self::$players[$name] = $player;
	}

	/**
	 * @param Player $player 
	 * @return void
	 */
	public static function checkAndRemovePlayer(Player $player): void
	{
		if(!self::isAllowedPlayer($player))
			return;
		$name = strtolower($player->getName());
		unset(self::$players[$name]);
	}

	/**
	 * @return Player[]
	 */
	public static function getPlayers(): array
	{
		return self::$players;
	}

	/**
	 * @param Player $player 
	 * @return bool
	 */
	public static function isAllowedPlayer(Player $player): bool
	{
		$allowed_players = Configs::getConfig()->get("allowed_players");
		$name = strtolower($player->getName());

		return in_array($name, $allowed_players);
	}

}