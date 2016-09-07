<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_Blog.class.php");

	$MongoColl = $MongoDB->blog;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>1, "msg"=>null))->limit(100);
		$cnt = 0;
		foreach($MongoCursor as $document) {
			$MongoIDObj = $document["_id"];
			$html = file_get_html($document["url"]);
			echo ($cnt + 1) . "- " . $document["id"] . " ";
			$VWSL_B = new VWSL_Blog($document["id"], $document["type"]);
			$VWSL_B->set_url($document["url"]);
			if(!$VWSL_B->set_title($html)) 					{ phase2_error("title is fail"); continue; }
			if(!$VWSL_B->set_body($html));
			if(!$VWSL_B->set_created($html)) 				{ phase2_error("created_time is fail"); continue; }
			if(!$VWSL_B->set_author_account($html))	{ phase2_error("author_account is fail"); continue; }
			if(!$VWSL_B->set_author_thumb($html))		{ phase2_error("author_thumb is fail"); continue; }
			if(!$VWSL_B->set_views($html))					{ phase2_error("views is fail"); continue; }
			if(!$VWSL_B->set_replies($html));
			if(!$VWSL_B->set_image($html));
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_B->xml_mapping()));
			echo "status: " . $status . "\n";
			$cnt++;
		}
		if($cnt == 0) break;
	}
?>
