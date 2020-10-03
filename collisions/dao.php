<?php

require_once('/opt/kwynn/kwutils.php');

class dao_collisions extends dao_generic {
    const db = 'collisions';
    const uqTestField = 'tuv';
    
	function __construct() {
	    parent::__construct(self::db);
	    $this->ecoll    = $this->client->selectCollection(self::db, 'entries');
      }
      
      public function put($dat) { $this->ecoll->insertOne($dat); }
      
      public function ndn() {
	  $ret['n' ] = $this->ecoll->count();
	  
	  $one = $this->ecoll->findOne();
	  
	  if ($one['func'] === 'hrtime') $ret['dn'] = count($this->ecoll->distinct(self::uqTestField));
	  else $ret['dn'] = $ret['n']; // $this->checkRDUq($ret['n']);
	  return $ret;
	  
      }
      
      private function checkRDUq($n) {
	  $res = $this->ecoll->createIndex(['tick' => 1, 'cpun' => 1], ['unique' => true]);
	  if ($res === 'tick_1_cpun_1') return $n;
	  else return $n - 1;
      }
      
      
      public function group() {
	$agg = $this->ecoll->aggregate(
	    [
		[ 
		    '$group' => [
			'_id' => '$' . self::uqTestField,
			'tot' => ['$sum' => 1]
		    ]
		],
		[
		    '$match' => ['tot' => ['$gt' => 1]]
		]
	    ]
	);
	  
	  return $agg->toArray();
      }
      public function truncate() {
	  $this->ecoll->deleteMany([]);
      }
      
}
