<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";


function parseStaffCSV($data) {
	$lines = explode("\n",$data);
	$staff = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$staff[] = array(
			"UserID"=>strtolower($csv[0]),
			"Name"=>$csv[1]
		);
	}
	return $staff;
}

function insertStaff($stafflist, $questionnaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "REPLACE INTO Staff (UserID, Name, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($stafflist as $staff) {
		$dbsmodule->query($staff["UserID"], $staff["Name"], $questionnaireID);
	}
}


Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/staff.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = parseStaffCSV($_POST["csvdata"]);
		insertStaff($data, $questionnaireID);
		$alerts[] = array("type"=>"success", "message"=>"Staff inserted");
}


echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts
));


