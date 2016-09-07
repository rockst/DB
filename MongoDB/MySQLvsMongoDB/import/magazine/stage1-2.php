<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");
	include(dirname(__FILE__) . "/../simple_html_dom.php");
	include(dirname(__FILE__) . "/../VWSL.class.php");

	$MongoColl = $MongoDB->magazine;

	$page_first = 0; 
	$page_limit = 11;

	$list = "http://verywed.com/magazine/?p={page}";
	$type = "magazine";
	$author_account = "veryWed 非常婚禮";
	$author_thumb = "http://s.verywed.com/s1/2009/03/10/1236679061_ceebd61d5ba5f14e3168c5c909ebc283.jpeg";
	$TPTZ = new DateTimeZone('Asia/Taipei');

	for($p = $page_first; $p <= $page_limit; $p++) {
		$html = file_get_html(preg_replace("/\{page\}/i", $p, $list));

		foreach($html->find("ul#articleList h4 a[href^='http://verywed.com/magazine']") as $i=>$element) {
			$url = ((!preg_match("/^http:\/\/verywed.com/i", $element->href)) ? "http://verywed.com" : "") . $element->href;
			$data[$i] = array(
				"url"=>$url, 
				"type"=>$type, 
				"stage"=>2, 
			);
			if(preg_match("/\/magazine\/vo([0-9]{1,}).html/i", $url, $matches) && !empty($matches[1])) {
				$data[$i]["id"] = (int) $matches[1];
			}
			$data[$i]["data"] = array(
				"id" => $data[$i]["id"],
				"type" => $data[$i]["type"],
				"url" => $data[$i]["url"],
				"title" => $element->plaintext,
				"author_acount"=>$author_account, 
				"author_thumb"=>$author_thumb
			);
		}

		foreach($html->find("ul#articleList p.paragraph a") as $i=>$element) {
			$data[$i]["data"]["body"] = preg_replace("/...\(more\)$/", "", VWSL::html2text($element->plaintext));
		}

		foreach($html->find("ul#articleList p span.volg") as $i=>$element) {
			$tmp = preg_replace("/\//", "-", preg_replace("/ 上線日期:/", "", $element->plaintext));
			$D = new DateTime($tmp, $TPTZ);
			$data[$i]["data"]["created"] = (int) $D->getTimestamp();
			$data[$i]["data"]["updated"] = $data[$i]["data"]["created"];
		}

		for($i = 0; $i < count($data); $i++) {
			echo $data[$i]["id"] . "- status: " . insert_mongodb($data[$i]) . "\n";
		}

	}
?>
