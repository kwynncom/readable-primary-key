<?php

// $fs = ['time', 'nanotime', 'rdtscp', 'nanopk', 'uptime'];
$fs = ['nanotime', 'nanopk', 'uptime', 'rdtscp'];

foreach($fs as $f) var_dump($f());

$args = [
    0,
    NANOPK_U,
    NANOPK_TSC,
    NANOPK_PID | NANOPK_UNS
    ];

foreach($args as $a) var_dump(nanopk($a));