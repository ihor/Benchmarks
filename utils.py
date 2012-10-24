from random import randint, choice
import string

def genstr(len=6, chars=string.ascii_uppercase + string.digits):
    return ''.join(choice(chars) for x in xrange(len))

def gendict(size=8, nesting=2):
    return {genstr(): gendict(size, nesting - 1) if nesting else genstr() for i in xrange(size)}
