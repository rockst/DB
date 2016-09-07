<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Photo extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb,views,exif";

		public function __construct($id, $type) {

			parent::set_id($id);
			parent::set_type($type);

		}

		public function set_cover_image(&$html) {

			if($this->type == "photo") {
				foreach($html->find("div#right-td-wrap div.img img[src^='http://s.verywed.com/s1']") as $element) {
					parent::set_cover_image($element->src);
					break;
				}
			} else if($this->type == "video") {
				foreach($html->find("div.video img[src^='http://s.verywed.com/s1']") as $element) {
					parent::set_cover_image($element->src);
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

			foreach($html->find("div#img-descri") as $element) {
				parent::set_body(parent::html2text($element->plaintext));
				break; 
			}

			return (!empty($this->body)) ? true : false;

		}

		public function set_created(&$html) {

			foreach($html->find("div#img-info div#date") as $element) {
				if(preg_match("/([0-9]{4}\/[0-9]{2}\/[0-9]{2})上傳/", parent::html2text($element->plaintext), $matches)) {
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

		public function set_views(&$html) {

			foreach($html->find("div#img-info div#hits") as $element) {
				if(preg_match("/人氣：([0-9]+)/", parent::html2text($element->plaintext), $matches)) {
					parent::set_views($matches[1]);
				}
				break;
			}
			return (!empty($this->views)) ? true : false;

		}

		public function set_exif(&$html) {

			foreach($html->find("div#exif") as $element) {
				parent::set_exif($element->plaintext);
				break;
			}
			return (!empty($this->exif)) ? true : false;

		}
}
?>
