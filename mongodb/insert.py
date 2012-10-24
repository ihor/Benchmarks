import pymongo, time
from gendict import gendict

DOCS_NUM = 2500
 
connection = pymongo.Connection()
docs = connection.benchmark.inscol
docs.remove({})

nums = range(0, DOCS_NUM)
data = gendict(8, 2)

start = time.time()
for i in nums:
    docs.insert(data)
end = time.time()

print 'Done', DOCS_NUM, 'inserts in', (end - start) * 1000, 'ms'
