<?php

$m = new MongoClient();
$d = $m->VW;
$c = $d->UGC;

$c->remove(array("URL"=>"Calvin and Hobbes"));

?>

