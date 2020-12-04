<?php

$its = 200000;
$j = 0;
$b = nanotime();
for ($i = 0; $i < $its; $i++) {
    // new MongoDB\BSON\ObjectId();
   // rdtscp_assoc();
   // $j++;
   // rdtscp();
  // $r = rdtscp();   $s = $r[0] . '-' . $r[1] . '-boot1-mid1';
    // nanotime();
  //  nanopk();
    // time();
}
$e = nanotime();

$d = $e - $b;

$r = $d / $its;

echo $r;
