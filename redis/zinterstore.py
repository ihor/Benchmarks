import sys
sys.path.insert(0, '../')

import time
from utils import *
from redis import ConnectionPool, Redis
from random import sample
from optparse import OptionParser

connectionPool = ConnectionPool(host='localhost', db=11, port=6379, max_connections=1000)
redis = Redis(connection_pool=connectionPool)

def genval():
    return genstr(12, 'abcdef1234567890')

def genzset(key, size=300):
    with redis.pipeline() as pipe:
        for i in xrange(size):
            pipe.zadd(key, genval(), 1)
        pipe.execute()

def commonize(big_zset_key, small_zset_keys, num=1000, prob=.2):
    population_size = int(len(small_zset_keys) * prob)
    with redis.pipeline() as pipe:
        for i in xrange(num):
            val = genval()
            redis.zadd(big_zset_key, val, 1)
            for key in sample(small_zset_keys, population_size):
                redis.zadd(key, val, 1)
        pipe.execute()

def setup(big_zset_size=100000, small_zset_size=300, small_zsets_num=100, common_values_num=1000, common_values_prob=.2):
    print 'Flushing database...'
    redis.flushdb()
    
    print 'Generating big sorted set...'
    keys = ['big']
    genzset('big', big_zset_size)

    print 'Generating small sorted sets...'
    small_set_keys = []
    with redis.pipeline() as pipe:
        for i in xrange(small_zsets_num):
            key = 'small:' + str(i)
            small_set_keys.append(key)
            redis.sadd('small_keys', key)
            genzset(key, small_zset_size)
        pipe.execute()

    print 'Adding common values to generated sorted sets...'
    commonize('big', small_set_keys, common_values_num, common_values_prob)

def test():
    print 'Intersecting...'
    keys = redis.smembers('small_keys')
    start = time.time()
    for key in keys:
        redis.zinterstore('inter:' + key, [key, 'big'])
    end = time.time()
    print 'Done', len(keys), 'intersections in', (end - start) * 1000, 'ms'

if __name__ == '__main__':
    parser = OptionParser()
    parser.add_option('-s', '--setup', dest='setup', action='store_true', help='setup Redis database for the test', default=False)
    parser.add_option('--bsize', dest='bsize', help='big sorted set size', type='int', default=100000)
    parser.add_option('--ssize', dest='ssize', help='small sorted set size', type='int', default=300)
    parser.add_option('--snum', dest='snum', help='small sorted sets number', type='int', default=1000)
    parser.add_option('--cnum', dest='cnum', help='common values number', type='int', default=1000)
    parser.add_option('--cprob', dest='cprob', help='common values distribution probability between small sets', type='float', default=.2)
    parser.add_option('-t', '--test', dest='test', action='store_true', help='run test', default=False)
    (options, args) = parser.parse_args()

    if options.setup:
        setup(options.bsize, options.ssize, options.snum, options.cnum, options.cprob)

    if options.test:
        test()
