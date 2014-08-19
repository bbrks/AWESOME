<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";


function parseStaffModulesCSV($data) {
	$lines = explode("\n",$data);
	$staffmodules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
		
		$staffmodules[] = array(
			"ModuleID" => strtoupper($csv[0]),
			"Semester" => $csv[1],
			"Staff" => array_map('strtolower',array_slice($csv, 2))
		);
	}
	if ($staffmodules[0]["ModuleID"] == "MODULES") {
		array_shift($staffmodules);
	}
	return $staffmodules;
}

function insertStaffModules($staffmodules, $questionaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "REPLACE INTO StaffToModules (ModuleID, UserID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($staffmodules as $module) {
		foreach($module["Staff"] as $staff) {
			$dbsmodule->query($module["ModuleID"], $staff, $questionaireID);
		}
	}
}


Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('import-staffmodules.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = parseStaffModulesCSV($_POST["csvdata"]);
	insertStaffModules($data, $questionaireID);
	$alerts[] = array("type"=>"success", "message"=>"Staff modules inserted");
}


echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts
));


