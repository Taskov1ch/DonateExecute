<?php

namespace Taskovich\DonateExecute\donationalerts;

class DaRequests
{

	private const URL = "https://www.donationalerts.com/api/v1/";
	
	/**
	 * @param string $token 
	 * @param bool $in_array (optional) 
	 * @return string|null
	 */
	public static function getDonationList(string $token, bool $in_array = true): string|array|null
	{
		$headers = ["Authorization: Bearer " . $token];

		$ch = curl_init(self::URL . "alerts/donations");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);

		if(!$response)
			return null;

		$array_response = json_decode($response, true);

		if(!isset($array_response["message"]))
			return null;

		if($array_response["message"] === "Unauthenticated.")
			return null;

		return $in_array ? $array_response : $response;
	}

}