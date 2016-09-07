<?php
	include_once("data.php");
	include_once(".library.php");

	$m = new MongoClient();
	$db = $m->Yahoo;
	$collection1 = $db->thread;
	$collection2 = $db->thread2;

	$cursor = $collection1->find(array("stage"=>2))->limit(1000); 
	foreach($cursor as $document) {
		$id = $document["_id"];
		echo $id . "\n";
		$status = $collection2->insert($document);	
		echo $status . "\n";
	}

?>
