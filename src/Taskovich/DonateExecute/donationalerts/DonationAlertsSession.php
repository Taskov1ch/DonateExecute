<?php

namespace Taskovich\DonateExecute\donationalerts;

class DonationAlertsSession
{

	/**
	 * @var string
	 */
	private static string $token = "";

	/**
	 * @param string $token
	 */
	public function __construct(string $token) {
		self::$token = $token;
	}

	/**
	 * @param bool $array (optional) 
	 * @return string|array|false
	 */
	public static function getDonationList(bool $in_array = true): string|array|false
	{
		$url = "https://www.donationalerts.com/api/v1/alerts/donations";
		$response = Requests::get($url, ["Authorization: Bearer " . self::$token]);

		if(!$response)
			return false;

		$array_response = json_decode($response, true);

		if($array_response["message"] ?? "" === "Unauthenticated.")
			return false;

		return $in_array ? $array_response : $response;
	}

}