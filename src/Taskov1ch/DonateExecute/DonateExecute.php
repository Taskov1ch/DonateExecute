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
use Taskov1ch\DonateExecute\commands\AllowDisallowDonates;
use Taskov1ch\DonateExecute\commands\FakeDonate;
use Taskov1ch\DonateExecute\donations\DonatesHandler;

class DonateExecute extends PluginBase
{
	use SingletonTrait;

	private ?TaskHandler $task = null;
	private DonatesHandler $donatesHandler;
	private Translator $translator;

	private array $players = [];
	private array $pricelist;
	private int $antiSpam = 0;

	public function onEnable(): void
	{
		self::setInstance($this);

		$this->saveResources();
		$this->loadTranslations();
		$this->loadPriceList();
		$this->registerCommands();
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents(new EventsListener($this), $this);

		$this->donatesHandler = new DonatesHandler($this);
		$this->donatesHandler->prepare();

		$this->startDonations();
	}

	public function onDisable(): void
	{
		$this->stopDonations();
	}

	private function saveResources(): void
	{
		$dirs = ["", "languages"];
		$resourceFolder = $this->getResourceFolder();

		foreach ($dirs as $dir) {
			$files = glob(Path::join($resourceFolder, $dir, "*.yml"));

			foreach ($files as $file) {
				$relativePath = str_replace($resourceFolder, "", $file);
				$this->saveResource($relativePath);
			}
		}
	}

	private function loadTranslations(): void
	{
		$defaultLang = $this->getConfig()->get("default_language");
		$files = glob(Path::join($this->getDataFolder(), "languages", "*.yml"));
		$this->translator = new Translator($this);

		foreach ($files as $file) {
			$langName = basename($file, ".yml");
			$lang = new Language(
				$langName,
				(new Config($file))->getAll()
			);

			$this->translator->registerLanguage($lang);

			if ($langName === $defaultLang) {
				$this->translator->setDefaultLanguage($lang);
			}
		}
	}

	private function loadPriceList(): void
	{
		$this->saveResource("pricelist.yml");
		$this->pricelist = (new Config(Path::join($this->getDataFolder() . "pricelist.yml")))->getAll();
	}

	private function registerCommands(): void
	{
		$this->getServer()->getCommandMap()->registerAll("DonateExecute", [
			new AllowDisallowDonates($this),
			new FakeDonate($this)
		]);
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
			fn () => $this->donatesHandler->execute()
		), 20 * $this->getConfig()->get("delay"));

		$this->donatesHandler->start();
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

	public function getDonatesHandler(): DonatesHandler
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
