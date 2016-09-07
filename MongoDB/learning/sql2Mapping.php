<?php
// on the Australian client
$m = new MongoClient("mongodb://localhost:27017");
$db = $m->comedy;
$db->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);
$c = $db->cartoons;

$cursor = $c->find();
$cursor->getNext();

echo "Reading from: ", $cursor->info()["server"], "\n";

/*
// now $joe has an _id field
$person = array("name" => "joe");
$c->insert($person);
$joe = $c->findOne(array("_id" => $person['_id']));
print_r($joe);
*/

/*
$c->save(array("awards" => array("gold", "silver", "bronze")));
$cursor = $c->findOne(array("awards" => "gold"));
print_r($cursor);
*/
$cursor = $c->findOne(array(), array("title"=>1, "author"=>1));
print_r($cursor);
?>
