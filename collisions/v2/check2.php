<?php

require_once('/opt/kwynn/kwutils.php');

function evalRes() {
    $fn = '/tmp/rd/r1';
    $s = trim(file_get_contents($fn));
    $a = explode("\n", $s);
    $r = [];
    $cnt = count($a);
    for($i=0; $i < $cnt; $i++) {
	$v = $a[$i];
	kwas(!isset($r[$v]), 'collision!');
	$r[$v] = true;
    }
    
    kwas(count($r) === count($a), 'count mismatch!');
    echo 'count = ' . number_format($cnt) . "\n";
    
    echo 'no collisions' . "\n";
}

evalRes();