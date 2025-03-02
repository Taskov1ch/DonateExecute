<?php

namespace Taskov1ch\DonateExecute;

use IvanCraft623\languages\Language;
use IvanCraft623\languages\Translator;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;
use Taskov1ch\DonateExecute\donations\DonatesHandler;

class DonateExecute extends PluginBase
{
	use SingletonTrait;

	private ?TaskHandler $task;
	private DonatesHandler $donatesHandler;
	private Translator $translator;

	private array $players = [];
	private array $pricelist;
	private int $antiSpam = 0;

	public function onEnable(): void
	{
		self::setInstance($this);

		$this->donatesHandler = new DonatesHandler($this);

		$this->loadTranslations();
		$this->loadPriceList();
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents(new EventsListener($this), $this);
	}

	private function loadTranslations(): void
	{
		$this->translator = new Translator($this);

		$files = glob(Path::join($this->getResourceFolder(), "languages", "*.yml"));

		foreach ($files as $file) {
			$this->saveResource(Path::join("languages", $file));

			$language = new Language($file,
				(new Config(Path::join($this->getDataFolder(), "languages", $file)))->getAll()
			);

			$this->translator->registerLanguage($language);
		}

		$this->translator->setDefaultLanguage($this->getConfig()->get("default_language"));
	}

	private function loadPriceList(): void
	{
		$this->saveResource("pricelist.yml");
		$this->pricelist = (new Config(Path::join($this->getDataFolder() . "pricelist.yml")))->getAll();
	}

	public function getTranslator(): Translator
	{
		return $this->translator;
	}

	public function getPriceList(): array
	{
		return $this->pricelist;
	}

	public function startDonations(): bool
	{
		if ($this->antiSpam >= time()) {
			return false;
		}

		if ($this->task !== null) {
			$this->stopDonations();
		}

		$this->task = $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
			fn() => $this->donatesHandler->execute()
		), 20 * $this->getConfig()->get("delay"));

		$this->donatesHandler->schedule();
		$this->antiSpam = time() + 15;

		return true;
	}

	public function stopDonations(): bool
	{
		if ($this->antiSpam >= time()) {
			return false;
		}

		$this->task?->remove();
		$this->task = null;
		$this->donatesHandler->stop();
		$this->antiSpam = time() + 15;

		return true;
	}

	public function taskIsRunning(): bool
	{
		return $this->task !== null;
	}

	public function getDonateHandler(): DonatesHandler
	{
		return $this->donatesHandler;
	}

	public function addPlayer(Player $player): void
	{
		$name = strtolower($player->getName());
		$allowedPlayers = $this->getConfig()->get("allowed_players");

		if (!isset($this->players[$name]) and in_array($name, $allowedPlayers)) {
			$this->players[$name] = $player;
		}
	}

	public function removePlayer(Player $player): void
	{
		$name = strtolower($player->getName());

		if (isset($this->players[$name])) {
			unset($this->players[$name]);
		}
	}

	/**
	 * @return Player[]
	 */
	public function getPlayers(): array
	{
		return $this->players;
	}
}