<?php

require_once('dao.php');

function runAllCores() {

    $dao = new dao_collisions();
    $dao->truncate();
    unset($dao);
    
    $now = time();
    $cn = 12;
    $ni = 40000;
    if (0) $func = 'hrtime';
    else   $func = 'rdtscp';
    
    $pid = 1; // any truthy value just because the logic works that way
    
    for     ($c=0; $c < $cn; $c++) {

	if ($pid !== 0) $pid = pcntl_fork();

	if ($pid !== 0) continue;
	
	$dao = new dao_collisions();

	
	for ($i=0; $i <  $ni; $i++) {
	    $a[] = $func(1);
	}

	for ($i=0; $i <  $ni; $i++) {
	    $dat = [];
	    $dat['pid'] = getmypid();
	    $dat['r']   = date('r', $now);
	    
	    if ($func === 'hrtime') 
		$dat['tuv'] = $a[$i];
	    else {
		$dat['tuv']  = $a[$i][0] . '-' . $a[$i][1];
		$dat['tick'] = $a[$i][0];
		$dat['cpun'] = $a[$i][1];
	    }
	    
	    
	    $dat['func'] = $func;
	    $dao->put($dat);
	}    

	if ($pid === 0) break;

    }
}

runAllCores();
