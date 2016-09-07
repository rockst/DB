<?php
// Config
$dbhost = 'localhost';

// Connect to test database
$m = new Mongo("mongodb://$dbhost");
$db = $m->comedy;

// select the collection
$collection = $db->cartoons;

// pull a cursor query
$cursor = $collection->find(array("title"=>""), array("title"=>1, "author"=>1));

foreach($cursor as $document) {
print_r($document);
}

?>
