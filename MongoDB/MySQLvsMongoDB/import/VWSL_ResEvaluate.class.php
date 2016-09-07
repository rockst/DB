<?php
include_once(dirname(__FILE__) . "/VWSL.class.php");

Class VWSL_ResEvaluate extends VWSL {

		public $xml_mapping = "id,type,url,cover_image,title,body,created,updated,author_account,author_thumb";

		public function __construct($id) {

			parent::set_id($id);
			parent::set_type("resEvaluate");

		}

		public function set_cover_image(&$html) {

			$album_url = "";
			foreach($html->find("a[href^='/album/photos/']") as $element) {
				$album_url = preg_replace("/\/slide\?idx=[0-9]+$/", "", trim($element->href));
				break;
			}
			if(!$album_url) return false;

			$matches = array();
			$html2 = file_get_html(((preg_match("/^http:\/\/verywed.com/i", $album_url)) ? $album_url : "http://verywed.com" . $album_url));
			foreach($html2->find("div.user-name a") as $element) {
				preg_match("/^\/album\/user\/([0-9]+)$/i", $element->href, $matches);
				break;
			}
			if(!isset($matches[1]) || intval($matches[1]) == 0) return false;

			$user_id = intval($matches[1]);
			$html2 = file_get_html("http://verywed.com/album/user/" . $user_id);
			foreach($html2->find("div.album a[href^='/album/photos/']") as $element) {
				if($album_url == $element->href) {
					foreach($html2->find("div.album a[href^='" . $album_url . "'] img") as $element) {
						parent::set_cover_image(preg_replace("/_sml/i", "_big", $element->src));
						break;
					}
					break;
				}
			}

			return (!empty($this->cover_image)) ? true : false;

		}

		public function set_title(&$html) {

			foreach($html->find("font#d_company") as $element) {
				parent::set_title("宴客經驗談:" . parent::html2text($element->plaintext));
				break;
			}
			return (!empty($this->title)) ? true : false;

		}

		public function set_body(&$html) {

			$body = array();

			foreach($html->find("font#d_company") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "宴客地點:" . ((!empty($tmp)) ? $tmp : ""); 
				break; 
			}

			foreach($html->find("font#d_city") as $element) { 
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "地區:" . ((!empty($tmp)) ? $tmp : ""); 
				break; 
			}

			foreach($html->find("font#d_date") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "宴客日期:" . ((!empty($tmp)) ? $tmp : ""); 
			}

			foreach($html->find("font#d_type") as $element) { 
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "宴客時段:" . ((!empty($tmp)) ? $tmp : ""); 
				break;
			}

			foreach($html->find("font#d_info") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "喜宴價格:" . ((!empty($tmp)) ? $tmp : ""); 
				break;
			}

			foreach($html->find("font#d_limit") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "每桌人數:" . ((!empty($tmp)) ? $tmp : ""); 
			}

			foreach($html->find("font#d_table") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "宴客桌數:" . ((!empty($tmp)) ? $tmp : ""); 
				break;
			}

			foreach($html->find("font#d_guest") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "賓客人數:" . ((!empty($tmp)) ? $tmp : ""); 
				break;
			}

			foreach($html->find("font#d_price") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "最後花費價格:" . ((!empty($tmp)) ? $tmp : ""); 
				break; 
			}

			foreach($html->find("font#d_reason") as $element) {
				$tmp = parent::html2text($element->plaintext);
				$body[] .= "最後花費原因:" . ((!empty($tmp)) ? $tmp : ""); 
				break;
			}

			if(count($body) > 0) {
				parent::set_body(implode("\n", $body));
			}

			return true;

		}

		public function set_updated(&$html) {

			foreach($html->find("font#d_date") as $element) {
				$tmp = preg_replace("/\//", "-", parent::html2text($element->plaintext));
				if((!empty($tmp))) {
					$TPTZ = new DateTimeZone('Asia/Taipei');
					$D = new DateTime($tmp, $TPTZ);
					parent::set_updated((int)$D->getTimestamp());
				}
				break;
			}

			parent::set_created($this->updated);
			return (!empty($this->updated)) ? true : false;

		}

		public function set_author_account(&$html) {

			foreach($html->find("font#d_author") as $element) {
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
