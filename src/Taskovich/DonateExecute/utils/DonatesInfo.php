<?php

namespace Taskovich\DonateExecute\utils;

class DonatesInfo
{

	/**
	 * @var int
	 */
	private static array $last_donate_data = [];

	/**
	 * @param mixed[] $data 
	 * @return void
	 */
	public static function updateLastDonate(array $data): void
	{
		self::$last_donate_data = $data;
	}

	/**
	 * @return mixed[]
	 */
	public static function getLastDonate(): array
	{
		return self::$last_donate_data;
	}

	/**
	 * @param string $donate 
	 * @return bool
	 */
	public static function checkDonate(string $donate): bool
	{
		$data = json_decode($donate, true)["data"][0];

		if(self::getLastDonate()["id"] === $data["id"])
			return false;

		self::updateLastDonate($data);
		return true;
	}

}