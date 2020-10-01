<?php

require_once('dao.php');

$dao = new dao_collisions();
$res = $dao->ndn();
var_dump($res);
$agg  = $dao->group();
var_dump($agg);

// db.getCollection('entries').find({'h' : 13600104187767})