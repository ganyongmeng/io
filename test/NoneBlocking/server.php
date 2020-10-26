<?php
	
require __DIR__.'/../../vendor/autoload.php';

use ganym\io\NoneBlocking\Worker;

$host = "tcp://127.0.0.1:9000";
$server = new Worker($host);

//$server->onConnect = function($socket,$client) {
//	echo "有一个链接进来了\n";
//	var_dump($client);
//};

//接收和处理信息
$server->onReceive = function($socket,$client,$data) {
	echo date("Y-m-d H:i:s")."给链接发送消息\n";
	sleep(4);
	$socket->send($client,"hello");
};

//$server->onClose = function($socket,$client){
//    echo '关闭了链接';
//    $socket->close($client);
//};

$server->start();