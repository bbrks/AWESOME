<?
require "../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";


function parseModulesCSV($data) {
	$lines = explode("\n",$data);
	$modules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$modules[] = array(
			"ModuleID"=>strtolower($csv[0]),
			"ModuleTitle"=>$csv[1]
		);
	}
	return $modules;
}

function insertModules($modules, $questionaireID) {
	global $db;
	$dbmodule = new tidy_sql($db, "REPLACE INTO Modules (ModuleID, QuestionaireID, ModuleTitle) VALUES (?, ?, ?)", "sis");
	foreach($modules as $module) {
		$dbmodule->query($module["ModuleID"], $questionaireID, $module["ModuleTitle"]);
	}
}


Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('modify-modules.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = parseModulesCSV($_POST["csvdata"]);
	insertModules($data, $questionaireID);
	$alerts[] = array("type"=>"success", "message"=>"Modules inserted");
}


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


