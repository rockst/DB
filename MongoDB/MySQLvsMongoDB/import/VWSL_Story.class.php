<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_Story extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb,image";

		public function __construct($id) {

			parent::set_id($id);
			parent::set_type("story");

		}

		public function set_cover_image(&$html) {
		}

		public function set_title(&$html) {

			foreach($html->find("div#header h1") as $element) {
				parent::set_title($element->plaintext);
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			foreach($html->find("div#block2 p.text") as $element) {
				parent::set_body(parent::html2text($element->plaintext));
				break;
			}
			return (!empty($this->body)) ? true : false;

		}

		public function set_created(&$html) {

			Switch($this->id) {
				Case 1:  $date = "2013-06-01"; break;
				Case 2:  $date = "2013-07-01"; break;
				Case 3:  $date = "2013-08-01"; break;
				Case 4:  $date = "2013-10-01"; break;
				Case 5:  $date = "2013-12-01"; break;
				Default: $date = null; break;
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

			parent::set_author_account("veryWed 非常婚禮");
			return (!empty($this->author_account)) ? true : false;

		}

		public function set_author_thumb(&$html) {

			parent::set_author_thumb("http://s.verywed.com/s1/2014/03/06/1394074546_4d45e1ee7ca7ce77dc41d5a2a17e5cb8.png");
			return (!empty($this->author_thumb)) ? true : false;

		}

		public function set_image(&$html) {

		}

}
?>
