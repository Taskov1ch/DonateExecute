<?php

declare(strict_types=1);

namespace Taskovich\DonateExecute\event;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

class NewDonateEvent extends Event implements Cancellable
{

	use CancellableTrait;

	/**
	 * @param array $data
	 */
	public function __construct(
		private array $data
	) {}

	/**
	 * @return array
	 */
	public function getAllData(): array
	{
		return $this->data;
	}

	/**
	 * @return int|float
	 */
	public function getAmount(): int|float
	{
		return $this->data["amount"];
	}

	/**
	 * @return string
	 */
	public function getCurrency(): string
	{
		return $this->data["currency"];
	}

	/**
	 * @return string
	 */
	public function getSenderName(): string
	{
		return $this->data["username"];
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->data["id"];
	}

	/**
	 * @return string|null
	 */
	public function getMessage(): ?string
	{
		return null;
		// return $this->data["message"] ?? null;
	}

}