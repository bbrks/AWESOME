<?
require "../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('home.html'); 

function insertQuestionaire($details) {
	global $questionaireID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "INSERT INTO Questionaires (QuestionaireName, QuestionaireDepartment) VALUES (?,?)", "ss");
		$stmt->query($questionaireID, $details["questionaireName"], $details["questionaireDepartment"]);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully added questionnairep");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred adding questionnaire ({$e->getMessage()})");
	}
}

function deleteQuestionaire($questionaireID) {
	global $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "DELETE Answers FROM Answers INNER JOIN AnswerGroup WHERE AnswerGroup.QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Answers");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Answers ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM AnswerGroup WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted AnswerGroups");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting AnswerGroups ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Questions WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Questions");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Questions ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM StaffToModules WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Staff Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Staff Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Staff WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Staff");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Staff ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM StudentsToModules WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Student Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Student Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Students WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Students");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Students ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Modules WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Questionaires WHERE QuestionaireID=?", "i");
		$stmt->query($questionaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Questionaire (finally :D)");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Questionaire ({$e->getMessage()})");
		return;
	}
}

function getQuestionaires() {
	global $db;

	$stmt = new tidy_sql($db, "
		SELECT *, (
				SELECT COUNT(*)
				FROM AnswerGroup
				WHERE Questionaires.QuestionaireID = AnswerGroup.QuestionaireID
			) AS Answers,
			(
				SELECT COUNT(DISTINCT StudentsToModules.UserID)
				FROM StudentsToModules
				WHERE Questionaires.QuestionaireID = StudentsToModules.QuestionaireID
			) AS Total
		FROM Questionaires
	");
	$rows = $stmt->query();
	
	foreach ($rows as &$questionaire) {
		$questionaire["Percent"] = $questionaire["Total"]==0?0:($questionaire["Answers"]/$questionaire["Total"])*100;
	}

	return $rows;
}

$alerts = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["action"])) {
	$action = $_POST["action"];
	if ($action == "add_questionaire") {
		insertQuestionaire($_POST);
	}
	if ($action == "table") { //a button within table was clicked
		if (isset($_POST["delete"])) {
			deleteQuestionaire($_POST["delete"]);
		}
	}
}

$questionaires = getQuestionaires();

echo $template->render(array(
	"url"=>$url,"alerts"=>$alerts,
	"questionaires"=>$questionaires
));
