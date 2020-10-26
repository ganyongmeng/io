<?php
	require 'e.php';
	use \Event as Event;
	use \EventBase as EventBase;
	
	$socketAddress = "tcp://0.0.0.0:9000";
	echo $socketAddress;
	
	$server = stream_socket_server($socketAddress);
	$eventBase = new EventBase();
	
	//记录我们所创建的这样时间，让$eventBase可以找到这个事件
	$count = [];
	
	$event = new Event($eventBase,$server,Event::PERSIST | Event::READ | Event::WRITE,function($socket) use ($eventBase,&$count){
		
		echo "链接开始\n";
		
		//建立与用户链接
		//在闭包中的function($socket)中的$socket就是在构造函数中传递的$server这个属性，即$socket=$server
		$client = stream_socket_accept($socket);
		
		//加上这一句，然后两个终端同时请求，则可以看到这里是阻塞的
//		sleep(10);
		
		//第一次代码，不能处理多个事件
//		var_dump(fread($client,65535));
//		fwrite($client, "提前祝大家平安夜快乐 \n");
//		fclose($client);
		
		//第二次代码，嵌套处理多个事件
		//这个发现没有正常触发
//		$event2 = new Event($eventBase,$client,Event::PERSIST | Event::READ | Event::WRITE ,function($socket){
//			//对建立链接处理事件
//			var_dump(fread($socket,65535));
//			fwrite($socket, "提前祝大家平安夜快乐 \n");
//			fclose($socket);
//		});
//		$event2->add();
		//以上代码会出现一个问题，在嵌套里面的代码没有执行
		
		//第三次代码,要加上$count这个变量才能正常运行
		//因为Event 在同一个文件不能嵌套定义，而转为引用式的嵌套
		//event：因为在同一个作用域中，如果嵌套可能会存在覆盖问题；因此不支持
		(new E($eventBase,$client,$count))->handler();
		
		echo "链接结束 \n";
	});
	
	$event->add();
	
	$count[(int)$server][Event::PERSIST | Event::READ | Event::WRITE] = $event;
	
	$eventBase->loop();
