<?php

namespace Taskov1ch\DonateExecute\donations;

class Requests
{
	const URL = "https://www.donationalerts.com/api/v1/";

	public function __construct(private string $token)
	{}

	public function getDonationList(): array
	{
		$headers = ["Authorization: Bearer " . $this->token];

		$ch = curl_init(self::URL . "alerts/donations");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		curl_close($ch);

		if (!$response) {
			return ["error" => "unknown_error"];
		}

		$data = json_decode($response, true);

		if (
			isset($data["message"]) &&
			$data["message"] === "Unauthenticated."
		) {
			return ["error" => "broken_token"];
		}

		return $data;
	}
}