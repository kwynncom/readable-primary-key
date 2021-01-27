This repo should go dormant.  It became a mess.  I have moved stuff:

https://github.com/kwynncom/nano-php-extension

That is the new home of the extension, as of 2021/01/26

***************
I think I mentioned it elsewhere, but the original intent of this repository is starting to get off the ground.  
I have started creating keys (_id) like this: 1-01-22-23:44:59-2343-2021

Where 1- is the short form of 2021 and I append 2021 lest I have problems in 10 years.  01-22 is month and day, then 
timestamp, then a sequence.  Even if I re-write the sequence table one second after the last ID is created, I'm fine because 
the sequence will be reused during a later second.  

These IDs are being created from

https://github.com/kwynncom/kwynn-php-general-utils/blob/6eecc847951fdd5aea5c9907b85dc7cfa0f69c7b/mongodb2.php

Specifically, I tend to use the 'idoas' option - ID only as string.

********

2020/12/19 restatement of the project

The main result of this project is the opposite of human-readable keys.  One day I will move code to another project.

The human-readable part did happen eventually.  I mention it further below.

The main result of what this became is in my /php_extension folder.

The extension gives one tools to create primary keys with many cores / threads without mutual exclusion / semaphores.  

I created a PHP function rdtscp that gives PHP access to the rdtscp x86 / x64 instruction--the time stamp counter (TSC) plus 
the processor ID (PID).  The TSC is the basic clock tick such as 1.33 GHz.  The processor ID is which processor / core / thread the 
function read.  The rdtscp instruction takes 32 cycles (tics) to run (based on my experiments in the project), so the TSC and the PID 
give one a unique key for a given boot session.  Upon reboot, the clock starts over.

The nanopk() function by default gives TSC, PID, and "wall clock" time down to the nanosecond, as an associative array.  Thus, those 3 fields are unique 
to that machine (because the "wall clock" is much more precise than a boot session).

nanopk(fields) will optionally give or exclude those fields and others based on the NANOPK_ constants I define.  Use the constants as a bitmask.

The fields one can get are:


21507323850116      - NANOPK_TSC - timestamp counter (number of ticks since boot)
11                  - NANOPK_PID - The processor / core / thread that the ticks came from 
1608420495          - NANOPK_U   (Unix epoch in seconds)
1608420495816608010 - NANOPK_UNS (Unix epoch in nanoseconds)
          816608010 - NANOPK_UNSOI - Only the nanoseconds, as an integer, as opposed to the seconds
        0.81660801  - NANOPK_UNSOF - Only the nanoseconds as a float
0.0.10              - NANOPK_VERSION - version of the extension

Or:

array(7) {
  ["tsc"]=>
  int(21507323850116)
  ["pid"]=>
  int(8)
  ["Uns"]=>
  int(1608420495816608010)
  ["U"]=>
  int(1608420495)
  ["Unsoi"]=>
  int(816608010)
  ["Unsof"]=>
  float(0.81660801)
  ["nanopk_v"]=>
  string(5) "0.0.9"
}

nanotime() gives only UNS or Unix nanoseconds, such as 1608420495816608010

uptime() is very similar to the Linux command of the same name.  It returns:

array(4) {
  ["uptime"]=>
  int(60398)
  ["Ubest"]=>
  int(1608360097)
  ["Ubmin"]=>
  int(1608360094)
  ["Ubmax"]=>
  int(1608360100)
}

uptime is seconds since boot.  This is the direct / only result of the underlying C function.  
Ubest is Unix timestamp (epoch) boot time estimate.  It's an estimate because the precision is only down to the second.  If you call this or 
run $ uptime -s   You will get slighly different results.

Thus Ubmin and Ubmax are the estimate plus or minus 3.  It seems that one could never be more than 3 off. (I'm not sure you can be 2 off, but it seems 
somewhat possible.)

I included uptime in case one wanted to use this to define which boot session.  I'm now close to done with a boot service to do this more systematically.  
For a specific version of this, see:

https://github.com/kwynncom/code-fragments/commit/046cd1e28115ff538af8e1e4b84d24be305bf412

Similarly, machine ID is a service to very specifically identify the machine:

https://github.com/kwynncom/code-fragments/commit/41c736063de0a0a1b285ae6ff786dd3ba54ee9d9

I am giving specific commits because I will move that code eventually.

*******
**********
Human-readable

The human-readable keys finally happend in my "mongodb2" file:

https://github.com/kwynncom/kwynn-php-general-utils/blob/92ce6f735d12403e47ebc54936dbcea914571e49/mongodb2.php

The MongoDB _id looks like this: 0-12-17-00:17:44-13-2020

I'll write up the (at least some) details in that project's README.

*********************
****************
2020/11/16 update - see timing/tsc_v_oid_v_mutex.php and timing/README.md

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
