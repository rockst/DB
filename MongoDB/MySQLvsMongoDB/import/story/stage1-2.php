<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");
	include(dirname(__FILE__) . "/../simple_html_dom.php");
	include(dirname(__FILE__) . "/../VWSL_Story.class.php");

	$MongoColl = $MongoDB->story;

	$first = 1; 
	$limit = 5;

	for($id = $first; $id <= $limit; $id++) {
		echo $id . ": ";
		$url = preg_replace("/\{id\}/i", $id, "http://verywed.com/story/vol{id}.htm");
		$html = file_get_html($url);
		$VWSL_S = new VWSL_Story($id);
		$VWSL_S->set_url($url);
		$VWSL_S->set_title($html);
		$VWSL_S->set_body($html);
		$VWSL_S->set_created($html);
		$VWSL_S->set_author_account($html);
		$VWSL_S->set_author_thumb($html);
		$data = array(
			"id"=> $VWSL_S->id,
			"type"=> $VWSL_S->type,
			"url"=> $VWSL_S->url,
			"stage"=>2,
			"data"=> $VWSL_S->xml_mapping()
		);
		echo ((insert_mongodb($data)) ? 1 : 0) . "\n";
	}

?>
