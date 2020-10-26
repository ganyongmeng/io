<?php

use \Event as Event;

class e{

	protected $client;
	
	protected $eventBase;

	function __construct($eventBase,$client,$count) {
		$this->eventBase = $eventBase;
		$this->client = $client;
	}
	
	public function handler(){
		$event2 = new Event($this->eventBase,$this->client,Event::PERSIST | Event::READ | Event::WRITE ,function($socket){
			//对建立链接处理事件
			var_dump(fread($socket,65535));
			fwrite($socket, "提前祝大家平安夜快乐 \n");
			fclose($socket);
			($this->count[(int)$socket][Event::PERSIST | Event::READ | Event::WRITE])->free();
		});
		$event2->add();
		$this->count[(int)$this->client][Event::PERSIST | Event::READ | Event::WRITE] = $event2 ;
		var_dump($this->count);
	}
	
}