<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require "../../../lib.php";

$twig_common = twig_common();
$twig = $twig_common; //reduce code changes needed

$template = $twig->loadTemplate('questionnaire/customise/questions.html');

/**
 * get module details from db (used for title)
 * 
 * @returns Module ID, ModuleTitle, Fake
 */
function getModule() {
	global $questionnaireID, $moduleID, $alerts, $db;
	if (!$moduleID) {
		return array("ModuleID"=>"global", "ModuleTitle"=>"Repeated questions", "Fake"=>true);
	}
	else {
		$stmt = new tidy_sql($db, "
			SELECT ModuleID, ModuleTitle, Fake FROM Modules WHERE QuestionaireID=? AND ModuleID=?
		", "is");
		$module = $stmt->query($questionnaireID, $moduleID);
		return $module[0];
	}
}


/**
 * retrieve questions from db for this module
 * 
 */
function getModuleQuestions() { //SQL's WHERE is fussy
	global $questionnaireID, $moduleID, $alerts, $db;
	if (!$moduleID) {
		$stmt = new tidy_sql($db, "
			SELECT QuestionID, QuestionText, QuestionText_welsh, Type FROM Questions WHERE QuestionaireID=? AND ModuleID is NULL
		", "i");
		return $stmt->query($questionnaireID);
	}
	else {
		$stmt = new tidy_sql($db, "
			SELECT QuestionID, QuestionText, QuestionText_welsh, Type FROM Questions WHERE QuestionaireID=? AND ModuleID=?
		", "is");
		return $stmt->query($questionnaireID, $moduleID);
	}
}

/**
 * Add a new question
 * 
 * @param array $details Question details (QuestionText, QuestionText_welsh, QuestionType, Staff)
 */
function insertQuestion($details) {
	global $questionnaireID, $moduleID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "INSERT INTO Questions (QuestionaireID, ModuleID, QuestionText, QuestionText_welsh, Type, staff) VALUES (?,?,?,?,?,?)", "issssi");
		$stmt->query($questionnaireID, $moduleID, $details["QuestionText"], $details["QuestionText_welsh"], $details["QuestionType"], isset($details["Staff"])?$details["Staff"]:false);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully added question");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred adding question ({$e->getMessage()})");
	}
}

/**
 * Deletes question
 * 
 * @param int $questionID Question ID
 */
function deleteQuestion($questionID) {
	global $questionnaireID, $moduleID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Questions WHERE QuestionID=?", "i");
		$stmt->query($questionID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted question");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting question ({$e->getMessage()})");
	}
}

$questionnaireID = $_GET["questionnaireID"];
$moduleID = isset($_GET["moduleID"])?$_GET["moduleID"]:null;
$alerts = array();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["action"])) {
	$action = $_POST["action"];
	if ($action == "add_question") {
		insertQuestion($_POST);
	}
	if ($action == "table") { //a button within table was clicked
		if (isset($_POST["delete"])) {
			deleteQuestion($_POST["delete"]);
		}
	}
	if ($action == "defaults") {
		if ($_POST["type"] == "full") {
			insertQuestion(array(
				"QuestionText"=>"I have learned a good deal from this module",
				"QuestionText_welsh"=>"Rydw i wedi dysgu llawer o'r modiwl",
				"QuestionType"=>"rate"
			));
			insertQuestion(array(
				"QuestionText"=>"This module was well taught by %s",
				"QuestionText_welsh"=>"Mae'r modiwl ei haddysgu yn dda %s",
				"QuestionType"=>"rate",
				"Staff"=>true
			));
			insertQuestion(array(
				"QuestionText"=>"What one thing would you change to improve this module, and why?",
				"QuestionText_welsh"=>"Gwelliannau Modiwl, a pham?",
				"QuestionType"=>"text"
			));
			insertQuestion(array(
				"QuestionText"=>"Please add any further comments on this module below",
				"QuestionText_welsh"=>"sylwadau pellach",
				"QuestionType"=>"text"
			));
		}
		elseif ($_POST["type"] == "partial") {
			insertQuestion(array(
				"QuestionText"=>"This module has problems",
				"QuestionText_welsh"=>"Mae gan y modiwl problemau",
				"QuestionType"=>"rate"
			));
		}
	}
}

$module = getModule();
$questions = getModuleQuestions();

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"questions"=>$questions,
	"module"=>$module
));
