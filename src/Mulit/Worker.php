<?php
namespace ShineYork\Io\Multi;

// 这是等会自个要写的服务
class Worker
{

    // 自定义服务的事件注册函数，
    // 这三个是闭包函数
    public $onReceive = null;
    public $onConnect = null;
    public $onClose = null;

    protected $sockets = [];
    // 连接
    public $socket = null;

    public function __construct($socket_address)
    {
        $this->socket = stream_socket_server($socket_address);
        stream_set_blocking($this->socket, 0);
        // 咋们的server也有忙的时候
        $this->sockets[(int) $this->socket] = $this->socket;
        // echo $socket_address."\n";
    }

    // 需要处理事情
    public function accept()
    {
        // 接收连接和处理使用
        while (true) {

            $read = $this->sockets;
            // 校验池子是否有可用的连接 -》 校验传递的数组中是否有可以用的连接 socket
            // 把连接放到$read
            // 它返回值其实并不是特别可靠

            stream_select($read, $w, $e, 1);
            foreach ($read as $socket) {
                // $socket 可能为
                if ($socket === $this->socket) {
                    // 创建与客户端的连接
                    $this->createSocket();
                } else {
                    // 发送信息
                    $this->sendMessage($socket);
                }
                // 1. 主worker
                // 2. 也可能是通过 stream_socket_accept 创建的连接
            }
        }
    }

    public function createSocket()
    {
        $client = stream_socket_accept($this->socket);
        // is_callable判断一个参数是不是闭包
        if (is_callable($this->onConnect)) {
            // 执行函数
            // ($this->onConnect)($this, $client);
        }
        // 把创建的socket的连接 -》 放到 $this->sockets
        $this->sockets[(int) $client] = $client;
    }

    public function sendMessage($client)
    {
        $data = fread($client, 65535);
        if ($data === '' || $data == false) {
            // 关闭连接
            // fclose($client);
            // unset($this->sockets[(int) $client]);
            return null;
        }
        if (is_callable($this->onReceive)) {
            ($this->onReceive)($this, $client, $data);
        }
    }
    public function send($client, $data)
    {
        fwrite($client, $data);
//        $response = "HTTP/1.1 200 OK\r\n";
//        $response .= "Content-Type: text/html;charset=UTF-8\r\n";
//        $response .= "Connection: keep-alive\r\n";
//        $response .= "Content-length: ".strlen($data)."\r\n\r\n";
//        $response .= $data;
//        fwrite($client, $response);
    }

    public function debug($data, $flag = false)
    {
        if ($flag) {
            var_dump($data);
        } else {
            echo "==== >>>> : ".$data." \n";
        }
    }

    // 启动服务的
    public function start()
    {
        $this->accept();
    }
}
