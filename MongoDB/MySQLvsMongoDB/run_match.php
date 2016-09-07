<?php
	// 測試關鍵字媒合
	if(!isset($argv[1]) || !preg_match("/^(mysql|mongodb)+$/i", $argv[1])) {
		exit("Please Input mysql or mongodb\n");
	}
	echo $argv[1] . "...\n";

	$file = "test_" . $argv[1] . "_match.php";

	$cnt = 0;
	$sum = 0;
	for($i = 1; $i <= 100; $i++) {
		exec(escapeshellcmd("/usr/bin/php " . $file), $output);
		$time = $output[count($output) - 1];
		if(preg_match("/[0-9]{1}.[0-9]{1}/", $time)) {
			$time = (float) $time;
			echo $i . ": " . $time . "\n";
			$cnt++;
			$sum += $time;
		}
	}
	echo "avg: " . (float) ($sum / $cnt) . "s count: " . $cnt . "\n";
?>
