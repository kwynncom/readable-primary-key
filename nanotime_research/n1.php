<?php

$a = [];
for ($i=0; $i < 30; $i++) $a[] = nanotime();
for ($i=1; $i < 30; $i++) {
    echo(($a[$i] - $a[$i-1]) . "\n");
}