<?php

require_once('/opt/kwynn/kwutils.php');

class ufnid extends dao_kw2 {
  
    const dbname = 'metakw';
    const datv   = 1;

    function __construct() {
	parent::__construct();
	$this->icoll  = $this->client->selectCollection(self::dbname, 'ids');
    }
    
    public static function get($db, $coll) {
	static $o = false;
	if (!$o) $o = new self();
	return $o->getI($db, $coll);
    }
    
    private function getI($db, $co) {
	
	$i=0;
	$dat = [];
	$dat['db']  = $db;
	$dat['co']  = $co;
	$dat['v' ]  = self::datv;
	
	do {
	try {
	    self::gen($i, $dat);
	    $this->icoll->insertOne($dat);
	    return $dat['_id'];
	} catch (Exception $ex) {
	    $ignore = 0;
	}
	} while ($i++ < 10);

	kwas(0, 'no _id generated');
    }
    
    private static function gen($i, &$dat) {
	
	static $a = false;
	
	if (!$a) $a = ['g:ia', 'g:i:sa'];
	
	kwas(isset($a[$i]), 'ran out of _id options'); 

	$tsu = intval(microtime(1) * 1000000);
	$dat['h'  ] = hrtime(1);
	$dat['genopt'] = $i;
	$theid      = date($a[$i]);	
	$dat['_id'] = $theid;
	$dat['tsu'] = $tsu;
    }
    
}

class dao_kw2  {
    protected $client;
    public function __construct() { $this->client = new monclikw2(); }
}

class monclikw2 extends MongoDB\Client {
    public function __construct() {
	parent::__construct('mongodb://127.0.0.1/', [], ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']]);
    }
    public function selectCollection     ($db, $coll, array $optionsINGORED_see_below = []) {
	return new collkw2($this->getManager(), $db, $coll, ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']]); 
    }
}

class collkw2 extends MongoDB\Collection {
    
    public function insertOne($dat = [], $opts = []) {
	if (!isset($dat['_id']))
	           $dat['_id'] = ufnid::get($this->getDatabaseName(), $this->getCollectionName());
	return parent::insertOne($dat, $opts);
    }
    
    public function upsert($q, $set) { return $this->updateOne($q, ['$set' => $set], ['upsert' => true]);  }
    
}

class dao_uuid_test1 extends dao_kw2 {
    const  dbname = 'metaTest1';
    function __construct() {
	parent::__construct();
	$this->t1coll  = $this->client->selectCollection(self::dbname, 'test1');
	$this->t1();
    }
    
    public function t1() { 
	for ($i=0; $i < 3; $i++) $this->t1coll->insertOne(); 
	
    }
} 

new dao_uuid_test1(); // db.getCollection('test1').find({}).sort({'_id' : -1});
