# Showing how we can compare an entire system
# with a hex digest against two versions of the client
# or client/server.
#
# By Rob Carlson <rob@vees.net>
# This code contains Uniden proprietary and/or copyright control codes. Used with permission.

import pickle
import BcdSystem
import hashlib

b=BcdSystem.BcdSystemInfo()
b.from_bcd(5825,'SIN,M82S,Baltimore County,1,2,0,2,,AUTO,0,AUTO,8,-1,5166,5287,5287,4')
print b.to_s()
print b.bcd_data[2]
print hashlib.md5(pickle.dumps(b)).hexdigest()


