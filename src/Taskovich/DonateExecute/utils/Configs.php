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
	 * @var Config
	 */
	private static Config $pricelist;

	/**
	 * @param Config $config 
	 * @return void
	 */
	public function __construct(Config $config, Config $pricelist)
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
