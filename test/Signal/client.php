<?php


$client = stream_socket_client("tcp://127.0.0.1:9000");

while (true){
    echo "\n\r start send msg \n\r";
    fwrite($client,'hello world');
    echo "\n\r end send msg \n\r";
    var_dump(fread($client,65535));
    echo "\n\r end --- \n\r";

    sleep(1);
}


	//安装信号
//	pcntl_signal(SIGIO, "sig_handler");
//
//	//信号处理函数
//	function sig_handler($signo)
//	{
//		sleep(2);
//		echo "这是测试信号的一个测试类";
//	}
//
//	//posix_kill该函数是一个安装信号的操作
//	//第一个参数pid(即是进程的pid)， 第二个参数为要设置的信号
//	//根据进程设置信号
//	//posix_getpid() 函数可以获取进程id
//	posix_kill(posix_getpid(), SIGIO);
//
//	echo "其他事情";
//
//	//分发
//	pcntl_signal_dispatch();
	
	//信号是配合与多进程使用
	//posix_getpid函数只会针对于当前的进程去设置信号
	
	//输出结果为：其他事情这是测试信号的一个测试类
	//也就是执行了分发之后才调用sig_handler函数
	
	
	//posix_kill 告诉那些人这些信号是干嘛的
	//pcntl_signal_dispatch  实战
	//pcntl_signal 设置信号的作用
	