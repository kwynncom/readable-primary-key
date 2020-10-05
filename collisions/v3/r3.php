<?php

require_once('../v2/check2.php');

function runAllCores() {
    
    $cn = 12; // number of cpus
    $ni = 200000; // number of iterations
    
    $pid = 1; // any truthy value just because the logic will work that way
    
    $cpids = [];

    
    for ($c=0; $c < $cn; $c++) {
	
	if ($pid !== 0) { 
	    $pid = pcntl_fork(); 
	    if ($pid !== 0) {
		$cpids[] = $pid;
		continue; 
	    }
	}

	for ($i=0; $i <  $ni; $i++) $a[] = rdtscp();

	$rf = tsc_collisions::rfname . '_' . getmypid();
	$fh = fopen($rf,'w');
	
	$t = '';
	for ($i=0; $i <  $ni; $i++) {
	    $tick = $a[$i][0];
	    $cpun = $a[$i][1];
	    $t .= sprintf('%015d-%02d' . "\n", $tick, $cpun);
	}
	
	fwrite($fh, $t);
	
	if ($pid === 0) exit(0);
    }
    
    for($i=0; $i < $cn; $i++) pcntl_waitpid($cpids[$i], $status);

}

runAllCores();
