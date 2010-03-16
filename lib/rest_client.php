<?php

require_once( dirname(__FILE__) . "/exceptions.php" );
require_once( dirname(__FILE__) . "/rest_resource.php" );

/**

  alle vier CRUD Methoden:

  Create:  post()
  Read:    read()
  Update:  put()
  Delete:  delete()

*/
class RestClient {
	
	private $username;
	private $password;
	private $base_url;
	
	private static $content_type = 'application/json';
	private static $timeout      = 15; // seconds

	function __construct($base_url,$username=null,$password=null){
		$this->base_url = $base_url;
		$this->username = $username;
		$this->password = $password;
	}

	public function get($url){
		return $this->send($url,'GET');
	}
	
	public function post($url,$data){
		return $this->send($url,'POST',$data);
	}
	
	public function put($url,$data){
		return $this->send($url,'PUT',$data);
	}
	
	public function delete($url){
		return $this->send($url,'DELETE');
	}
	
	public function resource($path){
		return new RestResource($this,$path);
	}
	
	/**
	 * Sendet eine REST-Anfrage an den Server
	 */
	protected function send($url,$method,$data=null){
		
		$header[] = "Accept: " . self::$content_type;
		$header[] = "Content-Type: ". self::$content_type;
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,            $this->base_url.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,        self::$timeout);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($data));
		
		if($this->username){
			curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		}
		
		/*
			Wenn hier der fehler 'error setting certificate verify locations' auftritt, 
			dann fehlt das ca-certificates-Paket. Das muss nachinstalliert werden mit:
			 
			apt-get install ca-certificates
		*/
		$data = curl_exec($ch);
		
		if (curl_errno($ch)) {
			// CURL-Fehler
			throw new CurlException(curl_error($ch),curl_errno($ch));
		}
		else {
			$info = curl_getinfo($ch);
			curl_close($ch);
			
			$http_code = $info['http_code'];
			
			if($http_code==422){ // Unprocessable Entity
				$errors = json_decode($data);
				
				if(is_array($errors)){
					$message = "Unprocessable Entity:";
					foreach($errors as $error){
						$message .= "\n- " . implode(" ",$error);
					}
					throw new RestException($message, 422, $errors);
				}
				else{
					throw new RestException($data, 422);
				}
			}
			if($http_code==201){ // Created
				//TODO hier könnte noch die URL ermittelt werden, zu der weitergeleitet wird
				return true;
			}
			if($http_code >= 400){
				throw new RestException("Response-Code $http_code",$http_code);
			}
			
			if($method=='DELETE'){
				return $http_code==200;
			}
			
			return $data;
		}
	}
	
	/**
	 * Serialisiert ein Array als URL-Parameter
	 */
	public static function array2params($array,$prefix=null){
		if(!$array){
			return '';
		}
		$data = "";
		foreach($array as $key => $val){
			$fullKey = $prefix ? "{$prefix}[$key]" : $key;

			if(is_array($val)){
				$data .= self::array2params($val, $fullKey);
			}
			else{
				$data .= '&' . urlEncode($fullKey) . '=' . urlEncode($val);
			}
		}
		return $data[0]=="&" ? substr($data,1) : $data;
	}
	
}

?>