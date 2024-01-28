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
	 * @param array|null $args 
	 * @return string|null
	 */
	public static function translate(string $translatable, array $args = []): ?string
	{
		$translate = self::$lang[$translatable] ?? false;

		if(!$translate)
			return null;

		foreach($args as $key => $value) {
			$translate = str_replace($key, $value, $translate);
		}

		return $translate;
	}

}