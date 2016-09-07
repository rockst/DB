<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");

	$MongoColl = $MongoDB->evaluate;

	for($id = 94797; $id <= 102096; $id++) {
		echo $id . "- ";
		$url = "http://verywed.com/evaluate/" . $id;
		$code = chk_thread($url);
		echo $code;
		if($code == 200) {
			$data = array("id"=>(int) $id, "url"=>(string) $url, "type"=>"evaluate", "stage"=>1);
			echo " - " . insert_mongodb($data);
		}
		echo "\n";
	}
?>
