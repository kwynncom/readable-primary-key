<?php

$its = 30;
for ($i = 0; $i < $its; $i++) $a[] = hrtime(1);
for ($i = 1; $i < $its; $i++) echo $a[$i] - $a[$i - 1] . "\n";

// below result is meaningless because there isn't enough floating point precision
// for ($i = 0; $i < $its; $i++) $b[] = microtime(1);
// for ($i = 1; $i < $its; $i++) echo $b[$i] - $b[$i - 1] . "\n";
