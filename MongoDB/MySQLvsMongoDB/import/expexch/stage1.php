<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");
	include(dirname(__FILE__) . "/../simple_html_dom.php");

	$MongoColl = $MongoDB->expexch;

	$page_first = 1; 
	$page_limit = 120; 

	get_url_list("http://verywed.com/forum/expexch/list/{page}.html", "td.subject a[href$=-1.html]", $page_first, $page_limit, "expexch");
?>
