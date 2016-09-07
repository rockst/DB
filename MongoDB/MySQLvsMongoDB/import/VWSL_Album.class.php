<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Album extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb,replies,views,photos,image";

		public function __construct($id) {

			parent::set_id($id);
			parent::set_type("album");

		}

		public function set_cover_image(&$html) {

			$matches = array();
			foreach($html->find("div.user-name a") as $element) {
				preg_match("/^\/album\/user\/([0-9]+)$/i", $element->href, $matches);
				break;
			}
			if(!isset($matches[1]) || intval($matches[1]) == 0) return false;

			$user_id = intval($matches[1]);
			$html2 = file_get_html("http://verywed.com/album/user/" . $user_id);
			foreach($html2->find("div.album a[href^='/album/photos/']") as $element) {
				if(preg_match("/" . $this->id . "/", $element->href)) {
					foreach($html2->find("div.album a[href^='/album/photos/" . $this->id . "'] img") as $element) {
						parent::set_cover_image(preg_replace("/_sml/i", "_big", $element->src));
						break;
					}
					break;
				}
			}

			return (!empty($this->cover_image)) ? true : false;

		}

		public function set_title(&$html) {

			foreach($html->find("span#alb_name") as $element) {
				parent::set_title($element->plaintext);
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			foreach($html->find("div.album-descri span") as $element) {
				parent::set_body($element->plaintext);
				break; 
			}

			return (!empty($this->body)) ? true : false;

		}

		public function set_created(&$html) {

			foreach($html->find("span.hits") as $element) {
				if(preg_match("/建立日期：([0-9]{4}\/[0-9]{2}\/[0-9]{2})/", parent::html2text($element->plaintext), $matches)) {
					$TPTZ = new DateTimeZone('Asia/Taipei');
					$D = new DateTime(preg_replace("/\//", "-", $matches[1]), $TPTZ);
					parent::set_created((int)$D->getTimestamp());
				} else {
					return false;
				}
				break;
			}
			parent::set_updated($this->created);
			return (!empty($this->created)) ? true : false;

		}

		public function set_author_account(&$html) {

			foreach($html->find("div.user-name a") as $element) {
				parent::set_author_account($element->plaintext);
				break;
			}
			return (!empty($this->author_account)) ? true : false;

		}

		public function set_author_thumb(&$html) {

			foreach($html->find("div#user-info div.img a img") as $element) {
				$element->src = ($element->src == "/images/user_default_photo.jpg") ? "http://verywed.com" . $element->src : $element->src;
				parent::set_author_thumb($element->src);
				break;
			}
			return (!empty($this->author_thumb)) ? true : false;

		}

		public function set_replies(&$html) {

			foreach($html->find("span.message-bookmark a span.album_gb_count") as $element) {
				parent::set_replies($element->plaintext);
				break;
			}
			return (!empty($this->replies)) ? true : false;

		}

		public function set_views(&$html) {

			foreach($html->find("span.hits") as $element) {
				if(preg_match("/人氣：([0-9]+)/", parent::html2text($element->plaintext), $matches)) {
					parent::set_views($matches[1]);
				}
				break;
			}
			return (!empty($this->views)) ? true : false;

		}

		public function set_photos(&$html) {

			foreach($html->find("span#alb_cnt") as $element) {
				parent::set_photos($element->plaintext);
				break;
			}
			return (!empty($this->photos)) ? true : false;

		}

		public function set_image(&$html, $limit = 5) {
	
			$images = array();
			foreach($html->find("div#img-list img[src^='http://s.verywed.com/s1']") as $j=>$element) {
				array_push($images, $element->src);
				if(($j + 1) == $limit) { break; }
			}
			parent::set_image($images);

		}

}
?>
