<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");

	$MongoColl1 = $MongoDB->album;
	$MongoColl2 = $MongoDB->photo;

	while(1) {

		$MongoCursor = $MongoColl1->find(array("get_photo"=>NULL, "data.photos" => array('$gte' => 0)), array("id"=>1, "url"=>1, "data.photos"=>1))->limit(100);

		$i = 0;
		foreach($MongoCursor as $document) {
			$MonObj = $document["_id"];
			$id 		= (int) $document["id"];
			$url 		= (string) $document["url"];
			$photos = (int) $document["data"]["photos"];
			echo "- " . $id . ": \n";
			$page_limit = (!($photos % 15)) ? ($photos / 15) : floor($photos / 15) + 1;
			$sum_photos = 0;	
			for($page_num = 1; $page_num <= $page_limit; $page_num++) {
				echo "-- page " . $page_num . "\n";
				$html = file_get_html($url . "?p=" . $page_num);
				$datas = array();
				$links = array();
				$cnt = 0;
				foreach($html->find("div#img-list a[href^='/album/photos/" . $id . "/']") as $document) {
					if(!isset($links[$document->href])) {
						if(preg_match("/^\/album\/photos\/" . $id . "\/([0-9]+)/i", $document->href, $matches)) {
							$datas[$cnt]["id"] = $matches[1];
							$datas[$cnt]["url"] = "http://verywed.com" . $document->href;
							$links[$document->href] = 1;
						}
						$cnt++;
					}
				}
				foreach($html->find("div#img-list a[href^='/album/photos/" . $id . "/'] img[src^='http://s.verywed.com']") as $cnt=>$document) {
					$datas[$cnt]["image"] = $document->src;
					$datas[$cnt]["type"]  = (preg_match("/_(sml|big)+/", $document->src)) ? "photo" : "video";
				}
				foreach($datas as $data) {
					echo "--- " . ($sum_photos + 1) . ": ";
					echo $data["id"] . ": ";
					echo insert_mongodb2($MongoColl2, array("id"=>(int)$data["id"], "url"=>(string)$data["url"], "type"=>(string)$data["type"], "stage"=>1))."\n";
					$sum_photos++;
				}
			}
			if($sum_photos != $photos) {
				echo "-- verify: photos:" . $photos . " - " . $sum_photos . "\n";
				sleep(1);
			}
			update_mongodb($MongoColl1, $MonObj, array("get_photo"=>1));
			$i++;
		}
		if($i == 0) break;
	}
?>
