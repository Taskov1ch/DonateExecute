<?php

namespace Taskovich\DonateExecute\utils;

class Environment
{

	/**
	 * @var string[]
	 */
	private array $env_paths = [
		__DIR__ . "../.env",
		__DIR__ . "/../../../../.env"
	];

	/**
	 * @var mixed[]
	 */
	private array $env = [];

	/**
	 * @var bool
	 */
	private bool $status = false;

	/**
	 * @param array $path_options (optional)
	 */
	public function __construct(array $path_options = [])
	{
		$this->status = false;
		$env_files = array_merge($this->env_paths, $path_options);

		foreach($env_files as $option) {
			if(file_exists($option)) {
				$env_file = $option;
				break;
			}
		}

		if(isset($env_file)) {
			$env_content = file_get_contents($env_file);
			$env_lines = explode("\n", $env_content);

			foreach($env_lines as $line) {
				if(!empty($line) and strpos($line, "=") !== false and $line[0] !== "#") {
					list($key, $value) = explode("=", $line, 2);
					$key = trim($key);
					$value = trim($value);
					$this->env[$key] = $value;
				}
			}
			
			$this->status = true;
		}
	}

	public function isOk(): bool
	{
		return $this->status;
	}

	/**
	 * @param string $key 
	 * @return mixed
	 */
	public function get(string $key)
	{
		return $this->env[$key] ?? null;
	}

	/**
	 * @return mixed[]
	 */
	public function getAll(): array
	{
		return $this->env;
	}

}