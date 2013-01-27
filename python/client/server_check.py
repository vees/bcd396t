from Bcd396tSerial import *
import urllib2
import urllib
import pickle
#import pprint
import psutil
import os

os.environ['http_proxy']=''

quickset_filename = '/tmp/quickset.pck'

try:
	pkl_file = open(quickset_filename, 'rb')
	last_quickset = pickle.load(pkl_file)
	pkl_file.close()
except:
	last_quickset = None

bs=Bcd396tSerial()
#bs.set_quick_by_id('5285','1111111110')
statustext = bs.status_text()
print statustext

def f7(seq):
	seen = set()
	seen_add = seen.add
	return [ x for x in seq if x not in seen and not seen_add(x)]

PROCNAME = "icecast2"

for proc in psutil.process_iter():
	if proc.name == PROCNAME:
		out = []
		for conn in proc.get_connections():
			if len(conn.remote_address) > 0:
				out.append(conn.remote_address[0])

iplist = ",".join(f7(out))

url = "https://vees.net/scanner/service2.php"

request = urllib2.Request(url,
	urllib.urlencode({"status": statustext, "iponline": iplist})
)
reply = urllib2.urlopen(request)

quickset = reply.read(10)
if (last_quickset != None and last_quickset!= quickset):
	bs.set_quick_by_id('1033',quickset)
	print "Changed value"
else:
	print "Unchanged value"

output = open(quickset_filename, 'wb')
pickle.dump(quickset, output)
output.close()

statustext = bs.status_text()
request = urllib2.Request(url,
        urllib.urlencode({"status": statustext})
)

