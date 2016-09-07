<?php
	// 測試 mongodb`s match 主程式
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);
	$cursor = $MongoColl->find(
		array(  
			'$or' => array(
				array("tag" => (string) $t1),
				array("tag" => (string) $t2),
				array("tag" => (string) $t3),
				array("tag" => (string) $t4)
			),
			"tag"=>(string) $t5
		)
	)->limit(100)->sort(array("url"=>-1));
	foreach($cursor as $document) {
		echo $document["url"] . "\n";
		// asort($document["tag"]);
		// echo join(", ", $document["tag"]) . "\n";
	}
	echo sprintf("%01.6f", (microtime(true) - $time_start));
	echo "\n";
?>
