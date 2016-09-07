<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL.class.php");

	$MongoColl = $MongoDB->essence;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>2))->limit(1000);
		$rows = array();
		$i = 0;
		foreach($MongoCursor as $document) {
			$status = update_mongodb($MongoColl, $document["_id"], array("stage" => 3));
			echo ($i + 1) . " - " . $document["id"] . " - Update: " . $status . "\n";
			array_push($rows, $document["data"]);
			$i++;
		}
		if(count($rows) > 0) {
			echo "Result: " . VWSL::buildXML("expexch_essence_all", $rows) . "\n";
		}
		echo "Sleep ...\n";
		sleep(3);
	}
?>
