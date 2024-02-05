<?php

namespace Taskovich\DonateExecute\utils;

class Language
{

	/**
	 * @var string[]
	 */
	private static array $lang = [];

	/**
	 * @param string[] $lang
	 */
	public function __construct(array $lang)
	{
		self::$lang = $lang;
	}

	/**
	 * @return string[]
	 */
	public static function getAll(): array
	{
		return self::$lang;
	}

	/**
	 * @param string $translatable 
	 * @return string|null
	 */
	public static function translate(string $translatable): ?string
	{
		$translate = self::$lang[$translatable] ?? false;

		if(!$translate)
			return null;

		return $translate;
	}

}
