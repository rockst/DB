<?php

$m = new MongoClient();
// db name
$db = $m->comedy;
// callection name
$collection = $db->cartoons;

$document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
$collection->insert($document);
$document = array( "title" => "XKCD", "online" => true );
$collection->insert($document);
$cursor = $collection->find();
foreach ($cursor as $document) {
    echo $document["title"] . "\n";
}
