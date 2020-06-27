import flask
import os
import json
import jinja2
from datetime import datetime
from flask import request
from flask import render_template

hostlist = []
loader=jinja2.FileSystemLoader('templates')

app = flask.Flask(__name__)
app.config["DEBUG"] = True

@app.route('/', methods=['GET'])
def home():
    print (hostlist)
    return render_template('home.html',hostlist = hostlist,title="Node Monitoring Site",description="Node monitoring")

@app.route('/submit', methods=['GET'])
def submit():

    if 'id' in request.args:
        id = request.args['id']
    else:
        return "Error: No id field provided. Please specify an id."

    if 'cpu_value' in request.args:
        cpu_value = request.args['cpu_value']
    else:
        return "Error: No cpu_value field provided. Please specify an cpu_value."

    if 'mem_value' in request.args:
        mem_value = request.args['mem_value']
    else:
        return "Error: No mem_value field provided. Please specify an mem_value."

    index = -1

    for dic in hostlist:
        index += 1
        if dic['hostname'] == id:
            print('here')
            hostlist[index] = {"hostname":id,"cpu_value":cpu_value,"mem_value":mem_value, "last_updated": datetime.now()}
            return "Success"

    hostlist.append({"hostname":id,"cpu_value":cpu_value,"mem_value":mem_value, "last_updated": datetime.now()})
    return "Success"

app.run(host='0.0.0.0')
