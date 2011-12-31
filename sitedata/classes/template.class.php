<?
class Template {

	public $content;
	private $templateFile;

###########################################################
	function setTemplateFile($filename) {
		#$dir = dirname(__FILE__) . '/../templates';
		#$filename = basename($filename);
		$this->templateFile = "$filename";
	}

###########################################################
	function fill() {
		extract($this->content);
		ob_start();
		include($this->templateFile);
		$html = ob_get_clean();
		return $html;
	}

###########################################################
} #end class
?>