<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Blog extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb,views,replies,image";

		public function __construct($id = 0) {

			$this->set_id($id);
			$this->set_type("blog");

		}

		public function set_cover_image() {

		}

		public function set_title(&$html) {

			foreach($html->find("li.article-title a") as $element) {
				parent::set_title($element->plaintext);
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			foreach($html->find("div.article-body") as $element) {
				parent::set_body($element->plaintext);
				break;
			}
			return (!empty($this->body)) ? true : false;

		}

		public function set_created(&$html) {

			foreach($html->find("li.article-date") as $element) {
				$text = parent::html2text($element->plaintext);
				$date = preg_replace("/^(\d{4}) \/ (\d{2}) \/ (\d{2}) \d{2}:\d{2} (AM|PM)+/", "\$1-\$2-\$3", $text);
				$type = preg_replace("/^(\d{4}) \/ (\d{2}) \/ (\d{2}) (\d{2}):(\d{2}) (AM|PM)+/", "\$6", $text);
				if($type == "PM") {
					$time = (int) preg_replace("/^(\d{4}) \/ (\d{2}) \/ (\d{2}) (\d{2}):(\d{2}) (AM|PM)+/", "\$4", $text) + 12;
					$time.= ":" . preg_replace("/^(\d{4}) \/ (\d{2}) \/ (\d{2}) (\d{2}):(\d{2}) (AM|PM)+/", "\$5:00", $text);
				} else {
					$time = preg_replace("/^(\d{4}) \/ (\d{2}) \/ (\d{2}) (\d{2}):(\d{2}) (AM|PM)+/", "\$4:\$5:00", $text);
				}
				$D = new DateTime($date . " " . $time);
				parent::set_created($D->getTimestamp());
				break;
			}
			parent::set_updated($this->created);
			return (!empty($this->created)) ? true : false;

		}

		public function set_author_account(&$html) {

			parent::set_author_account(preg_replace("/^http:\/\/verywed.com\/vwblog\/(.*)\/article\/\d+.*/i", "\$1", $this->url));
			return (!empty($this->author_account)) ? true : false;

		}

		public function set_author_thumb(&$html) {

			if(!$this->author_account) return false;

			$html2 = file_get_html("http://verywed.com/my/" . $this->author_account);
			foreach($html2->find("img#UserImage") as $element) {
				parent::set_author_thumb((($element->src == "/images/user_default_photo.jpg") ? "http://verywed.com" . $element->src : $element->src));
				break;
			}
			return (!empty($this->author_thumb)) ? true : false;

		}

		public function set_views(&$html) { 

			foreach($html->find("div.article-footer ul li") as $element) {
				if(preg_match("/人氣：([0-9]{1,})/i", parent::html2text($element->plaintext), $matches) && isset($matches[1])) {
					parent::set_views($matches[1]);
				} else {
					return false;
				}
				break;
			}
			return (isset($this->views)) ? true : false;

		}

		public function set_replies(&$html) { 

			foreach($html->find("ul li a[href*='message-list']") as $element) {
				if(preg_match("/訪客留言\(([0-9]{1,})\)/i", parent::html2text($element->plaintext), $matches) && isset($matches[1])) {
					parent::set_replies($matches[1]);
				} else {
					return false;
				}
				break;
			}

		}

		public function set_image(&$html, $limit = 5) {

			$images = array();
			foreach($html->find("div.article-body img[src^=http://s.verywed.com/s1]") as $j=>$element) {
				array_push($images, $element->src);
				if(($j + 1) == $limit) { break; }
			}
			parent::set_image($images);

		}

}
?>
