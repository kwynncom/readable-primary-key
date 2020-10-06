<?php

class stddev {
    public function __construct() {
	$this->sum = 0;
	$this->dat = [];
    }
    
    public function put($din) {
	$this->sum  += $din;
	$this->dat[] = $din;
    }
    
    public function get() {
	$n = count($this->dat);
	if ($n === 0) return null;
	$avg = $this->sum / $n;
	
	$acc = 0;
	foreach($this->dat as $v) $acc += pow($v - $avg, 2);
	$stdd = sqrt($acc / $n);
	return ['a' => $avg, 's' => $stdd, 'n' => $n];
    }
}