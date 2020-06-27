#!/usr/bin/env python
import psutil
import requests
import os
import socket
import time
nexttime = time.time()

url = "127.0.0.1"
port = "5000"
node = socket.gethostname()

if os.getenv('SERVER_HOSTNAME') != None:
    url = os.getenv('SERVER_HOSTNAME')

if os.getenv('SERVER_PORT') != None:
    port = os.getenv('SERVER_PORT')

if os.getenv('MY_NODE_NAME') != None:
    node = os.getenv('MY_NODE_NAME')

def update_status():
    PARAMS = {'id':node,'cpu_value':psutil.cpu_percent(),"mem_value":psutil.virtual_memory().percent}
    print (psutil.cpu_percent())
    print (psutil.virtual_memory().percent)
    requests.get("http://{}:{}/submit".format(url,port), params=PARAMS)

import time
starttime = time.time()
while True:
    update_status()          # take t sec
    nexttime += 10
    sleeptime = nexttime - time.time()
    if sleeptime > 0:
        time.sleep(sleeptime)
