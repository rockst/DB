<?php 
/** 
 * 
 * Extension for SimpleXMLElement 
 * @author Alexandre FERAUD 
 * 
 */ 
class ExSimpleXMLElement extends SimpleXMLElement 
{ 
  /** 
  * Add CDATA text in a node 
  * @param string $cdata_text The CDATA value  to add 
  */ 
	private function addCData($cdata_text) 
  { 
   $node= dom_import_simplexml($this); 
   $no = $node->ownerDocument; 
   $node->appendChild($no->createCDATASection($cdata_text)); 
  } 

  /** 
  * Create a child with CDATA value 
  * @param string $name The name of the child element to add. 
  * @param string $cdata_text The CDATA value of the child element. 
  */ 
  public function addChildCData($name,$cdata_text) 
  { 
    $child = $this->addChild($name); 
    $child->addCData($cdata_text); 
  } 

  /** 
  * Add SimpleXMLElement code into a SimpleXMLElement 
  * @param SimpleXMLElement $append 
  */ 
  public function appendXML($append) 
  { 
        if ($append) { 
            if (strlen(trim((string) $append))==0) { 
                $xml = $this->addChild($append->getName()); 
                foreach($append->children() as $child) { 
                    $xml->appendXML($child); 
                } 
            } else { 
                $xml = $this->addChild($append->getName(), (string) $append); 
            } 
            foreach($append->attributes() as $n => $v) { 
                $xml->addAttribute($n, $v); 
            } 
        } 
    } 

	public function unicode2str($value) {
		return preg_replace( array('/\x00/', '/\x01/', '/\x02/', '/\x03/', '/\x04/', '/\x05/', '/\x06/', '/\x07/', '/\x08/', '/\x09/', '/\x0A/', '/\x0B/','/\x0C/','/\x0D/', '/\x0E/', '/\x0F/', '/\x10/', '/\x11/', '/\x12/','/\x13/','/\x14/','/\x15/', '/\x16/', '/\x17/', '/\x18/', '/\x19/','/\x1A/','/\x1B/','/\x1C/','/\x1D/', '/\x1E/', '/\x1F/'), array( "\u0000", "\u0001", "\u0002", "\u0003", "\u0004", "\u0005", "\u0006", "\u0007", "\u0008", "\u0009", "\u000A", "\u000B", "\u000C", "\u000D", "\u000E", "\u000F", "\u0010", "\u0011", "\u0012", "\u0013", "\u0014", "\u0015", "\u0016", "\u0017", "\u0018", "\u0019", "\u001A", "\u001B", "\u001C", "\u001D", "\u001E", "\u001F"), $value);
	}
} 
?>
