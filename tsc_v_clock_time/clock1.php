<?php

$esec =  3;
$iter = 10;
$per  = $esec / $iter;
$pow6 = pow(10,6);

for ($i=0; $i < $iter; $i++) {
    $t['u'] = microtime(1);
    $t['ta'] = rdtscp();
    $a[] = $t;
    usleep($per * $pow6);
}

$s = '';
for ($i=0; $i < $iter; $i++) {
    $tick = $a[$i]['ta'][0];
    if ($i === 0) {
	$mint = $tick;
	$minu = $a[$i]['u'];
    }
    $s .= $a[$i]['u'] - $minu;
    $s .= ', ';
    $s .= $tick - $mint;
    $s .= "\n";
}

file_put_contents('/tmp/cl', $s, FILE_APPEND);

exit(0);