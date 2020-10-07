<?php

require_once('ck3.php');

function runAllCores() {
    
    $cn = 12; // number of cpus
    $ni = pow(10,1) * 8; // number of iterations
    $rfp = coll_ck3::rfname;
    
    $fa = glob($rfp . '*');
    foreach($fa as $f) unlink($f);
    
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

	$b = hrtime(1);
	$rf = $rfp . '_' . getmypid();
	
	$t = '';
	for ($i=0; $i <  $ni; $i++) {
	    $tick = $a[$i][0];
	    $cpun = $a[$i][1];
	    $t .= sprintf('%015d-%02d' . "\n", $tick, $cpun);
	}
	
	file_put_contents($rf, $t);
	$e = hrtime(1);
	
	echo 'write time: ' . number_format($e - $b) . "\n";

	
	if ($pid === 0) exit(0);
    }
    
    for($i=0; $i < $cn; $i++) pcntl_waitpid($cpids[$i], $status);
}

runAllCores();
new coll_ck3();