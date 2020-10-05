<?php

require_once('/opt/kwynn/kwutils.php');

class tsc_collisions {
    
    const rfname = '/tmp/rd/r1';    
    
    public function __construct() {
	$this->load();
	$this->af10();
	$this->af20();
    }

    private function af20() {
	$cmma = [];
	$mina = PHP_INT_MAX;
	for($i=0; $i < $this->a10n; $i++) {
	   $e    = explode('-', $this->a10[$i]);
	   $cpun = $e[1];
	   $tick = intval($e[0]);
	   $ca  [$cpun][] = $tick;
	   if (!isset($cmma[$cpun]['max'])) {
		$cmma[$cpun]['max'] = -1;
		$cmma[$cpun]['min'] = PHP_INT_MAX;
		$cmma[$cpun]['cnt'] = 0;
	   }
	   
	   $cmma[$cpun]['cnt']++;
	   
	   if ($tick >  $cmma[$cpun]['max'])
	                $cmma[$cpun]['max'] = $tick;
	   

	   if ($tick <  $cmma[$cpun]['min'])
	                $cmma[$cpun]['min'] = $tick;
	   
	   if ($tick < $mina) $mina = $tick;
	   
	}
	
	foreach($cmma as $k => $av) {
	    $cmma[$k]['min'] -= $mina;
	    $cmma[$k]['max'] -= $mina;
	    $cmma[$k]['dif']  = number_format($cmma[$k]['max'] - $cmma[$k]['min']);
	    $cmma[$k]['min']  = number_format($cmma[$k]['min']);
	    $cmma[$k]['max']  = number_format($cmma[$k]['max']);	    
	    
	}
	
	return;
    }
    
    private function load() {
	$s = trim(file_get_contents(self::rfname));
	$this->a10  = explode("\n", $s);
	$this->a10n = count($this->a10);
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

if (didCLIcallMe(__FILE__)) new tsc_collisions();