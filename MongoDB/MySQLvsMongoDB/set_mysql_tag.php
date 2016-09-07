<?php
	// 隨機設定 MySQL`s tag

	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);
	$categories = mysql_lst_categories($Mysql);

	$count = 0;
	while(1) {
		$threads = mysql_lst_dc($Mysql, 1000);
		if(count($threads) == 0) break;
		foreach($threads as $dc_id) {
			echo "- " . ($count + 1) . ": dc_id: " . $dc_id . "\n";
			$dc_tag = array();
			foreach($categories as $category) {
				$rand = rand(0, 5);
				$rows = array();
				if($rand > 0) {
					$rows = mysql_lst_tag($Mysql, $category, $rand);
					if(count($rows) > 0) {
						mysql_insert_dc_tag($Mysql, $dc_id, $rows);
						$dc_tag = array_merge($dc_tag, $rows);
					}
				}
			}
			if(count($dc_tag) > 0) {
				asort($dc_tag);
				mysql_update_dc_tag($Mysql, $dc_id, "[" . join($dc_tag, "],[") . "]");
			}
			$count++;
		}
		echo "sleep...\n";
		sleep(3);
	}
	$time_end = microtime(true);
	echo "count: " . $count . "\n";
	echo "time: " . ($time_end - $time_start) . "\n";
?>
