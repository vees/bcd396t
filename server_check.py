from Bcd396tSerial import *

bs=Bcd396tSerial()
bs.set_quick_by_id('5285','1222221220')
#bs.set_quick_by_id('5285','1111111110')
print bs.status_text()

