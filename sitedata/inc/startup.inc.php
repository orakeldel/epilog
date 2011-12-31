<?
###########################
# Klassen inkludieren
###########################

include("data/inc/config.inc.php");
include("data/inc/recaptchalib.inc.php");
function include_all_once ($pattern) {
    foreach (glob($pattern) as $file) { // remember the { and } are necessary!
        include $file;
    }
}

include_all_once('data/classes/*.class.php');

if ($CONF[debug]==1){
  include("data/inc/dd.inc.php");
}

include("data/inc/functions.inc.php");

$ad_generator = new Ad();

###########################
# Template initialisieren
###########################
$mainTemplate = new Template();
?>