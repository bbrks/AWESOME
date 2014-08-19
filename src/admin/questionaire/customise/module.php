<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/customise/module.html');

$questionaireID = $_GET["questionaireID"];
$moduleID = $_GET["moduleID"];
$alerts = array();

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

$stmt = new tidy_sql($db, "
	SELECT QuestionID, QuestionText, QuestionText_welsh, Type FROM Questions WHERE QuestionaireID=? AND ModuleID=?
", "is");
$questions = $stmt->query($questionaireID, $moduleID);

$stmt = new tidy_sql($db, "
	SELECT ModuleID, ModuleTitle, Fake FROM Modules WHERE QuestionaireID=? AND ModuleID=?
", "is");
$module = $stmt->query($questionaireID, $moduleID);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"questions"=>$questions,
	"module"=>$module[0]
));
