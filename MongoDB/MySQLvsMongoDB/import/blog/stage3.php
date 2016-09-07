<?php
	include_once(dirname(__FILE__) . "/../.library.php");
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");
	include_once(dirname(__FILE__) . "/../VWSL.class.php");

	$TPTZ = new DateTimeZone('Asia/Taipei');
	$MongoColl = $MongoDB->blog;

	for($month = 1; $month <= 12; $month++) {

		$year_month = date('Y-m', mktime(0,0,0,$month,1,2013));
		echo $year_month . "\n";

		$D = new DateTime(date('Y-m-d', mktime(0,0,0,$month,1,2013)), $TPTZ);
		$d1 = $D->getTimestamp();
		$D = new DateTime(date('Y-m-d', mktime(0,0,0,$month,date('t',mktime(0,0,0,$month,1,2013)),2013)), $TPTZ);
		$d2 = $D->getTimestamp();

		$MongoCursor = $MongoColl->find(array("stage"=> 2, "data.created" => array('$gte' => $d1, '$lte' => $d2)));
		$rows = array();

		$i = 0;
		foreach($MongoCursor as $document) {
			$status = update_mongodb($MongoColl, $document["_id"], array("stage" => 3));
			echo ($i + 1) . " - " . $document["id"] . " - Update: " . $status . "\n";
			array_push($rows, $document["data"]);
			$i++;
		}

		if(count($rows) > 0) {
			echo "Result: " . VWSL::buildXML("blog_" . $year_month, $rows) . "\n";
		}

		echo "Sleep ...\n";
		sleep(1);
	}
?>
