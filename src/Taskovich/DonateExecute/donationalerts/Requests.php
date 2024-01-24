<?php

namespace Taskovich\DonateExecute\donationalerts;

class Requests
{

	/**
	 * @param string $url 
	 * @param array $headers (optional)
	 * @return string|false
	 */
	public function get(string $url, array $headers = []): string|false
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response ?? false;
	}

}