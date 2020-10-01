<?php

require_once('dao.php');

$now = time();

$cn = 12;
$ni = 1000;
$dao = new dao_collisions();
$pid = 1;

for     ($c=0; $c < $cn; $c++) {
    
    if ($pid !== 0) $pid = pcntl_fork();
    
    if ($pid !== 0) continue;
     
    for ($i=0; $i <  $ni; $i++) {
	$a[] = hrtime(1);
    }
    
    for ($i=0; $i <  $ni; $i++) {
	$dat = [];
	$dat['pid'] = getmypid();
	$dat['r']   = date('r', $now);
	$dat['h']   = $a[$i];
	$dao->put($dat);
    }    
    
    if ($pid === 0) break;
    
}