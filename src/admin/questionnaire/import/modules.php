<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

/**
 * Parse CSV data into an array.
 * 
 * @param string $data Raw CSV data
 * 
 * @returns list of parsed modules: (ModuleID, Title)
 */
function parseModulesCSV($data) {
	$lines = explode("\n",$data);
	$modules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$modules[] = array(
			"ModuleID"=>strtoupper($csv[0]),
			"ModuleTitle"=>$csv[1]
		);
	}
	return $modules;
}

/**
 * 
 * Add array of modules into database
 * 
 * @param parsed-modules $modules The list of parsed modules (from parseModulesCSV())
 * @param int $questionnaireID The questionnaire ID
 */
function insertModules($modules, $questionnaireID) {
	global $db;
	$dbmodule = new tidy_sql($db, "
REPLACE INTO Modules (ModuleID, QuestionaireID, ModuleTitle) VALUES (?, ?, ?)", "sis");
	foreach($modules as $module) {
		$dbmodule->query($module["ModuleID"], $questionnaireID, $module["ModuleTitle"]);
	}
}


/**
 * Get module list from database
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of all modules (ModuleID, ModuleTitle)
 */
function getModules($questionnaireID) {
	global $db;
	$stmt = new tidy_sql($db, "
SELECT ModuleID, ModuleTitle FROM Modules WHERE QuestionaireID=? AND Fake=0", "i");
	$modules = $stmt->query($questionnaireID);
	return $modules;
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/modules.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = parseModulesCSV($_POST["csvdata"]);
	insertModules($data, $questionnaireID);
	$alerts[] = array("type"=>"success", "message"=>"Modules inserted");
}

$modules = getModules($questionnaireID);

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"modules"=>$modules
));


