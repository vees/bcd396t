import serial
import datetime
import time

class Bcd396tSerial:
	def __init__(self):
		self.open_serial()

	def open_serial(self):
		self.ser=serial.Serial('/dev/ttyUSB1',115200, rtscts=0)
		if (self.ser.isOpen() == False):
			self.ser.open()
		
		# We just started, so clear out the buffer
		if (self.ser.inWaiting()):
			self.ser.read(self.ser.inWaiting())

	def close_serial(self):
		print "Cleaning up to end"
		self.ser.close()

	def set_quick_by_id(self, quick_id, qgl_string):
		command_list = [
			[ 'PRG', 'PRG,OK' ],
			[ 'QGL,'+quick_id+','+qgl_string, 'QGL,'+qgl_string ],
			[ 'EPG', 'EPG,OK' ],
			[ 'KEY,S,P', 'KEY,OK' ],
		]
		for command in command_list:
			self.send_serial_command(command[0],command[1])

	def get_quick_by_id(self, quick_id):
		command_list = [
			[ 'PRG', 'PRG,OK' ],
			[ 'QGL,'+quick_id+','+qgl_string, 'QGL,'+qgl_string ],
			[ 'EPG', 'EPG,OK' ],
			[ 'KEY,S,P', 'KEY,OK' ],
		]
		for command in command_list:
			self.send_serial_command(command[0],command[1])
		

	def send_serial_command(self, command, expected):
		print command
		self.ser.write(command + '\r')
		time.sleep(.2)
		bcdreply = self.ser.read(self.ser.inWaiting())
		print bcdreply

	def status_text(self):
		self.ser.write("STS\r")
		time.sleep(.2)
		bcdreply = self.ser.read(self.ser.inWaiting())
		bcdparts=bcdreply.split(',')
		screenprint = ''
		for parts in [4,6,8,10,12]:
			#ascii char 130 is a down arrow on the screen
			screenprint += bcdparts[parts].replace(chr(130),'v') + '\n'
		return screenprint

