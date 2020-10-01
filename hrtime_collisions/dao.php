<?php

require_once('/opt/kwynn/kwutils.php');

class dao_collisions extends dao_generic {
    const db = 'collisions';
	function __construct() {
	    parent::__construct(self::db);
	    $this->ecoll    = $this->client->selectCollection(self::db, 'entries');
      }
      
      public function put($dat) { $this->ecoll->insertOne($dat); }
      
      public function ndn() {
	  $ret['n' ] = $this->ecoll->count();
	  $ret['dn'] = count($this->ecoll->distinct('h'));
	  return $ret;
	  
      }
      
      public function group() {
	  $agg = $this->ecoll->aggregate(
	    [
		[ 
		    '$group' => [
			'_id' => '$h',
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
}
/*
db.sales.aggregate(
  [
    // First Stage
    {
      $group :
        {
          _id : "$item",
          totalSaleAmount: { $sum: { $multiply: [ "$price", "$quantity" ] } }
        }
     },
     // Second Stage
     {
       $match: { "totalSaleAmount": { $gte: 100 } }
     }
   ]
 )*/