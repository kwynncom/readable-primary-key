<?php

$iter = 100;

$a = [];
$b = hrtime(1);
// for ($i=0; $i < $iter; $i++) $a[] = file_get_contents('/proc/uptime');
for ($i=0; $i < $iter; $i++) $a[] = shell_exec('uptime');
$e = hrtime(1);

$f =  ($e - $b) / $iter;
$s =  number_format(round($f));

echo $s . "\n";
