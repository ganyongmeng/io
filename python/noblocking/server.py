#!/usr/bin/env python
# -*- coding: utf-8 -*-

import socket

def start_noblocking(self):
        """
        同步非阻塞
        """
        self.ssock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.ssock.bind(('', 8080))
        self.ssock.listen(5)
        count = 0
        while True:
            conn, addr = self.ssock.accept()
            conn.setblocking(0) #设置为非阻塞socket
            count += 1
            print 'Connected by', addr
            print 'Accepted clinet count:%d' % count
            try:
                data = conn.recv(1024) #非阻塞,没有数据会立刻返回
                if data:
                    conn.sendall(data)
            except Exception as e:
               pass
            finally:
                conn.close()

if __name__ == '__main__':
    start_noblocking()


