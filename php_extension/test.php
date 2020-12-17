<?php

$fs = ['time', 'nanotime', 'rdtscp', 'rdtscp_assoc', 'nanopk', 'uptime'];

foreach($fs as $f) var_dump($f());
