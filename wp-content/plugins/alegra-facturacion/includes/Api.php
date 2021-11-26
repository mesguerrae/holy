<?php 

namespace includes;

class Api{

	public $method;
	public $auth 	= 'andres@holycosmetics.com.co:39d1539eea6b4c298c1e';
	public $baseUrl = 'https://api.alegra.com/api/v1/';

	function __construct(){
	}

	public function get($uri, $params){
		return $this->curl(__FUNCTION__, $uri, $params);
	}

	public function post($uri, $params){

		return $this->curl(__FUNCTION__, $uri, $params);
	}

	public function put($uri, $params){

		return $this->curl(__FUNCTION__, $uri, $params);
	}

	public function delete(){

	}

	public function curl($method, $uri, $params){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$uri);
		$headers = array(
			'Content-Type:application/json',
    		'Authorization: Basic '. base64_encode($this->auth) // <---
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		if (!empty($params)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;

	}


}