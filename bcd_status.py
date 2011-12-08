import serial
import datetime
import time

ser=serial.Serial('/dev/ttyUSB0',115200, rtscts=0)
ser.open()

ser.write("STS\r")
time.sleep(.25)
#print ser.inWaiting()
bcdreply = ser.read(ser.inWaiting())

bcdparts=bcdreply.split(',')
print bcdparts[4]
#ascii char 130 is a down arrow on the screen
print bcdparts[6].replace(chr(130),'v')
#for cr in bcdparts[6]:
#	print ord(cr)
print bcdparts[8]
print bcdparts[10]
print bcdparts[12]

ser.close()

