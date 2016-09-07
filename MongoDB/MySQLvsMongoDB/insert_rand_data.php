<?php
	// 增加倍量資料
	include_once(dirname(__FILE__) . "/.config.php");
	include_once(dirname(__FILE__) . "/.library.php");

	if(!isset($argv[1]) || !preg_match("/^(mysql|mongodb)+$/i", $argv[1])) {
		exit("Please Input mysql or mongodb\n");
	}
	echo $argv[1] . "...\n";

	if($argv[1] == "mysql") {
		for($i = 1; $i <= 1000000; $i++) {
			$id = rand(1, 219516);
			$sql = <<<SQL
INSERT INTO `test`.`dc` (`url`, `title`, `body`, `tag`)
SELECT `url`, `title`, `body`, `tag` FROM `test`.`dc_bk_0214` WHERE `id` = {$id}
SQL;
			$sth = $Mysql->prepare($sql);
			echo $i . ": " . $sth->execute() . "\n";
		}
	} else if($argv[1] == "mongodb") {
		for($i = 1; $i <= 1000000; $i++) {
			echo $i . ": ";
			$sql = "SELECT `url`, `title`, `body`, `tag` FROM `test`.`dc_bk_0214` WHERE `id` = :id";
			$sth = $Mysql->prepare($sql);
			$sth->bindParam(':id', rand(1, 219516), PDO::PARAM_INT);
			$sth->execute();
			$row = $sth->fetch(PDO::FETCH_BOTH);
			if(!empty($row)) {
				$data = array(
					"url"=>$row["url"],
					"title"=>$row["title"],
					"body"=>$row["body"]
				);
				if(!empty($row["tag"])) {
					$data["tag"] = array();
					$tmp = explode(",", preg_replace("/(\[|\])+/", "", $row["tag"]));
					foreach($tmp as $t) {
						if(!empty($t)) {
							array_push($data["tag"], (string) $t);
						}
					}
					$data["tag"] = explode(",", preg_replace("/(\[|\])+/", "", $row["tag"]));
				}
				if(!empty($data)) {
					$status = $MongoColl->insert($data);
					echo (isset($status["ok"])) ? $status["ok"] : 0;
				}
			} else {
				echo "No data found";
			}
			echo "\n";
		}
	}
?>
