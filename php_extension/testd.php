<?php

$cmd = 'php -d extension=' . __DIR__ . '/' . 'rdtscp.so' . ' ' . __DIR__ . '/test.php' ;
echo $cmd . "\n";
echo shell_exec($cmd);