<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/customise/modules.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();

global $db, $questionaireID;
$stmt = new tidy_sql($db, "
	SELECT ModuleID, ModuleTitle, Fake FROM Modules WHERE QuestionaireID=? AND Fake=?
", "ii");
$groups = $stmt->query($questionaireID, 1);
$modules = $stmt->query($questionaireID, 0);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"groups"=>$groups,
	"modules"=>$modules
));


