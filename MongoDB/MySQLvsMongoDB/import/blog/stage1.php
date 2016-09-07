<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");
	include(dirname(__FILE__) . "/../simple_html_dom.php");

	$MongoColl = $MongoDB->blog;

	$page_first = 14; 
	$page_limit = 136; 

	get_url_list("http://verywed.com/vwblog/channel/wedding?p={page}", "div#articles div.excerpt h5 a", $page_first, $page_limit, "blog");

?>
