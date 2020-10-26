<?php
namespace ganym\io\Blocking;

class Worker{

	//自定义服务的事件注册函数
	//这三个都是闭包函数
    public $onReceive = null;
    public $onConnect = null;
    public $onClose = null;
	
	public $socket = null;

	public function __construct($socketAddress){
		$this->socket = stream_socket_server($socketAddress);
		echo $socketAddress."\n";
	}
	
	//需要处理事件
	public function accept(){
		//接收链接和处理事情
		while (true) {
			//监听过程是阻塞的（所以client.php里面while，只有第一次是可以正常执行，第二次就被阻塞了，这里可以用多路复用，client的while就不用阻塞了）
			$client = @stream_socket_accept($this->socket);
			
			sleep(5);
			
			//is_callable 判断一个参数是不是闭包
			if(is_callable($this->onConnect)){
				//执行函数
				($this->onConnect)($this,$client);
			}
			
			$buffer = "";
			$buffer = fread($client, 65535);
			if(is_callable($this->onReceive)){
				//执行函数
				($this->onReceive)($this,$client,$buffer);
			}
			//TCP 处理粘包问题，要用这3行代码更加严谨，24行代码没有处理异常情况
	//		while (!feof($client)) {
	//			$buffer = $buffer.fread($client, 65535);
	//		}
			var_dump($buffer);

            if(is_callable($this->onClose)){
                //执行函数
                ($this->onClose)($this,$client);
            }

		}
	}
	
	//发送信息函数
	public function send($conn,$data) {
		//默认是tcp的协议，要返回数据直接执行如下语句：
		fwrite($conn, $data);
		
		//如果需要能在浏览器访问，则返回数据要加上http的响应头信息
//		$response = "HTTP/1.1 200 OK\r\n";
//		$response .= "Content-Type: text/html;charset=utf-8\r\n";
//		$response .= "Connection: keep-alive\r\n";
//		$response .= "Content-length: ".strlen($data)."\r\n\r\n";
//		$response .= $data;
//		fwrite($conn, $response);
	}

	//关闭函数
	public function close($conn){
        fclose($conn);
    }

	public function start(){
	    $this->accept();
	}

}
