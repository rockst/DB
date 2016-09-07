<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL_Album.class.php");

	$MongoColl = $MongoDB->album;

	while(1) {
		$MongoCursor = $MongoColl->find(array("stage"=>1))->limit(100);
		$cnt = 0;
		foreach($MongoCursor as $document) {
			$MongoIDObj = $document["_id"];
			$html = file_get_html($document["url"]);
			echo ($cnt + 1) . "- " . $document["id"] . " ";
			$VWSL_A = new VWSL_Album($document["id"]);
			$VWSL_A->set_url($document["url"]);
			if(!$VWSL_A->set_cover_image($html))		{ phase2_error("cover_image is fail"); continue; }
			if(!$VWSL_A->set_title($html)) 					{ phase2_error("title is fail"); continue; }
			if(!$VWSL_A->set_body($html));
			if(!$VWSL_A->set_created($html)) 				{ phase2_error("created_time is fail"); continue; }
			if(!$VWSL_A->set_author_account($html))	{ phase2_error("author_account is fail"); continue; }
			if(!$VWSL_A->set_author_thumb($html))		{ phase2_error("author_thumb is fail"); continue; }
			if(!$VWSL_A->set_replies($html));
			if(!$VWSL_A->set_views($html));
			if(!$VWSL_A->set_photos($html));
			if(!$VWSL_A->set_image($html));
			$status = update_mongodb($MongoColl, $MongoIDObj, array("stage" => 2, "data" => $VWSL_A->xml_mapping()));
			echo "status: " . $status . "\n";
			$cnt++;
		}
		if($cnt == 0) break;
	}

?>
