<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/customise/module.html');

$questionaireID = $_GET["questionaireID"];
$moduleID = isset($_GET["moduleID"])?$_GET["moduleID"]:null;
$alerts = array();

function getModule() {
	global $questionaireID, $moduleID, $alerts, $db;
	if (!$moduleID) {
		return array("ModuleID"=>"global", "ModuleTitle"=>"Repeated questions", "Fake"=>true);
	}
	else {
		$stmt = new tidy_sql($db, "
			SELECT ModuleID, ModuleTitle, Fake FROM Modules WHERE QuestionaireID=? AND ModuleID=?
		", "is");
		$module = $stmt->query($questionaireID, $moduleID);
		return $module[0];
	}
}

function getModuleQuestions() { //SQL's WHERE is fussy
	global $questionaireID, $moduleID, $alerts, $db;
	if (!$moduleID) {
		$stmt = new tidy_sql($db, "
			SELECT QuestionID, QuestionText, QuestionText_welsh, Type FROM Questions WHERE QuestionaireID=? AND ModuleID is NULL
		", "i");
		return $stmt->query($questionaireID);
	}
	else {
		$stmt = new tidy_sql($db, "
			SELECT QuestionID, QuestionText, QuestionText_welsh, Type FROM Questions WHERE QuestionaireID=? AND ModuleID=?
		", "is");
		return $stmt->query($questionaireID, $moduleID);
	}
}

function insertQuestion($details) {
	global $questionaireID, $moduleID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "INSERT INTO Questions (QuestionaireID, ModuleID, QuestionText, QuestionText_welsh, Type) VALUES (?,?,?,?,?)", "issss");
		$stmt->query($questionaireID, $moduleID, $details["questionText"], $details["questionText_welsh"], $details["questionType"]);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully added question");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred adding question ({$e->getMessage()})");
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST["action"] == "add_question") {
		insertQuestion($_POST);
	}
}

$module = getModule();
$questions = getModuleQuestions();

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"questions"=>$questions,
	"module"=>$module
));
