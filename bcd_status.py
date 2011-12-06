import serial
import datetime
import time

ser=serial.Serial('/dev/ttyUSB0',115200, rtscts=0)
ser.open()

ser.write("STS\r")
time.sleep(.25)
print ser.inWaiting()
print ser.read(ser.inWaiting())

ser.close()

