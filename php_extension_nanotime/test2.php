<?php

for($i=0; $i < 1; $i++) {
    echo(nanotime() . "\n");
    echo(time() . "\n");
}

$ns = nanotime();

if (is_integer($ns)) echo("integer type confirmed\n");

exit(0);