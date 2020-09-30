# readable-primary-key
Create human-readable primary keys for MongoDB

My original idea is further below, but this may be coming to a dead end.  

It takes roughly 1,200ns to run "new MongoDB\BSON\ObjectId();"  Or, more precisely, it takes that on average when you run it 1,000 times (see ooid.php).

I created /oidmods to test how long it takes to make an OOID human-readable, where 

5f73dd149dcaf543ad322721 becomes 
2020-0929-2119-16-03286817-9dcaf543ad

That's time down to seconds, a 24 bit sequence starting from a random number, and the process ID + machine ID UID.  
See https://www.php.net/manual/en/class.mongodb-bson-objectid.php

The answer is that it's something like 30X slower than the original object ID.  

Running my extension is only something like 30% faster than ObjectId().  And for the slightly longer runtime, one gets a true UUID.  The sequence 
gets me fractional seconds, after the fact, because I can calculate how many numbers in the sequence were created within a second.  

The point being that ObjectId() is efficient and effective and can be turned into something human-readable after the fact.  

Rather than considering a general use case, I'll start thinking about unique, human-readable keys on a collection by collection basis.

To go back a few weeks, one point that started all this is that I realized that my "getSeq()" function in kwutils.php is by no means foolproof.  It 
will fail under some circumances; there exists a race condition.  I have some reason to think that it does fail in production (on live systems) maybe 
once a month or so.  I was using sequences as something human readable, but I need to re-think that, too.

***
Written Sep 27 (or whatever GitHub says), before the above:

As of late September, 2020, this has already branched, although not "branched" in the version-control sense.  There was my original 
intention, and then there is my research into UUIDs.  The UUID stuff has become far more interesting to me for now.

The original intention was to eventually solve solve what I consider a problematic situation: by default, MongoDB document / object / unique IDs / 
primary keys are of the form "5f712c332958d5032b0f3f19".  I did not even know for sure until a few days ago that this format is meant to be 
more-or-less sortable by time.  In any event, it's an ugly format to look at.  

I also was unclear until a few days ago whether _id had to be unique throughout the database.  I am now almost certain that the answer is "no."  
The _id is the primary key for that "collection" / table just like RDBMSs.  The default _id should be a UUID across space, time, machines, etc., but 
it is really ugly to look at.  I have no immediate need for a UUID.

So the original goal was to first create IDs like 8:58pm and then modify them as needed for different days and months and years and machines and 
whatnot.  My "key.php" is the start of that.  My productivity would probably be better if I could see such timestamps at a glance.  And I'll 
probably get back to that goal.  

In the event that I'm creating many rows ("documents") at one time, if I do it as above, I can go down to microtime() / microseconds and add quite a 
few decimal places.  But with PHP there comes the end of the line.  

So I got diverted from my original intent and went back to studying UUIDs, which is funny because that's what I'm trying to get away from.  More 
specifically, I'm trying to figure out where the end of the line is.  Or, if I needed to create UUIDs, how would I do it differently?  Or yet more 
specifically, a UUID is a very specifically defined concept.  How would I do it such that it's unique for my purposes, even on a multi-core, 
multi-threaded machine that is creating rows as quickly as it can?

So in PHP there is microtime and then relatively recently hrtime() which is down to nanoseconds.  So that leads to a number of questions that I've 
been trying to answer for a day or two, as of 2020/09/27.  

My "hrtest.php" shows that on my very old processors, it takes a bit less than 700ns to capture one nanosecond.  That does not seem ideal, so 
I started looking into alternatives.  I found the x86_64 rdtscp instruction.  That gives both the clock tick of a given processor and its cpu number
(0 - 11 for 12 cores).  That is runnable from C/C++ and a number of pieces of code in the repo run it.  That takes 32 clock cycles or 16ns on my 
slow CPUs.  More specifically, when I run the command twice back to back I always get 32 cycles.  I have not yet compared C to PHP to see how long 
it takes to "capture" that number by putting it in an array in C.  

This brought up related questions.  Will hrtime collide given multiple processes?  The answer to that is yes.  I trivally demonstrate that in my 
currently-named code collisions/c1.php and check1.php.  With 12 processes, something like 4 out of 12,000 calls to hrtime() collided.  

I wrote my rdtscp PHP extension before I was sure of collisions. My assumption is that there is no way that clock cycle plus CPU number can collide on 
the same machine during the same boot session.  I'll test that soon.

In /ext is my very first PHP extension, which is to implement rdtscp.  I am almost sure I included everything needed.  I had to clean up a 
lot of build stuff.  
