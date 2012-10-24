import pymongo, time
from gendict import gendict
from random import randint

DOCS_NUM = 10000
DOC_SIZE = 6
DOC_NESTING = 2
 
connection = pymongo.Connection()
docs = connection.benchmark.readupdcol
docs.remove({})

for i in xrange(0, DOCS_NUM):
	docs.insert(gendict(DOC_SIZE, DOC_NESTING))
print 'Generated', DOCS_NUM, 'documents'

change = gendict(DOC_SIZE, 1)

start = time.time()
for doc in docs.find():
	updated_key = doc.keys()[randint(0, DOC_SIZE - 1)]
	docs.update({"_id": doc['_id']}, {"$set": {updated_key: change}})
	change = doc[updated_key]
end = time.time()

print 'Done', DOCS_NUM, 'reads/sets in', (end - start) * 1000, 'ms'
