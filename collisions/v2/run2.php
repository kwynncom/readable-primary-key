<?php

require_once('check2.php');

function runAllCores() {
    
    $cn = 12; // number of cpus
    $ni = 10000; // number of iterations
    
    $pid = 1; // any truthy value just because the logic will work that way
    
    $cpids = [];
    
    $rf = tsc_collisions::rfname; // consider using a RAM disk
    if (file_exists($rf)) unlink($rf);
    
    for ($c=0; $c < $cn; $c++) {
	
	if ($pid !== 0) { 
	    $pid = pcntl_fork(); 
	    if ($pid !== 0) {
		$cpids[] = $pid;
		continue; 
	    }
	}
	
	$cpid = getmypid();

	for ($i=0; $i <  $ni; $i++) $a[] = rdtscp();

	for ($i=0; $i <  $ni; $i++) {
	    $tick = $a[$i][0];
	    $cpun = $a[$i][1];
	    // $t = sprintf('%015d-%02d-%05d' . "\n", $tick, $cpun, $cpid);
	    $t = sprintf('%015d-%02d' . "\n", $tick, $cpun);
	    
	    // $t = sprintf('%015d' . "\n", $tick);
	    
	    file_put_contents($rf, $t, FILE_APPEND);
	}    

	if ($pid === 0) exit(0);
    }
    
    for($i=0; $i < $cn; $i++) pcntl_waitpid($cpids[$i], $status);
}

runAllCores();

// 12 cores X 600,000 = 7.2M passes and runs in a few seconds for run and check total