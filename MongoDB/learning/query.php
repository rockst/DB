<?php
// Config
$dbhost = 'localhost';

// Connect to test database
$m = new Mongo("mongodb://$dbhost");
$db = $m->Test;

// select the collection
$collection = $db->Rock;

// pull a cursor query
// $cursor = $collection->find(array("data.title"=>new MongoRegex("/" . $argv[1] . "/")));
// $db->users->find(array('$or' => array(array("a" => 1), array("b" => 2))));
$cursor = $collection->find(
	array("tag.人物.家人"=>"父母親")
/*
	array(	
		'$or' => array(
			array("tag.顏色.黑色"=>1, "tag.人物.家人.父母親"=>1),
			array("tag.顏色.深灰色"=>1, "tag.人物.家人.父母親"=>1)
		)
	)
*/
);
foreach($cursor as $document) {
	print_r($document);
	echo "\n";
}
?>
