<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_ResEvaluate.class.php");

	$MongoColl = $MongoDB->resEvaluate;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>1))->limit(100);
		$i = 0;
		foreach($MongoCursor as $document) {
			$MongoIDObj = $document["_id"];
			$html = file_get_html($document["url"]);
			echo ($i + 1) . "- " . $document["id"] . " ";
			$VWSL_R = new VWSL_ResEvaluate($document["id"]);
			$VWSL_R->set_url($document["url"]);
			if(!$VWSL_R->set_title($html)) 		{ phase2_error("title is fail"); continue; }
			if(!$VWSL_R->set_body($html)) 		{ phase2_error("body is fail"); continue; }
			if(!$VWSL_R->set_updated($html)) 	{ phase2_error("updated is fail"); continue; }
			$VWSL_R->set_author_account($html);
			$VWSL_R->set_author_thumb($html);
			$VWSL_R->set_cover_image($html);
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_R->xml_mapping()));
			echo "status: " . $status . "\n";
			$i++;
		}
		if($i == 0) break;
	}

?>
