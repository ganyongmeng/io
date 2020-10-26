#!/usr/bin/env python
# -*- coding: utf-8 -*-

import socket

def start_blocking(self):
        """非阻塞server"""
        self.ssock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.ssock.bind(('', 8080))
        self.ssock.listen(5)
        count = 0
        while True:
            conn, addr = self.ssock.accept()
            count += 1
            print 'Connected by', addr
            print 'Accepted clinet count:%d' % count
            data = conn.recv(1024) #若无数据则阻塞
            if data:
                conn.sendall(data)
            conn.close()

if __name__ == '__main__':
    start_blocking()


