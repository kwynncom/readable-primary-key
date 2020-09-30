<?php

$o  = new MongoDB\BSON\ObjectId();
echo($s  = $o->__toString()   . "\n");
echo(($ts = $o->getTimestamp()) . "\n");
echo(($tss = date('Y-md-Hi-s', $ts)) . "\n");
echo(($tsss    = $tss  . '-' . sprintf('%08d', hexdec(substr($s, 18    )))) . "\n");
echo(($tsssmid = $tsss . '-' .			      substr($s,  8, 10)) . "\n");
