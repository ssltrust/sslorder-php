<?php

class CurlException extends Exception
{
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
}


class RestException extends Exception
{
	protected $errors;
	
	public function __construct($message, $code = 0, $errors = null) {
		// etwas Code
		$this->errors = $errors;
		
		// sicherstellen, dass alles korrekt zugewiesen wird
		parent::__construct($message, $code);
	}
	
	public function errors(){
		return $this->errors;
	}
}

?>