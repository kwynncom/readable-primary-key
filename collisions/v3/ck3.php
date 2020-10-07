<?php

require_once('/opt/kwynn/kwutils.php');

class coll_ck3 { 

    const rfname = '/tmp/rd/tscr';    
    
    public function __construct() {
	$this->load();
	$this->af10();
    }
    
    private function load() {
	
	$fa = glob(self::rfname . '*');
	
	$s = '';
	
	$a = [];
	foreach($fa as $f) {
	    $s = trim(file_get_contents($f));
	    $ta = explode("\n", $s);
	    $a  = array_merge($a, $ta);
	}
	
	$this->a10 = $a;
	$this->a10n = count($a);
    }
    
    function af10() {

	$a = $this->a10;

	$r = [];
	for($i=0; $i < $this->a10n; $i++) {
	    $v = $a[$i];
	    kwas(!isset($r[$v]), 'collision!');
	    $r[$v] = true;
	}

	kwas(count($r) === count($a), 'count mismatch!');
	echo 'count = ' . number_format(count($a)) . "\n";

	echo 'OK - no collisions' . "\n";
    }
    
}
