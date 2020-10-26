<?php
	// 是建立连接
	$client = stream_socket_client("tcp://127.0.0.1:9000");
	
//	echo (int)$client;
//	exit;
	
	//设置为非阻塞状态
	//设置成非阻塞。设置资源是阻塞还是非阻塞,model =0非阻塞，1=阻塞（默认）。
	//非阻塞影响的是fwrite和fread，不影响stream_socket_accept，定时去访问空间是否存在数据
	//缺点：做异步，需要不断轮询内核，资源消耗大
	stream_set_blocking($client, 0);
	
	$new = time();
	// 给socket通写信息

	var_dump(time());
	fwrite($client, "hello world");
	var_dump(fread($client, 65535));

	echo "其他的业务\n";
	echo time()-$new;
	
	//异步获取
	//feof检查是否结束了，也可以使用swoole的定时器函数swoole_timer_tick()
//	$r = 0;
//	while (!feof($client)) {
//		//接收数据包的大小
//		var_dump(fread($client, 65535));
//		echo $r++."\n";
//		sleep(1);
//	}

	//验证stream_selelt函数效果
	$read = $write = $except = null;
	//stream_select 检查的方式根据数组 去 检测 socket状态
	$r = 0;
	while (!feof($client)) {
		//接收数据包的大小
		
		$read[] = $client;
		
		var_dump(fread($client, 65535));
		echo $r++."\n";
		sleep(1);
		echo "检测socket\n";
		//返回结果，0是可用，1是正在忙状态
		var_dump(stream_select($read, $write, $except,0));		

//		foreach ($read as $value) {
//			//code
//		}
	}

	