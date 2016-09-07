<?php
/*
$m = new MongoClient("mongodb://localhost/?journal=true&w=majority&wTimeoutMS=20000");

$m = new MongoClient("mongodb://localhost/");
$d = $m->comedy;
$c = $d->cartoons;

// Set w=3 on the database object with a timeout of 25000ms
$d->setWriteConcern(3, 25000);

// Set w=majority on the collection object without changing the timeout
$c->setWriteConcern("majority");
*/
$m = new MongoClient("mongodb://localhost/");
$d = $m->comedy;
$c = $d->cartoons;
/*
$doc1 = array(
	"title" => "RC", 
	"author" => "rock", 
	"stage" => 1,
	"status" => "",
	"data" => array("test" => "book", "rock" => "claire")
);
$c->insert($doc1);
*/
$cur = $c->find(array("status"=>""));
foreach($cur as $document) {
	print_r($document);
/*
	// $_id = $document["_id"]->__toString();
	$data = array("test" => "book111", "rock" => "claire222");

	$status = $c->update(
		array("_id" => $document["_id"]), 
		array('$set' => array("status" => "error", "data" => $data))
	);

	print_r($status);
*/
}

//$doc2 = array( "title" => "RC", "author" => "rock");

// w = 0 for insert
//$c->insert($doc1, array("w" => 0));

// w = majority for update
// $c->update($doc1, $doc2, array("w" => "majority"));

// w = 5 and j = true for remove
// $c->update($doc2, array("w" => 5, "j" => true));


// w ="AllDCs" for batchInsert
// $c->update(array($doc1, $doc2), array("w" => "AllDCs"));
?>
