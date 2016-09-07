<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Forum extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_alias,author_thumb,replies,views,image";

		public function __construct($id) {

			parent::set_id($id);
			parent::set_type("expexch");

		}

		public function set_title(&$html) {

			foreach($html->find("div#post_" . $this->id . " div.subject h4") as $element) {
				parent::set_title($element->plaintext);
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			foreach($html->find("div#post_" . $this->id . " div.dfs") as $element) {
				parent::set_body($element->plaintext);
				break;
			}
			if(!empty($this->body)) { return true; } 
			else if(empty($this->body)) { $this->body = $this->title; return true; } 
			else { return false; }

		}

		public function set_created(&$html) {

			foreach($html->find("div#post_" . $this->id . " p.created") as $element) {
				if(preg_match_all("/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/", $element->plaintext, $matches) && !empty($matches[0][0])) {
					$TPTZ = new DateTimeZone('Asia/Taipei');
					$D = new DateTime(trim($matches[0][0]), $TPTZ);
					parent::set_created((int)$D->getTimestamp());
				}
				break;
			}
			parent::set_updated($this->created);
			return (!empty($this->created)) ? true : false;

		}

		public function set_author_account(&$html) {

			foreach($html->find("div#post_" . $this->id . " a.user_name") as $element) {
				parent::set_author_account($element->plaintext);
				break;
			}

		}

		public function set_author_alias(&$html) {

			foreach($html->find("div#post_" . $this->id . " p.user_name") as $element) {
				$alias = preg_replace("/" . $this->author_account . " 暱稱: /", "", parent::html2text($element->plaintext));
				if($alias != "無") {
					parent::set_author_alias($alias);
				}
				break;
			}

		}

		public function set_author_thumb(&$html) {

			foreach($html->find("div#post_" . $this->id . " img.user_cover") as $element) {
				if(preg_match("/^http:\/\/s.verywed.com/i", $element->src)) {
					parent::set_author_thumb($element->src);
				}
				break;
			}

		}

		public function set_replies_views(&$html) {

			include_once(dirname(__FILE__) . "/simple_html_dom.php");

			foreach($html->find("div#post_" . $this->id . " a[href^=http://verywed.com/forum/userThread/member/]") as $element) {
				$html2 = file_get_html($element->href);
				$temp = array();
				$key  = "";

				foreach($html2->find("td.subject a") as $j=>$element) {
					$temp[$j]["href"] = $element->href;
					if(preg_match("/" . $this->id . "-[0-9]+.html$/", $element->href)) {
						$key = $j;
					}
				}

				foreach($html2->find("td.hitAndReplyCount") as $k=>$element) {
					$temp[$k]["hitAndReplyCount"] = $element->plaintext;
				}

				if(!empty($temp[$key]["hitAndReplyCount"])) {
					$data = explode("/", $temp[$key]["hitAndReplyCount"]);
					parent::set_replies($data[0]);
					parent::set_views($data[1]);
				}
				break;
			}

		}

		public function set_image(&$html, $limit = 5) {

			$images = array();
			foreach($html->find("div#post_" . $this->id . " div.dfs img[src^=http://s.verywed.com/s1]") as $j=>$element) {
				array_push($images, $element->src);
				if(($j + 1) == $limit) { break; }
			}
			parent::set_image($images);

		}

}
?>
