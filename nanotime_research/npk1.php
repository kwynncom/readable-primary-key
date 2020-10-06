<?php

echo time() . "\n";

$iter = 10;

$a = [];
for ($i=0; $i < $iter; $i++) $a[] = nanopk();
for ($i=1; $i < $iter; $i++) {
    $a1 = $a[$i  ];
    $a0 = $a[$i-1];
    
    $s  = '';
    $s .= $a0[0] . ' ';
    
    $dns = $a1[0] - $a0[0];
    $s  .= $dns;
    $s  .= ' ';
    $dti = $a1[1] - $a0[1];
    $s  .= $dti;
    $s  .= ' ';
    $r   = $dti / $dns;
    $s  .= $r;
    $s  .= ' ';
    $s  .= $a1[2] . ' ' . $a0[2];
    
    $s .= "\n";
    echo $s;
}