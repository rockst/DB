<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL.class.php");

	$MongoColl = $MongoDB->album;

	$MongoCursor = $MongoColl->find(array("stage"=> 3, "data.photos" => array('$gt' => 5)));
	$i = 0;
	foreach($MongoCursor as $document) {
		$image = array();
		for($j = 0; $j < 5; $j++) $image[$j] = $document["data"]["image"][$j];
		$status = update_mongodb($MongoColl, $document["_id"], array("data"=>array("image"=>$image)));
		echo ($i + 1) . " - " . $document["id"] . " - Update: " . $status . "\n";
		$i++;
	}

?>
