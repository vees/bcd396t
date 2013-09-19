import psutil

PROCNAME = "icecast2"

for proc in psutil.process_iter():
	if proc.name == PROCNAME:
		for connection in proc.get_connections():
			if len(connection.remote_address) > 0:
				print connection.remote_address[0]
