<?php

	// 隨機新增 MongoDB`s tag

	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);
	$categories = mysql_lst_categories($Mysql);

	$count = 0;
	while(1) {
		$threads = mongodb_lst_dc($MongoColl, 1000);
		if(count($threads) == 0) break;
		foreach($threads as $document) {
			echo "- " . ($count + 1) . ": id: " . (string) $document["_id"] . "\n";
			$dc_tag = array();
			foreach($categories as $category) {
				$rand = rand(0, 5);
				$rows = array();
				if($rand > 0) {
					$rows = mysql_lst_tag($Mysql, $category, $rand);
					if(count($rows) > 0) {
						$dc_tag = array_merge($dc_tag, $rows);
					}
				}
			}
			if(count($dc_tag) > 0) {
				//foreach($dc_tag as $i=>$tag) {
				//	$dc_tag[$i] = intval($tag);
				//}
				mongodb_update_dc($MongoColl, $document["_id"], array("tag"=>$dc_tag));
			}
			$count++;
		}
		echo "sleep...\n";
		sleep(1);
	}
	$time_end = microtime(true);
	echo "count: " . $count . "\n";
	echo "time: " . ($time_end - $time_start) . "\n";
?>
