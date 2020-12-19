<?php

$iter = 100;

$b = hrtime(1);
for ($i=0; $i < $iter; $i++) {
     rdtscp();
    //nanopk();
     nanotime();
}
$e = hrtime(1);


$f =  ($e - $b) / $iter;
$s =  number_format(round($f));

echo $s . "\n";
