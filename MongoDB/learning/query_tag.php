<?php
// Config
$dbhost = 'localhost';

// Connect to test database
$m = new Mongo("mongodb://$dbhost");
$db = $m->Yahoo;

// select the collection
$collection = $db->thread;

// pull a cursor query
// $cursor = $collection->find(array("data.title"=>new MongoRegex("/" . $argv[1] . "/")));
// $db->users->find(array('$or' => array(array("a" => 1), array("b" => 2))));
$cursor = $collection->find(
	array("tag.顏色"=>"黑色")
	// array("tag.人物"=>"新娘")
	// array("tag.人物.家人"=>"父母親")
	// array("tag.人物.寵物"=>"狗")
	// array("tag.場合"=>"拜別")
	// array("tag.時間"=>"春天")
	// array("tag.地點.台灣"=>"北台灣")
	// array("tag.地點.亞洲"=>"日本")
	// array("tag.環境"=>"海邊")
	// array("tag.廠商.合作廠商.婚紗攝影"=>"蘿亞")
	// array("tag.廠商.合作廠商.婚紗攝影"=>"蘿亞")
	// array("tag.格式"=>"圖片")
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
	print_r($document["_id"]);
	echo "\n";
}
?>
