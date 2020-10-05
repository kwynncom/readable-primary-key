<?php

require_once('/opt/kwynn/kwutils.php');

function evalRes($fn) {
    $s = file_get_contents($fn);
    $a = explode("\n", $s);
    $r = [];
    for($i=0; $i < count($a); $i++) {
	$v = $a[$i];
	kwas(!isset($r[$v]), 'collision!');
	$r[$v] = true;
	
    }
    
    
}