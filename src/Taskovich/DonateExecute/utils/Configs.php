<?php

namespace Taskovich\DonateExecute\utils;

use pocketmine\utils\Config;

use Taskovich\DonateExecute\utils\Environment;

class Configs
{

	/**
	 * @var Config
	 */
	private static Config $config;

	/**
	 * @var mixed[]
	 */
	private static Config $pricelist;

	/**
	 * @param Config $config 
	 * @return void
	 */
	public static function init(Config $config, Config $pricelist): void
	{
		self::$config = $config;
		self::$pricelist = $pricelist;
	}

	/**
	 * @return Config
	 */
	public static function getConfig(): Config
	{
		return self::$config;
	}

	/**
	 * @return Config
	 */
	public static function getPriceList(): Config
	{
		return self::$pricelist;
	}

}