<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/import/problems.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();

function getMissingModules($questionaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
SELECT StudentsToModules.ModuleID,GROUP_CONCAT(DISTINCT StudentsToModules.UserID ORDER BY StudentsToModules.UserID ASC SEPARATOR ' ') AS Students FROM StudentsToModules
LEFT JOIN Modules ON StudentsToModules.ModuleID=Modules.ModuleID AND StudentsToModules.QuestionaireID=Modules.QuestionaireID
WHERE Modules.ModuleID IS NULL
AND StudentsToModules.QuestionaireID=?
GROUP BY StudentsToModules.ModuleID", "i");
	$results = $stmt->query($questionaireID);
	return $results;
}

$missingmodules = getMissingModules($questionaireID);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"missingmodules"=>$missingmodules
)); 
