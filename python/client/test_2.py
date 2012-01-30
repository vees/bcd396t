# Showing how we can compare an entire system
# with a hex digest against two versions of the client
# or client/server.
#
# By Rob Carlson <rob@vees.net>
# This code contains Uniden proprietary and/or copyright control codes. Used with permission.

import pickle
import BcdSystem
import hashlib

b=BcdSystem.BcdSystem(5825)
print b.get_next_command()

