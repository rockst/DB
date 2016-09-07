<?php
	include_once(dirname(__FILE__) . "/../.config.php");
	include_once(dirname(__FILE__) . "/../.library.php");
  include_once(dirname(__FILE__) . "/../VWSL.class.php");
  include_once(dirname(__FILE__) . "/../VWSL_Album.class.php");
	include_once(dirname(__FILE__) . "/../simple_html_dom.php");

	$MongoColl = $MongoDB->album;

	for($id = 177030; $id <= 187891; $id++) {

		echo $id . "- ";
		$url = "http://verywed.com/album/photos/" . $id;

		$VWSL_A = new VWSL_Album($id);
		$VWSL_A->set_url($url);

		$code = chk_thread($url);
		echo $code;
		if($code == 200) {
			$html = file_get_html($url);
			$VWSL_A->set_photos($html);
			if($VWSL_A->photos > 0) {
				$is_wedding_cate = false;
				$matches = array();
				foreach($html->find("span.cate") as $element) {
					$is_wedding_cate = preg_match("/(婚禮相關|旅遊)+/i", VWSL::html2text($element->plaintext), $matches);
					break;
				}
				echo " - " . ((!empty($matches[0])) ? $matches[0] : "");
				if($is_wedding_cate) {
					$data = array("id"=>(int) $id, "url"=>(string) $url, "type"=>"album", "stage"=>1);
					echo " - " . insert_mongodb($data);
				}
			}
		}
		echo "\n";
	}
?>
