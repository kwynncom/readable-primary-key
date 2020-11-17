<?php

function runAllCores() {
    
    $cn = 12;
    $ni = pow(10,3) * 2;
    $pid = 1;
    $cpids = [];
    
    $shmkey = 1; // ftok(__FILE__, chr( 4 ) );
    $data =  shm_attach($shmkey, 64);
    
    $i = 0;
    $res = shm_put_var($data, 1, $i); unset($i);
      
    for ($c=0; $c < $cn; $c++) {
	
	if ($pid !== 0 && 1) { 
	    $pid = pcntl_fork(); 
	    if ($pid !== 0) {
		$cpids[] = $pid;
		continue; 
	    }
	}

	$semr = sem_get($shmkey);
	
	
	$b = microtime(1);
	for ($i=0; $i <  $ni; $i++) {
	    if (1) {
		sem_acquire($semr);
		$shmi = shm_get_var($data, 1);
		shm_put_var($data, 1, ++$shmi);
		sem_release($semr);
	    } else if (0) rdtscp();
	    else new MongoDB\BSON\ObjectId();
		
	}
	$e = microtime(1);
	
	echo(sprintf('%0.2f', $e - $b) . "\n");

	if ($pid === 0) exit(0);
    }
    
    for($i=0; $i < $cn; $i++) pcntl_waitpid($cpids[$i], $status);
    
    $shmip = shm_get_var($data, 1);
    echo('$i === ' . $shmip . "\n");
}

runAllCores();
