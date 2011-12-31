<?
#############################
# Functions
#############################
function getTutorialsArray ( $dir ){
  $files = array();
  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        if (is_dir($dir."/".$file)===true){
          $files = array_merge($files, getTutorialsArray($dir."/".$file));
        } elseif ($file == "index.htm") {          
          $tutorial = getTutorialArray($dir."/".$file);
          $files[$dir."/".$file] = $tutorial["upload"];//filemtime($dir."/".$file);
        }
      }
    }
    closedir($handle);
  } 
  return $files;
}
  
function getDivValueById($xmlfile,$id){
  $divs = $xmlfile->getElementsByTagName( "div" );
  foreach ($divs as $div){
    if ($div->getAttribute("id")==$id){
      return $div->nodeValue;
    }
  }
  return False;
}

function getTutorialArray($filename){
  $splitted_file = explode("/", $filename);
  $name = $splitted_file[3];
  $kategory = $splitted_file[2];
  $xmlfile = new DOMDocument();
  @$xmlfile->loadHTMLFile( $filename );
  $title = htmlentities($xmlfile->getElementsByTagName( "h1" )->item(0)->nodeValue,ENT_COMPAT, 'UTF-8');  
  $author = htmlentities(getDivValueById($xmlfile,"author"),ENT_COMPAT, 'UTF-8');
  $subkategory = htmlentities(getDivValueById($xmlfile,"subkategory"),ENT_COMPAT, 'UTF-8');
  $image = getDivValueById($xmlfile,"demoimage");
  $difficulty = getDivValueById($xmlfile,"difficulty");
  $length = getDivValueById($xmlfile,"length");
  $description = htmlentities(getDivValueById($xmlfile,"description"),ENT_COMPAT, 'UTF-8');
  $keywords = htmlentities(getDivValueById($xmlfile,"keywords"),ENT_COMPAT, 'UTF-8');
  $upload = strtotime(getDivValueById($xmlfile,"uploadtime"));
  $update = strtotime(getDivValueById($xmlfile,"updatetime"));
  
  return array("name" => $name, "title" => $title, "kategory" => $kategory, "image" =>  $image, "author" => $author, "subkategory" => $subkategory, "difficulty" => $difficulty, "length" => $length, "description" => $description, "keywords" => $keywords, "upload" => $upload, "update" => $update);
}

function getCommentsCount($filename){
  if (!file_exists($filename)){
    return 0;
  }
  $xmlfile = new DOMDocument();
  @$xmlfile->load( $filename );
  $comments = $xmlfile->getElementsByTagName("comment");
  return count($comments);
}

function endsWith($string, $char){
  $length = strlen($char);
  $start =  $length *-1; 
  return (substr($string, $start, $length) === $char);
}

function getTutorialHtmlFromOdtXml($file){
  if (file_exists($file)){
    #$xml = simplexml_load_file($file);
    #$body = $xml->xpath('//office:text');
    #$text = $body->{'office:text'};
    #foreach ($xml->xpath('//office:text//*') as $header) {
    #  d(htmlentities((String)$header,ENT_COMPAT,"UTF-8"));
    #}
    $erg = "";
    $doc = DOMDocument::load($file);
    $xml = $doc->getElementsByTagNameNS('urn:oasis:names:tc:opendocument:xmlns:office:1.0','text')->item(0);
    return parseElement($xml->firstChild);
  } else return false;
}

function parseElement($xml){
  $erg = "";
  while ($xml != Null){
    if ($xml->nodeType == 3){
      $txt = htmlentities($xml->nodeValue,ENT_COMPAT,"UTF-8");
      $txt = preg_replace('/\[\[([^\|]*)\|([^\]]*)\]\]/','<a href="$2">$1</a>',$txt);      
      $txt = preg_replace('/\{\{([^\}]*)\}\}/','<a href="$1" style="display:block;width:520px;height:330px" id="player"></a>',$txt);
      $txt = str_replace('\[','[',$txt);
      $txt = str_replace('\{','{',$txt);
      $erg .= str_replace('\|','|',$txt);
      
    } else {
      switch ($xml->nodeName){
        case "text:h":
          $level = $xml->getAttribute("text:outline-level");
          $erg .= "<h$level>".parseElement($xml->firstChild)."</h$level>";
          break;
        case "text:p":
          $class = $xml->getAttribute("text:style-name");
          if ($class == "hinweis"){
            $erg .= "<p class=\"hinweis\">".parseElement($xml->firstChild)."</p>";
          } else {
            $erg .= "<p>".parseElement($xml->firstChild)."</p>";
          }
          break;
        case "text:span":
          $kind = $xml->getAttribute("text:style-name");
          switch ($kind){
            case "T1":
              $erg .= "<b>".parseElement($xml->firstChild)."</b>";
              break;
            case "T2":
              $erg .= "<i>".parseElement($xml->firstChild)."</i>";
              break;
            case "T3":
              $erg .= "<u>".parseElement($xml->firstChild)."</u>";
              break;
          }
          break;
        case "text:a":
          $url = $xml->getAttribute("xlink:href");
          $erg .= "<a href=\"$url\" target=\"_blank\">".parseElement($xml->firstChild)."</a>";
          break;
        #Just to go to draw:image
        case "draw:frame":
          $erg .= parseElement($xml->firstChild);
          break;        
        #TODO small jpg-images
        case "draw:image":
          $url = str_replace("Pictures","images",$xml->getAttribute("xlink:href"));
          $erg .= "<a href=\"$url\" target=\"_blank\"><img src=\"$url\" width=\"700\"/></a>";
          break;
        default:          
          break;
      } 
    }
    $xml = $xml->nextSibling;
  }
  return str_replace("<p></p>","",$erg);  
}

function friendly_url($string){
  $FRIENDLY_CHARS = "abcdefghijklmnopqrstuvwxyz0123456789_";
  $string = strtolower($string);
  $string = str_replace(' ', '_', $string);
  $array = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
  $erg = "";
  foreach($array as $char){
    if (strpos($FRIENDLY_CHARS,$char)!==false){$erg.=$char;}
  }
  return $erg;
}

	

function recursive_remove_directory($directory, $empty = false)	{
	if (substr($directory, -1) == '/')
		$directory = substr($directory, 0, -1);
	if (!file_exists($directory) || !is_dir($directory))
		return false;
	elseif (is_readable($directory)) {
		$handle = opendir($directory);
		while (false !== ($item = readdir($handle))) {
			if ($item != '.' && $item != '..') {
				$path = $directory.'/'.$item;
				if (is_dir($path))
					recursive_remove_directory($path);
				else
					unlink($path);
			}
		}
		closedir($handle);
		if (!$empty) {
			if (!rmdir($directory))
				return false;
		}
	}
	return true;
}

?>