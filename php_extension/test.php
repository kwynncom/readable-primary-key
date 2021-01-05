<?php

$fs = ['time', 'nanotime', 'nanopk', 'uptime', 'rdtscp', 'nanopkavg'];
foreach($fs as $f) var_dump($f());
var_dump(nanopk(NANOPK_VERSION));

if (0) {
$args = [
    0,
    NANOPK_U,
    NANOPK_TSC,
    NANOPK_PID | NANOPK_UNS,
    0xffffff
    ];

foreach($args as $a) var_dump(nanopk($a));
}