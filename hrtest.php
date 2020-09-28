<?php

$its = 30;
for ($i = 0; $i < $its; $i++) $a[] = hrtime(1);
for ($i = 1; $i < $its; $i++) echo $a[$i] - $a[$i - 1] . "\n";
