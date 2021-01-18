<?php

	// Prevent direct file access
	defined( 'ABSPATH' ) or exit;

	/** 
	*  funzione di login 
	**/
	
	function getToken($username,$password)
	{		
		$token = null;
		$ch = curl_init();
		
		$body = json_encode([
			'username' => $username,
			'password' => $password
		]);

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/login_check");
		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);

		curl_close ($ch);
		if ($responseInfo['http_code'] == 200) {
			$decodedBody = json_decode($serverOutput);
			if($decodedBody && property_exists($decodedBody,'token')){
				$token = $decodedBody->token;
			}
		}
		#endregion login call
		
		if(!$token) {
			return false;
		}
		
		return $token;
	}
	
	
	/**
	* function to get node list
	**/
	function getNodeList($token)
	{
		$listOfNodes = null;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/rest/v1/nodes");
		curl_setopt($ch, CURLOPT_POST, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer '.$token,
			'Content-Type: application/json'
		]);
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);
		
		curl_close ($ch);
		if ($responseInfo['http_code'] == 200) {
			$listOfNodes = $serverOutput;
		}
		
		if(!$listOfNodes) {
			return false;
		}
		#endregion
		
		return $listOfNodes;
	}
	
	
	/**
	* function get position list given a node
	**/
	function getPositionList($token, $nodeId)
	{
		$listOfPositions = null;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/rest/v1/nodes/".$nodeId."/positions");
		curl_setopt($ch, CURLOPT_POST, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$token,
			'Content-Type: application/json'
		));
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);
		
		curl_close ($ch);
		if ($responseInfo['http_code'] == 200) {
			$listOfPositions = $serverOutput;
		}
		
		if(!$listOfPositions) {
			return false;
		}
		
		return $listOfPositions;
	}
	
	
	/**
	* function to get position details
	**/
	function getPositionDetail($token, $nodeId, $positionId)
	{
		#region position detail
		$positionDetail = null;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/rest/v1/nodes/".$nodeId."/positions/".$positionId);
		curl_setopt($ch, CURLOPT_POST, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$token,
			'Content-Type: application/json'
		));
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);
		
		curl_close ($ch);
		if ($responseInfo['http_code'] == 200) {
			$positionDetail = $serverOutput;
		}
		
		if(!$positionDetail) {
			return false;
		}
		
		return $positionDetail;
		#endregion
	}
	
	
	/**
	*  function create application with given data
	**/
	function createApplication($token, $nodeId, $positionId, $data)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/rest/v1/nodes/".$nodeId."/positions/".$positionId."/applications");
		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$token,
			'Content-Type: application/json'
		));
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);
		
		$redirectUrl = null;

		curl_close ($ch);
		if ($responseInfo['http_code'] == 201) {
			$decodedBody = json_decode($serverOutput);
			if($decodedBody && property_exists($decodedBody,'web_url')){
				$redirectUrl = $decodedBody->web_url;
			}
		}
		elseif($responseInfo['http_code'] == 409) {
			$decodedBody = json_decode($serverOutput);
			if($decodedBody && property_exists($decodedBody,'web_url')){
				$redirectUrl = $decodedBody->web_url;
			}
		}

		if(!$redirectUrl) {
			return false;
		}
		return $redirectUrl;
	}
	
		/**
	* function to get position details
	**/
	function getPositionTemplates($token, $nodeId, $positionId)
	{
		#region position detail
		$positionTemplates = null;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, BASE_ENDPOINT."/api/rest/v1/nodes/".$nodeId."/positions/".$positionId."/templates");
		curl_setopt($ch, CURLOPT_POST, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$token,
			'Content-Type: application/json'
		));
		
		//should be 1 in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);

		$serverOutput = curl_exec($ch);
		$responseInfo = curl_getinfo($ch);
		
		curl_close ($ch);
		if ($responseInfo['http_code'] == 200) {
			$positionTemplates = $serverOutput;
		}
		
		if(!$positionTemplates) {
			return false;
		}
		
		return $positionTemplates;
		#endregion
	}
	