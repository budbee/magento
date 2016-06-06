<?php
  require_once('vendor/autoload.php');

  $apiKey = '8c218147-4558-435e-ace9-37f148dcb9b9';
  $apiSecret = '4bb824fd-3d15-439c-9357-72afb00e4026524ac4c1-459a-4ce0-a2b5-df0fe4399a0f';
  $postalCode = null;

  $client = new \Budbee\Client($apiKey, $apiSecret, Budbee\Client::$SANDBOX);

  /*
  *	postal code verfication
  */

  $postalCodesAPI = new \Budbee\PostalcodesApi($client);
  try {
    $possibleCollectionPoints = $postalCodesAPI->checkPostalCode('11453');
    if (count ($possibleCollectionPoints)){
    	foreach ($possibleCollectionPoints as $key => $value) {
    		//var_dump($value);
    		// save $value->address->postalCode somewehere 
    	}

    	$postalCode = $value->address->postalCode;
    }
	} catch (\Budbee\Exception\BudbeeException $e) {
	    die('Budbee does not deliver to specified Postal Code');
	}

	/****** end postal code verfication ***/
  
  $intervalAPI = new \Budbee\IntervalApi($client);
  try {
	    
// 2 ?? setting in backend ?!
	    $intervalResponse = $intervalAPI->getIntervals($postalCode, 2);

	    $firstInterval = $intervalResponse[0];
	    foreach ($intervalResponse as $key => $interval) {
	    	echo "Collection starts between " . 
	    	 ($interval->collection->start->format('Y-m-d H:i:s')) .  " and " . 
	    	 ($interval->collection->stop->format('Y-m-d H:i:s'));
	    	 echo "<br>";

	    	 echo "Delivery starts between " . 
	    	 ($interval->delivery->start->format('Y-m-d H:i:s')) . " and " . 
	    	 ($interval->delivery->stop->format('Y-m-d H:i:s'));
	    	  echo "<br>";
	    	
	    	 /*** to be checked 
	    	 $interval = new \Budbee\Model\OrderInterval($firstInterval->collection, $firstInterval->delivery);
				$collectionPointId = $firstInterval->collectionPointId;
	    	 echo $collectionPointId = $interval->collectionPointId;
	    	 echo "<hr>";***/


	    }

	    //var_dump($firstInterval);
	    die;
		$interval = new \Budbee\Model\OrderInterval($firstInterval->collection, $firstInterval->delivery);
		//$collectionPointId = $firstInterval->collectionPointId;

		echo 'Budbee can deliver between: ' + $interval->delivery->start + ' and ' + $interval->delivery->stop;
	    
	} catch (\Budbee\Exception\BudbeeException $e) {
	    die('No upcoming delivery intervals');
	}

  //$orderAPI = new \Budbee\OrderApi($client);

  
?>