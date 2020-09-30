<?php


function get_oidmod1() {
    $o   = new MongoDB\BSON\ObjectId();
    $s   = $o->__toString();
    $ts  = $o->getTimestamp();
    $tss = date('Y-md-Hi-s', $ts);
    $fs  = $tss . '-' . sprintf('%08d', hexdec(substr($s  , 18    ))) . '-' . 
					       substr($s  ,  8, 10);
    
    return $fs;
}

$goton = 1000;

$b = hrtime(1);
for ($i=0; $i < $goton; $i++) $a[] = get_oidmod1();
$e = hrtime(1);
$el = number_format(($e - $b) / $goton);

echo $el;