<?php

	$m = new MongoClient();
	$db = $m->VW;
	$collection = $db->UGC;

	$handle = opendir(dirname(__FILE__) . "/output/yahoo/");
	while(($entry = readdir($handle)) !== false) {
		if(preg_match("/.xml$/i", $entry)) {
			echo $entry . "\n";
			$file = file_get_contents(dirname(__FILE__) . "/output/yahoo/" . $entry, FILE_USE_INCLUDE_PATH);
			$xml = simplexml_load_string($file, 'SimpleXMLElement', LIBXML_NOCDATA);
			for($i = 0; $i < count($xml->addArticle); $i++)
				$collection->insert((array)$xml->addArticle[$i]);
		}
	}

?>
