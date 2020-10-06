<?php

$cmd = 'php -d extension=' . '/tmp/ntime/modules/' . 'nanotime.so' . ' ' . __DIR__ . '/test2.php' ;
echo $cmd . "\n";
echo shell_exec($cmd);
