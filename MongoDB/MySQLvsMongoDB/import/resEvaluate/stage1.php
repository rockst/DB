<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");

	$MongoColl = $MongoDB->resEvaluate;

	for($id = 7318; $id <= 8709; $id++) {
		echo $id . "- ";
		$url = "http://verywed.com/resEvaluate/getRestaurant.php?id=" . $id;
		$code = chk_thread($url);
		echo $code;
		if($code == 200) {
			$data = array("id"=>(int) $id, "url"=>(string) $url, "type"=>"resEvaluate", "stage"=>1);
			echo " - " . insert_mongodb($data);
		}
		echo "\n";
	}
?>
