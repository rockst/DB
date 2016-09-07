<?php
Class VWSL {
		public $url; // 基本的索引單位
		public $id; // 專案主鍵 
		public $type; // 專案代號 
		public $cover_image; // 封片圖片 
		public $title; // 文件標題須為純文字
		public $body; // 文件內容須為純文字
		public $created; // 此文件之建立時間
		public $updated; // 此文件之最後更新時間
		public $author_account; // 會員帳號
		public $author_alias; // 會員匿稱
		public $author_thumb; // 會員照片
		public $replies; // 總回應數
		public $views; // 總瀏覽數
		public $image = array(); // 文件內所內嵌之相關圖片URL，可重複
		public $photos; // 電子相簿相片數 
		public $exif; // 相片EXIF

		public function __construct($url = "",$id = 0,$type = "",$cover_image = NULL,$title = "",$body = NULL,$created = "",$updated = NYLL,$author_account = "",$author_alias = NULL,$author_thumb = "",$replies = 0,$views = 0,$image = array(),$photos = 0,$exif = NULL) {

			$this->set_url($url);
			$this->set_id($id);
			$this->set_type($type);
			$this->set_cover_image($cover_image);
			$this->set_title($title);
			$this->set_body($body);
			$this->set_created($created);
			$this->set_updated($updated);
			$this->set_author_account($author_account);
			$this->set_author_alias($author_alias);
			$this->set_author_thumb($author_thumb);
			$this->set_replies($replies);
			$this->set_views($views);
			$this->set_image($image);
			$this->set_photos($photos);
			$this->set_exif($exif);

		}

		public function set_url($url = "") {

			$this->url = trim($url);

		}

		public function set_id($id = 0) {

			$this->id = (int) trim($id);

		}

		public function set_type($type = "") {

			$this->type = trim($type);

		}
 
		public function set_cover_image($cover_image = "") {

			$this->cover_image = trim($cover_image);

		}

		public function set_title($title = "") {

			$this->title = trim($title);

		}

		public function set_body($body = "") {

			$this->body = preg_replace("/\s+/u", "", self::html2text($body));

		}

		public function set_created($created = "") {

			$this->created = (int) $created;

		}

		public function set_updated($updated = "") {

			$this->updated = (int) $updated;

		}

		public function set_author_account($author_account = "") {

			if(!empty($author_account)) {
				$this->author_account = self::html2text($author_account);
				return true;
			} else {
				return false;
			}

		}

		public function set_author_alias($author_alias = "") {

			if(!empty($author_alias)) {
				$this->author_alias = self::html2text($author_alias);
				return true;
			} else {
				return false;
			}

		}

		public function set_author_thumb($author_thumb = "") {

			if(!empty($author_thumb)) {
				$this->author_thumb = self::html2text($author_thumb);
				return true;
			} else {
				return false;
			}

		}


		public function set_replies($replies = "") {

			$this->replies = (int) trim($replies);

		}

		public function set_views($views = "") {

			$this->views = (int) trim($views);

		}

		public function set_image($image = array()) {

			$this->image = $image;

		}

		public function set_photos($photos = "") {

			$this->photos = (int) trim($photos);

		}

		public function set_exif($exif = "") {

			$this->exif = trim($exif);

		}

		public function xml_mapping() {

			$row = array();
			while(list($key, $value) = each($this)) {
				if(preg_match("/(" . preg_replace("/,/", "|", $this->xml_mapping) . ")+/", $key)) {
					if(!empty($value)) {
						$row[$key] = $value;
					}
				}
			}
			return $row;

		}

		/**
		* 建立 XML 檔案實體檔案
		*
		* @param String $filename (^[a-zA-Z0-9]+$)
		* @param Array $rows
		* @param Array $msg
		* @param Boolean $isHeap：是否用來疊加 XML 的變數
		* @return Int $num (threads 數量) or 0 (失敗)
		**/
		public function buildXML($filename, &$rows, $isHeap = false) {
			include_once(dirname(__FILE__) . "/.config.php");
			include_once(dirname(__FILE__) . "/SimpleXMLEX.class.php");

			$Dom = new DOMDocument("1.0");
			$Dom->preserveWhiteSpace = false;
			$Dom->formatOutput = true;

			// 建立 XML 標頭資料 
			$source = XMLROOT . $filename . ".xml";
			if($isHeap == false) {
				$fp = fopen($source, "w");
				fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?><rawfeed version="1.0"></rawfeed>');
				fclose($fp);
			}

			// 建立 XML 內容
			$XML  = new ExSimpleXMLElement($source, null, true);
			foreach($rows as $i=>$row) {
				$Thread = $XML->addChild("addThread");
				while(list($key, $value) = each($row)) {
					if(gettype($value) == "array") {
						$Images = $Thread->addChild("images");
						foreach($value as $data) {
							$data = $XML->unicode2str($data);
							$Images->addChildCData($key, $data);
						}
					} else {
						$value = $XML->unicode2str($value);
						$Thread->addChildCData($key, $value);
					}
				}
			}

			$Dom->loadXML($XML->asXML());
			$fp = fopen($source, (($isHeap) ? "w+" : "w"));
			fwrite($fp, $Dom->saveXML());
			fclose($fp);

			if(file_exists($source)) {
				return true;
			} else {
				return false;
			}

		}

		// strip javascript, styles, html tags, normalize entities and spaces
		// based on http://www.php.net/manual/en/function.strip-tags.php#68757
		public function html2text($html){
			$text = $html;
			static $search = array(
				'@<script.+?</script>@usi',  // Strip out javascript content
				'@<style.+?</style>@usi',    // Strip style content
				'@<!--.+?-->@us',            // Strip multi-line comments including CDATA
				'@</?[a-z].*?\>@usi',         // Strip out HTML tags
			);
			$text = preg_replace($search, ' ', $text);
			// normalize common entities
			$text = self::normalizeEntities($text);
			// decode other entities
			$text = html_entity_decode($text, ENT_QUOTES, 'utf-8');
			// normalize possibly repeated newlines, tabs, spaces to spaces
			$text = preg_replace('/\s+/u', ' ', $text);
			$text = preg_replace('//u', '', $text);
			$text = preg_replace('//u', '', $text);
			$text = trim($text);
			// we must still run htmlentities on anything that comes out!
			// for instance:
			// <<a>script>alert('XSS')//<<a>/script>
			// will become
			// <script>alert('XSS')//</script>
			return $text;
		} 

		// replace encoded and double encoded entities to equivalent unicode character
		// also see /app/bookmarkletPopup.js
		public function normalizeEntities($text) {
			static $find = array();
			static $repl = array();
			if (!count($find)) {
				// build $find and $replace from map one time
				$map = array(
					array('\'', 'apos', 39, 'x27'), // Apostrophe
					array('\'', '‘', 'lsquo', 8216, 'x2018'), // Open single quote
					array('\'', '’', 'rsquo', 8217, 'x2019'), // Close single quote
					array('"', '“', 'ldquo', 8220, 'x201C'), // Open double quotes
					array('"', '”', 'rdquo', 8221, 'x201D'), // Close double quotes
					array('\'', '‚', 'sbquo', 8218, 'x201A'), // Single low-9 quote
					array('"', '„', 'bdquo', 8222, 'x201E'), // Double low-9 quote
					array('\'', '′', 'prime', 8242, 'x2032'), // Prime/minutes/feet
					array('"', '″', 'Prime', 8243, 'x2033'), // Double prime/seconds/inches
					array(' ', 'nbsp', 160, 'xA0'), // Non-breaking space
					array('-', '‐', 8208, 'x2010'), // Hyphen
					array('-', '–', 'ndash', 8211, 150, 'x2013'), // En dash
					array('--', '—', 'mdash', 8212, 151, 'x2014'), // Em dash
					array(' ', ' ', 'ensp', 8194, 'x2002'), // En space
					array(' ', ' ', 'emsp', 8195, 'x2003'), // Em space
					array(' ', ' ', 'thinsp', 8201, 'x2009'), // Thin space
					array('*', '•', 'bull', 8226, 'x2022'), // Bullet
					array('*', '‣', 8227, 'x2023'), // Triangular bullet
					array('...', '…', 'hellip', 8230, 'x2026'), // Horizontal ellipsis
					array('°', 'deg', 176, 'xB0'), // Degree
					array('€', 'euro', 8364, 'x20AC'), // Euro
					array('¥', 'yen', 165, 'xA5'), // Yen
					array('£', 'pound', 163, 'xA3'), // British Pound
					array('©', 'copy', 169, 'xA9'), // Copyright Sign
					array('®', 'reg', 174, 'xAE'), // Registered Sign
					array('™', 'trade', 8482, 'x2122'), // TM Sign
				);
				foreach ($map as $e) {
					for ($i = 1; $i < count($e); ++$i) {
						$code = $e[$i];
						if (is_int($code)) { // numeric entity
							$regex = "/&(amp;)?#0*$code;/";
						} elseif (preg_match('/^.$/u', $code)/* one unicode char*/) { // single character
							$regex = "/$code/u";
						} elseif (preg_match('/^x([0-9A-F]{2}){1,2}$/i', $code)) { // hex entity
							$regex = "/&(amp;)?#x0*" . substr($code, 1) . ";/i";
						} else { // named entity
							$regex = "/&(amp;)?$code;/";
						}
						$find[] = $regex;
						$repl[] = $e[0];
					}
				}
		} // end first time build
		return preg_replace($find, $repl, $text);	
	}

}
?>
