<?php
	// 測試 MySQL`s search 主程式
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");
	$time_start = microtime(true);
	$sql = <<<SQL
SELECT `url` FROM `test`.`dc` 
WHERE `title` LIKE '%{$tag_for_search}%' OR `body` LIKE '%{$tag_for_search}%' 
ORDER BY `url` 
LIMIT 100
SQL;
	$sth = $Mysql->prepare($sql);
	$sth->execute();
	while($url = $sth->fetch(PDO::FETCH_COLUMN)) {
		echo $url . "\n";
	}
	echo sprintf("%0.6f", (microtime(true) - $time_start));
	echo "\n";
?>
