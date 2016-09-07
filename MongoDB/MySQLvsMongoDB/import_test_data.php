<?php
	// 從 XML 備份檔中匯入資料用來測試
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);
	$count = 0;
	$handle = opendir(dirname(__FILE__) . "/data/");

	while(($entry = readdir($handle)) !== false) {
		if(preg_match("/.xml$/i", $entry)) {
			echo $entry . ": \n";
			$file = file_get_contents(dirname(__FILE__) . "/data/" . $entry, FILE_USE_INCLUDE_PATH);
			$xml  = simplexml_load_string($file, "SimpleXMLElement", LIBXML_NOCDATA);
			for($i = 0; $i < count($xml->addArticle); $i++) {
				$data = array();
				$data["url"] = preg_replace("/\?utm_source=.*/i", "", (string) $xml->addArticle[$i]->url);
				$data["title"] = (string) $xml->addArticle[$i]->title;
				$data["body"] = (string) $xml->addArticle[$i]->body;
				echo "- MySQL: " . ((mysql_insert_dc($Mysql, $data)) ? 1 : 0) . "\n";
				echo "- MongoDB: " . ((mongodb_insert_dc($MongoColl, $data)) ? 1 : 0) . "\n";
				$count++;
			}
		}
	}

	echo "count: " . $count . "\n";
	echo "time: " . (microtime(true) - $time_start) . "\n";
?>
