<?php
	$m = new MongoClient();
	$db = $m->Test;
	$collection = $db->Rock;
	$data = array("顏色"=>array("黑色", "深灰色", "淺灰色"));
	$collection->insert($data);
?>
