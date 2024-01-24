<?php

namespace Taskovich\DonateExecute\donationalerts;

class DonationAlertsSession extends Requests
{

	/**
	 * @param string $token
	 */
	public function __construct(private string $token) {}

	/**
	 * @param bool $array (optional) 
	 * @return string|array|false
	 */
	public function getDonationList(bool $in_array = true): string|array|false
	{
		$url = "https://www.donationalerts.com/api/v1/alerts/donations";
		$response = $this->get($url, ["Authorization: Bearer " . $this->token]);

		if(!$response)
			return false;

		$array_response = json_decode($response, true);

		if($array_response["message"] ?? "" === "Unauthenticated.")
			return false;

		return $in_array ? $array_response : $response;
	}

}