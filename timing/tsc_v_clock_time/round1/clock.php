<?php

require_once('/opt/kwynn/kwutils.php');
require_once('stddev.php');

$esec =  1;
$iter = 20;
$per  = $esec / $iter;
$pow6 = pow(10,6);

for ($i=0; $i < $iter; $i++) {
    $t['ns'] = nanotime();
    $t['ta'] = rdtscp();
    usleep(900);
    $a[] = $t;
    usleep($per * $pow6);
}

$sdo = new stddev();

for ($i=0; $i < $iter; $i++) {
    $tick = $a[$i]['ta'][0];
    $nr   = $a[$i]['ns'];
    if ($i === 0) {
	$mint = $tick;
	$minu = $nr;
    }
    $eu = $nr   - $minu;
    $et = $tick - $mint;
   
    if (abs($eu - 0) > 0.0000001) {
	$sl = $et / $eu;
	$d  = $sl;
	$sdo->put($d);
	echo($d . "\n");
    }
}

    
var_dump($sdo->get());

exit(0);