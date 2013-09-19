import pickle
import pprint

output = open('/tmp/hi.pck', 'wb')
data1 = '1111111120'

# Pickle dictionary using protocol 0.
pickle.dump(data1, output)

output.close()

pkl_file = open('/tmp/hi.pck', 'rb')

data1 = pickle.load(pkl_file)
pprint.pprint(data1)

pkl_file.close()
