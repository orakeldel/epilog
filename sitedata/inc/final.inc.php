<?
###########################
# Main-Template ausfüllen
###########################
$mainTemplate->setTemplateFile("data/templates/$CONF[template]/$CONF[template].php");
$html = $mainTemplate->fill();
echo $html;
?>