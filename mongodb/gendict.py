from random import randint, choice

def genstr(len = 6):
	result = ''
	for i in xrange(len):
		result += choice('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
	return result
	
	
def gendict(size = 8, nesting = 2):
	result = {}
	for i in xrange(size):
		result[genstr()] = gendict(size, nesting - 1) if nesting else genstr()
	return result
