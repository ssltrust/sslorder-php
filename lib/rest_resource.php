<?php

class RestResource {
	
	private $client;
	private $url;
	
	function __construct($client,$url){
		$this->client = $client;
		$this->url    = $url;
	}
	
	public function getAll($params=null){
		$query = RestClient::array2params($params);
		return $this->query('get', $this->url . ($query ? "?$query" : '') );
	}
	
	public function get($id){
		return $this->query('get',$this->url.'/'.$id);
	}
	
	public function create($data){
		return $this->client->post($this->url,$data);
	}
	
	public function update($id,$data){
		return $this->client->put($this->url.'/'.$id,$data);
	}
	
	public function delete($id){
		return $this->client->delete($this->url.'/'.$id);
	}
	
	public function custom($method,$action,$data=null){
		return $this->query($method,$this->url."/$action",$data);
	}
	
	public function customId($method,$id,$data=null){
		return $this->query($method,$this->url."/$id/$action",$data);
	}
	
	protected function query($method,$url,$data=null){
		$response = null;
		
		if($data){
			$response = $this->client->$method($url,$data);
		}else{
			$response = $this->client->$method($url);
		}
		
		return json_decode($response);
	}
	
}

?>