import serial
import datetime
import time
ser=serial.Serial('/dev/ttyUSB0',115200, rtscts=0)
ser.open()

# We just started, so clear out the buffer
if (ser.inWaiting()):
	ser.read(ser.inWaiting())

try:
	bcdreplyprev = ''
	lastchange = datetime.datetime.now()
	while 1:
		ser.write("GLG\r")
		bcdreply = ser.read(ser.inWaiting())
		#'GLG,80,NFM,0,0,Baltimore County,Fire Dispatch,Eastern Fire 3,1,0\r'
		if (bcdreply != bcdreplyprev): 
			now = datetime.datetime.now()
			now_ts = now.time().strftime("%H:%M:%S")
			print now_ts + '\tEND\t' + str((now-lastchange).seconds) + '\t' + bcdreplyprev.strip('\r') 
			print now_ts + '\tBEG\t0\t' + bcdreply.strip('\r')
			lastchange = now
			bcdreplyprev=bcdreply
		time.sleep(.5)
except KeyboardInterrupt:
	ser.close()

