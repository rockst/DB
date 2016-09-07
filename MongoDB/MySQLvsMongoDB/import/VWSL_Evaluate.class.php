<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Evaluate extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb";

		public function __construct($id) {

			parent::set_id($id);
			parent::set_type("evaluate");

		}

		public function set_cover_image(&$html) {

			foreach($html->find("div#single_eva-left1 img.single_evaimg[src^='http://s.verywed.com']") as $element) {
				parent::set_cover_image(
					((preg_match("/s1\/.*\.jpg$/i", $element->src, $matches) && isset($matches[0])) ? "http://s.verywed.com/" . $matches[0] : $element->src)
				);	
				break;
			}

			return (!empty($this->cover_image)) ? true : false;

		}

		public function set_title(&$html) {

			foreach($html->find("div#single_eva-right1") as $element) {
				foreach($element->find("p") as $element) {
					if(preg_match("/婚紗公司： (.*)$/", parent::html2text($element->plaintext), $matches) && isset($matches[1])) {
						parent::set_title("婚紗經驗談:" . $matches[1]);
					}
					break;
				}
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			$body = array();

			foreach($html->find("div#single_eva-right1") as $element) {
				foreach($element->find("p") as $element) {
					$tmp = parent::html2text($element->plaintext);
					if(!preg_match("/^參考網址/", $tmp)) $body[] = $tmp; 
				}
				break;
			}

			if(count($body) > 0) {
				parent::set_body(implode("\n", $body));
			}

			return true;

		}

		public function set_created(&$html) {

			foreach($html->find("div#single_eva_info") as $element) {
				$tmp = parent::html2text($element->plaintext);
				if(preg_match("/經驗談建立日期：([0-9]{4}\/[0-9]{2}\/[0-9]{2})/i", $tmp, $matches) && !empty($matches[1])) {
					$date = preg_replace("/\//i", "-", $matches[1]);
				}
				break;
			}

			if(isset($date) && !empty($date)) {
				$TPTZ = new DateTimeZone("Asia/Taipei");
				$D = new DateTime($date, $TPTZ);
				parent::set_created((int) $D->getTimestamp());
			}

			parent::set_updated($this->created);
			return (!empty($this->created)) ? true : false;

		}

		public function set_author_account(&$html) {

			foreach($html->find("div#single_eva_info a[href^='http://my.verywed.com']") as $element) {
				parent::set_author_account($element->plaintext);
				break;
			}
			return (!empty($this->author_account)) ? true : false;

		}

		public function set_author_thumb(&$html) {

			include_once(dirname(__FILE__) . "/simple_html_dom.php");

			$author_thumb = "";

			if(empty($this->author_account)) {
				if(!$this->set_author_account($html)) {
					return false;
				}
			}

			$html2 = file_get_html("http://verywed.com/my/" . $this->author_account);
			foreach($html2->find("img#UserImage") as $element) {
				$element->src = ($element->src == "/images/user_default_photo.jpg") ? "http://verywed.com" . $element->src : $element->src;
				parent::set_author_thumb($element->src);
			}
			return (!empty($this->author_thumb)) ? true : false;

		}

}
?>
