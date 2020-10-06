<?php

$iter = 500;

$nano = $tick = [];
for ($i=0; $i < $iter; $i++) {
    $nano[] = nanotime();
    $tick[] = rdtscp();
    
}
for ($i=0; $i < $iter; $i++) {
    if ($i === 0) {
	$minns = $nano[$i];
	$minti = $tick[$i][0];
    }
    
    $ns = $nano[$i]    - $minns;
    $ti = $tick[$i][0] - $minti;
    
    if (abs($ns - 0) > 0.0000001) {
	$sl = $ti / $ns;
	echo $sl . "\n";
    }
}


