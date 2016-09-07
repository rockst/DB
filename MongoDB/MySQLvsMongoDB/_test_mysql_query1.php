<?php
	// 使用關聯表格作 Query dc <---> dc_tag <---> tag
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	$time_start = microtime(true);

	$sql1 = <<<SQL
SELECT distinct `dc_id` FROM `test`.`dc_tag` WHERE `tag_id` in (1, 11) 
UNION
SELECT distinct `dc_id` FROM `test`.`dc_tag` WHERE `tag_id` in (2, 11) 
UNION
SELECT distinct `dc_id` FROM `test`.`dc_tag` WHERE `tag_id` in (3, 11) 
UNION
SELECT distinct `dc_id` FROM `test`.`dc_tag` WHERE `tag_id` in (4, 11) 
ORDER BY 1 
SQL;
	$sth = $Mysql->prepare($sql1);
	$sth->execute();

	$sql = "SELECT `url` FROM `test`.`dc` WHERE id in (" . join(",", $sth->fetchAll(PDO::FETCH_COLUMN)) . ") ORDER BY `id`";
	$sth = $Mysql->prepare($sql);
	$sth->execute();
	echo "count: " . count($sth->fetchAll(PDO::FETCH_COLUMN)) . "\n";
	echo "time: " . (microtime(true) - $time_start) . "\n";
?>
