<?php
	// 測試 MySQL`s match 主程式
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);
	$sql = <<<SQL
SELECT * FROM `test`.`dc` 
WHERE (
	(tag like '%[$t1]%' AND tag like '%[$t5]%')
	OR
	(tag like '%[$t2]%' AND tag like '%[$t5]%')
	OR
	(tag like '%[$t3]%' AND tag like '%[$t5]%')
	OR
	(tag like '%[$t4]%' AND tag like '%[$t5]%')
)
ORDER BY `url` 
SQL;
echo $sql;
	$sth = $Mysql->prepare($sql);
	$sth->execute();

echo $sth->fetchColumn() . "\n";
/*
	while($url = $sth->fetch(PDO::FETCH_COLUMN))
		echo $url . "\n";
*/
	echo sprintf("%0.6f", (microtime(true) - $time_start));
	echo "\n";

/*
	// 使用 REGEXP match
	$time_start = microtime(true);
	$sql = <<<SQL
SELECT `id` FROM `test`.`dc` 
WHERE `tag` REGEXP :t1 AND `tag` REGEXP :t2 
SQL;
	$sth = $Mysql->prepare($sql);
	$sth->bindValue(":t1" , "(\\[1\\]|\\[2\\]|\\[3\\]|\\[4\\])", PDO::PARAM_STR); 
	$sth->bindValue(":t2" , "\\[13\\]", PDO::PARAM_STR); 
	$sth->execute();
	echo "count: " . count($sth->fetchAll(PDO::FETCH_COLUMN)) . "\n";
	echo "time: " . (microtime(true) - $time_start) . "\n";
*/
?>
