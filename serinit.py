import serial
ser=serial.Serial('/dev/ttyUSB0',115200, rtscts=0)
ser.open()

