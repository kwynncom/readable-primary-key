<?php

$fs = ['time', 'nanotime', 'rdtscp', 'rdtscp_assoc', 'nanopk'];

foreach($fs as $f) var_dump($f());
