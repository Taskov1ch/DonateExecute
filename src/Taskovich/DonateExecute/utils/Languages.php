<?php

namespace Taskovich\DonateExecute\utils;

class Languages {

	private static array $lang = [];

	public function __construct(array $lang) {
		self::$lang = $lang;
	}

	public static function getAll() {
		return self::$lang;
	}

	public static function translate(string $translatable): string
	{
		# code...
	}

}