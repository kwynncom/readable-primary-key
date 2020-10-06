<?php

require_once('stddev.php');

$esec =  3;
$iter = 100;
$per  = $esec / $iter;
$pow6 = pow(10,6);

for ($i=0; $i < $iter; $i++) {
    $t['us'] = microtime();
    $t['ta'] = rdtscp();
    $a[] = $t;
    usleep($per * $pow6);
}

$sdo = new stddev();

for ($i=0; $i < $iter; $i++) {
    $tick = $a[$i]['ta'][0];
    $ua = explode(" ", $a[$i]['us']);
    $ts = intval($ua[1]);
    $ur  = floatval($ua[0]);
    if ($i === 0) {
	$mint = $tick;
	$mini = $ts;
	$minu = $ur;
    }
    $eu = ($ts - $mini) + ($ur - $minu);
    $et = $tick - $mint;
   
    if (abs($eu - 0) > 0.0000001) {
	$sl = $et / $eu;
	$d  = intval(round($sl));
	$sdo->put($d);
	echo(number_format($d) . "\n");
    }
}

    
var_dump($sdo->get());

exit(0);