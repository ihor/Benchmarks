import sys
sys.setrecursionlimit(6000)

import pika
import time
from pika.adapters import SelectConnection
from config import *

connection = None
channels = {}
messages_sent = 0
message_properties = pika.BasicProperties(
    content_type='application/json',
    delivery_mode=1
)

def on_connected(connection):
    """
    :type connection pika.BaseConnection
    """
    for i in xrange(EXCHANGES_NUM):
        connection.channel(on_channel_open)

def on_channel_open(channel):
    """
    :type channel pika.channel.Channel
    """
    number = channel.channel_number
    print 'Opening channel c' + str(number)
    global channels
    channels[number] = channel
    channel.exchange_declare(exchange='e' + str(number), type='fanout', callback=on_exchange_declared)

def on_exchange_declared(frame):
    number = frame.channel_number
    print 'Sending messages to exchange e' + str(number)
    global messages_sent
    channel = channels[number]
    exchange = 'e' + str(number)
    for i in xrange(MESSAGES_NUM):
        channel.basic_publish(
            exchange=exchange,
            routing_key='key',
            body='m' + str(i),
            properties=message_properties
        )
        messages_sent += 1

    if messages_sent >= MESSAGES_NUM * EXCHANGES_NUM:
        # Close our connection
        connection.close()

if __name__ == '__main__':
    host = (len(sys.argv) > 1) and sys.argv[1] or '127.0.0.1'
    parameters = pika.ConnectionParameters(host)
    connection = SelectConnection(parameters, on_connected)
    try:
        start = time.time()
        connection.ioloop.start()
        end = time.time()
        print (end - start) * 1000, 'ms'
    except KeyboardInterrupt:
        connection.close()
        connection.ioloop.start()
