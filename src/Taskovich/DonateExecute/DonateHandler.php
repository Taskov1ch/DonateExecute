<?php

namespace Taskovich\DonateExecute;

class DonateHandler {

	public static int $last_donate_id = 0;

	public function __construct(int $id)
	{
		self::$last_donate_id = $id;
	}

	public static function getLastDonateId(): int
	{
		return self::$last_donate_id;
	}

	public static function execute(string $data): void {
		
	}

}