<?php

namespace Taskov1ch\DonateExecute\donations;

class Requests
{
	const URL = "https://www.donationalerts.com/api/v1/";

	private array $headers;

	public function __construct(private string $token)
	{
		$this->headers = ["Authorization: Bearer " . $this->token];
	}

	public function getDonationList(): array
	{

		$ch = curl_init(self::URL . "alerts/donations");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For production, it's a really bad idea.
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Maybe I'll fix it in the future, but for now, it stays this way.

		$response = curl_exec($ch);

		if (!$response) {
			return ["error" => curl_error($ch)];
		}

		curl_close($ch);

		$data = json_decode($response, true);

		if (isset($data["message"])) {
			return ["error" => $data["message"] === "Unauthenticated." ? "broken_token" : $data["message"]];
		}

		if (!isset($data["data"])) {
			return ["error" => "Unknown Error"];
		}

		return $data["data"];
	}
}
