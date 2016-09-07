<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_Forum.class.php");

	$MongoColl = $MongoDB->expexch;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage" => 1))->limit(100);
		$i = 0;
		foreach($MongoCursor as $document) {
			$url = $document["url"];
			$MongoIDObj = $document["_id"];
			$html = file_get_html($url);
			echo ($i + 1) . "- " . $document["id"] . " ";
			if(preg_match("/(expexch)+/i", $document["type"])) { // forum
				if(!preg_match("/([0-9]+)-1.html$/i", $url, $matchs) || empty($matchs[1])) { phase2_error("URL Format is fail"); continue; }
				$VWSL_F = new VWSL_Forum($matchs[1]);
				$VWSL_F->set_url($url);
				if(preg_match("/expexch/i", $url)) {
					if(preg_match("/(2757828|2769836)+/", $url)) { phase2_error("This URL is block"); continue; }
				}
				if(!$VWSL_F->set_title($html)) 		{ phase2_error("title is fail"); continue; }
				if(!$VWSL_F->set_body($html)) 		{ phase2_error("body is fail"); continue; }
				if(!$VWSL_F->set_created($html)) 	{ phase2_error("created_time is fail"); continue; }
				$VWSL_F->set_author_account($html);
				$VWSL_F->set_author_alias($html);
				$VWSL_F->set_author_thumb($html);
				$VWSL_F->set_replies_views($html);
				$VWSL_F->set_image($html);
			}
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_F->xml_mapping()));
			echo "status: " . $status . "\n";
			$i++;
		}
	}
?>
