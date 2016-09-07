<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL.class.php");

	$TPTZ = new DateTimeZone('Asia/Taipei');
	$MongoColl = $MongoDB->photo;

	for($month = 1; $month <= 1; $month++) {

		$year_month = date('Y-m', mktime(0,0,0,$month,1,2013));
		echo $year_month . "\n";

		$D = new DateTime(date('Y-m-d', mktime(0,0,0,$month,1,2013)), $TPTZ);
		$d1 = $D->getTimestamp();
		$D = new DateTime(date('Y-m-d', mktime(0,0,0,$month,date('t',mktime(0,0,0,$month,1,2013)),2013)), $TPTZ);
		$d2 = $D->getTimestamp();

		// $MongoCursor = $MongoColl->find(array("stage"=> 2, "data.created" => array('$gte' => $d1, '$lte' => $d2)))->limit(1000);
		// $MongoCursor = $MongoColl->find(array("stage"=> 2, "id" => array('$gte' => 5564079, '$lte' => 5564314)))->limit(10);
		// $MongoCursor = $MongoColl->find(array("stage"=> 2, "id" => array('$gte' => 5564079, '$lte' => 5564102)))->limit(2);
		$MongoCursor = $MongoColl->find(array("stage"=> 2, "id" => array('$gte' => 5564102, '$lte' => 5564102)))->limit(1);
		$rows = array();

		$i = 0;
		foreach($MongoCursor as $document) {
			//$status = update_mongodb($MongoColl, $document["_id"], array("stage" => 3));
			//echo ($i + 1) . " - " . $document["id"] . " - Update: " . $status . "\n";
			array_push($rows, $document["data"]);
			$i++;
		}
print_r($rows);
		if(count($rows) > 0) {
			echo "Result: " . VWSL::buildXML("photo_" . $year_month . "_" . $rows[0]["id"] . "-" . $rows[(count($rows)-1)]["id"], $rows) . "\n";
		}

		echo "Sleep ...\n";
		sleep(1);
	}

?>
