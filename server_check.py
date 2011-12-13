from Bcd396tSerial import *
import urllib2
import urllib

bs=Bcd396tSerial()
#bs.set_quick_by_id('5285','1111111110')
statustext = bs.status_text()
print statustext

url = "https://vees.net/scanner/index.php"

request = urllib2.Request(url,
	urllib.urlencode({"status": statustext})
)
reply = urllib2.urlopen(request)

quickset = reply.read(10)
bs.set_quick_by_id('5285',quickset)

statustext = bs.status_text()
request = urllib2.Request(url,
        urllib.urlencode({"status": statustext})
)


