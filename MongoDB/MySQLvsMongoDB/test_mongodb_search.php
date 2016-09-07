<?php
	// 測試 MongoDB`s Search 主程式
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");
	$time_start = microtime(true);
	$regex = new MongoRegex("/" . $tag_for_search . "/i");
	$cursor = $MongoColl->find(
		array(  
			'$or' => array(
				array("title" => $regex),
				array("body" => $regex)
			)
		)
	)->limit(100)->sort(array("url"=>-1));
	foreach($cursor as $document) {
		echo $document["url"] . "\n";
	}
	echo sprintf("%01.6f", (microtime(true) - $time_start));
	echo "\n";
?>
