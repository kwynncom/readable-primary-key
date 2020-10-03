<?php

require_once('dao.php');

$cmd = 'php ' . '-d extension=/tmp/phprd/modules/php_rdtscp.so ' . __DIR__  . '/' . 'run.php';
echo $cmd . "\n";
if (1) shell_exec($cmd);
echo "Run complete.  Analyzing.... \n";

$dao = new dao_collisions();
$res = $dao->ndn();
if ($res['dn'] !== $res['n']) {
    $agg  = $dao->group();
    var_dump($agg);
}

echo($res['n'] - $res['dn'] . " collisions out of $res[n] ; $res[dn] distinct\n");
