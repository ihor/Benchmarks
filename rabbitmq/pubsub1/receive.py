import sys
sys.setrecursionlimit(10000)

import pika
from pika.adapters import SelectConnection
from config import *

channels = {}
exchanges_consumers = {i: 0 for i in xrange(1, EXCHANGES_NUM + 1)}

def on_connected(connection):
    """
    :type connection pika.BaseConnection
    """
    for i in xrange(EXCHANGES_NUM * CONSUMERS_PER_EXCHANGE):
        connection.channel(on_channel_open)

def on_channel_open(channel):
    """
    :type channel pika.channel.Channel
    """
    number = channel.channel_number
    print 'Opening channel c' + str(number)
    global channels
    channels[number] = channel
    channel.queue_declare(queue='q' + str(number), exclusive=True, auto_delete=False, callback=on_queue_declared)

def on_queue_declared(frame):
    """
    :type frame pika.frame.Method
    """
    exchange_index = min(exchanges_consumers, key=exchanges_consumers.get)
    number = frame.channel_number
    print 'Channel c' + str(number) + ' is listening to exchange e' + str(exchange_index)
    exchanges_consumers[exchange_index] += 1
    channels[number].queue_bind(exchange='e' + str(exchange_index), queue=frame.method.queue, callback=on_queue_bound)

def on_queue_bound(frame):
    """
    :type frame pika.frame.Method
    """
    number = frame.channel_number
    channels[number].basic_consume(handle_delivery, queue='q' + str(number))

def handle_delivery(channel, method, header, body):
    """
    :type channel pika.channel.Channel
    """
    print 'c' + str(channel.channel_number) + ' << ' + body
    #pass

parameters = pika.ConnectionParameters(host='localhost')
connection = SelectConnection(parameters, on_connected)

try:
    connection.ioloop.start()
except KeyboardInterrupt:
    connection.close()
    # Loop until we're fully closed, will stop on its own
    connection.ioloop.start()
