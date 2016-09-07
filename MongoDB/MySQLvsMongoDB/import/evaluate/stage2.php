<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_Evaluate.class.php");

	$MongoColl = $MongoDB->evaluate;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>1, "msg"=>null))->limit(100);
		$i = 0;
		foreach($MongoCursor as $document) {
			$MongoIDObj = $document["_id"];
			$html = file_get_html($document["url"]);
			echo ($i + 1) . "- " . $document["id"] . " ";
			$VWSL_E = new VWSL_Evaluate($document["id"]);
			$VWSL_E->set_url($document["url"]);
			$VWSL_E->set_cover_image($html);
			if(!$VWSL_E->set_title($html)) 					{ phase2_error("title is fail"); continue; }
			if(!$VWSL_E->set_body($html)) 					{ phase2_error("body is fail"); continue; }
			if(!$VWSL_E->set_created($html)) 				{ phase2_error("created is fail"); continue; }
			if(!$VWSL_E->set_author_account($html)) { phase2_error("author_account is fail"); continue; }
			if(!$VWSL_E->set_author_thumb($html)) 	{ phase2_error("author_thumb is fail"); continue; }
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_E->xml_mapping()));
			echo "status: " . $status . "\n";
			$i++;
		}
		if($i == 0) break;
	}
?>
