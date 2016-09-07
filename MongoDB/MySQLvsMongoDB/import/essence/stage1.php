<?php
	include(dirname(__FILE__) . "/../.config.php");
	include(dirname(__FILE__) . "/../.library.php");
	include(dirname(__FILE__) . "/../simple_html_dom.php");

	$MongoColl = $MongoDB->essence;

	$page_first = 1; 
	$page_limit = 24; 

	get_url_list("http://verywed.com/forum/essence/list/{page}.html", "td.subject a[href$=-1.html]", $page_first, $page_limit, "expexch", "essence");
?>
