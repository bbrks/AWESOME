<?
require "../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/main.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
));
