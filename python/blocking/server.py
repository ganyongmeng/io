#!/usr/bin/env python
# -*- coding: utf-8 -*-

import socket

def start_blocking(self):
        self.host = '123.207.123.108'
        self.port = 8080
        self.csock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.csock.connect((self.host, self.port))
        data = self.csock.recv(1024)
        print data

if __name__ == '__main__':
    start_blocking()


