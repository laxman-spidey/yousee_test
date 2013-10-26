<?php
class GCM
{
	private $URL = "https://android.googleapis.com/gcm/send";
	private $API_KEY = "AIzaSyA3HjWvDdq5hleMFK6E24t8FGopNyBWo7w";
	private $registrationIdArray;
	private $data;
	private $header;

	private $curl;
	public function GCM($data)
	{
		$this -> data = $data;
	}

	public function process()
	{
		$this -> constructRequest();
	}

	private function constructRequest()
	{
		$this -> curl = curl_init();
		$this -> constructBody($this -> getRegIdsFromDatabase(), $this -> data);
		$this -> header = array(
			"Authorization: key= " . $this -> API_KEY,
			"Content-Type: application/json",
			"Content-Length: " . strlen($this -> data)
		);
		
		curl_setopt($this -> curl, CURLOPT_URL, $this -> URL);
		curl_setopt($this -> curl, CURLOPT_HTTPHEADER, $this -> header);
		curl_setopt($this -> curl, CURLOPT_POSTFIELDS, $this -> data);
		curl_exec($this->curl);
		echo "ggjhhjk";

	}

	private function constructBody($registrationIdArray, $data)
	{

		$finalJsonObject = array(
			"data" => "$data",
			"registration_ids" => $registrationIdArray
		);
		echo json_encode($finalJsonObject);
	}

	private function onResponseRecieved()
	{

	}

	private function getRegIdsFromDatabase()
	{
		$regIdArray = array();
		$query = "SELECT gcm_registration_id FROM gcm_registrations WHERE 1";
		$queryResult = mysql_query($query);
		if ($queryResult)
		{
			while ($record = mysql_fetch_array($queryResult))
			{
				$regIdArray[] = $record['gcm_registration_id'];
			}
		}
		else
		{
			$regIdArray[] = "fjkejflksdjfls";
			$regIdArray[] = "fjkejflksdjfls";
			$regIdArray[] = "fjkejflksdjfls";
			$regIdArray[] = "fjkejflksdjfls";
			echo "Biscuit";
		}
		return $regIdArray;
	}

	public function test()
	{
		$this -> constructBody($this -> getRegIdsFromDatabase(), $this -> data);
	}

}
?>

