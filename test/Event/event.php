<?php
	use \Event as Event;
	use \EventBase as EventBase;
	
	$eventBase = new EventBase();

	/**
	 * 第二个参数：
	 *		如果是-1 ：计时器
	 *		如果是信号 ：信号的标识 SIGIO、SIGHUP等
	 *		如果是socket ：传递socket资源
	 * 第三个参数：（ | 表示累加条件），针对闭包函数，不针对event对象类
	 *    Event::PERSIST ---- 表示事件循环执行
	 *	  Event::TIMEOUT ---- 表示间隔多久运行
	 */
	$event = new Event($eventBase,-1,Event::PERSIST | Event::TIMEOUT,function(){
		echo "hello world event \n";
	});
	$event->add(0.1);	//添加事件，后面0.1为可选，传递代表间隔时间
	
	//可以添加多个事件信号
	$event1 = new Event($eventBase,-1,Event::PERSIST | Event::TIMEOUT,function(){
		echo "hello world event 0.2\n";
	});
	$event1->add(0.2);	//添加事件，调用设置在eventBase的时间，比如说swoole中start启动事件
	
	$eventBase->loop();	//循环执行事件
	
	//EventBase 是一个事件库 --->存储创建的事件
	//even是一个事件