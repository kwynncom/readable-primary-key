Regarding tsc_v_oid_v_mutex.php:

I wanted to see how long it would take to do a sequence the "naive" way using shared memory and semaphores.  The answer is that it's 
100 times slower than rdtscp or a BSON Object ID.

A few notes:

64 bytes is too low for the shared memory segment, or, at least, it is under some circumstances.  If it's too low, you'll get a 
warning message and the result of shm_put_var will be false.  96 bytes works.  10,000 bytes also work.

ftok() is a fun little function to generate a unique shared memory ID, but I found that 1 works, too.

As for the BSON generator:

https://github.com/mongodb/libbson/blob/master/src/bson/bson-context.c  has _bson_context_get_oid_seq32_threadsafe - That's the C library that PHP uses

The PHP constructor is at https://github.com/mongodb/mongo-php-driver/blob/master/src/BSON/ObjectId.c

This is the sequence adder itself: https://github.com/mongodb/libbson/blob/master/src/bson/bson-atomic.c

The threadsafe version of course uses a semaphore / mutex mechanism.  The non-ts version only has to do blah++ because process ID is part of the 
UUID.

This brings up the question of just using process id and a sequence for a primary key.  The question is how often will your code be run on a 
threading web server or threading anything else?  I don't have a quick answer to that, although I suppose I should dig at the question.

Oh well, in any event, it was a fun few hours to get the shared memory working and test all that out.
