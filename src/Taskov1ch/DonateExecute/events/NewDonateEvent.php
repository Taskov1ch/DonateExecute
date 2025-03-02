<?php

namespace Taskov1ch\DonateExecute\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

class NewDonateEvent extends Event implements Cancellable
{
	use CancellableTrait;


	public function __construct(private array $data)
	{}

	public function getAllData(): array
	{
		return $this->data;
	}

	public function getAmount(): float
	{
		return $this->data["amount"];
	}

	public function getCurrency(): string
	{
		return $this->data["currency"];
	}

	public function getSenderName(): string
	{
		return $this->data["username"];
	}

	public function getId(): int
	{
		return $this->data["id"];
	}

	public function getMessage(): ?string
	{
		return $this->data["message"] ?? null;
	}
}