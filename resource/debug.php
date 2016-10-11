<?php

// Debug class


class CBdebug {
	public function __construct($toggle){
		if ($toggle == true){
			$debugEnabled = true;
		}
		$log = "";
	}

	public function add($debugMessage){
		if ($debugEnabled == true){
			echo $debugMessage;
		}
		$log + $debugMessage . PHP_EOL;
	}

	public function getMsg(){
		echo $log;
	}
}