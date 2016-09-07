<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_Photo.class.php");

	$MongoColl = $MongoDB->photo;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>1))->limit(100);
		$cnt = 0;
		foreach($MongoCursor as $document) {
			$MongoIDObj = $document["_id"];
			$html = file_get_html($document["url"]);
			echo ($cnt + 1) . "- " . $document["id"] . " ";
			$VWSL_P = new VWSL_Photo($document["id"], $document["type"]);
			$VWSL_P->set_url($document["url"]);
			if(!$VWSL_P->set_cover_image($html))		{ phase2_error("cover_image is fail"); continue; }
			if(!$VWSL_P->set_title($html)) 					{ phase2_error("title is fail"); continue; }
			if(!$VWSL_P->set_body($html));
			if(!$VWSL_P->set_created($html)) 				{ phase2_error("created_time is fail"); continue; }
			if(!$VWSL_P->set_author_account($html))	{ phase2_error("author_account is fail"); continue; }
			if(!$VWSL_P->set_author_thumb($html))		{ phase2_error("author_thumb is fail"); continue; }
			if(!$VWSL_P->set_views($html));
			if($VWSL_P->type == "photo") {
				$VWSL_P->set_exif($html);
			}
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_P->xml_mapping()));
			echo "status: " . $status . "\n";
			$cnt++;
		}
		if($cnt == 0) break;
	}
?>
