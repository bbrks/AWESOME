<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 * Admin homepage, this is the administrator portal used by staff members in order to create and manage questionnaires.
 */

require "../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('home.html'); 

/**
 * 
 * Add a new questionnaire to the database.
 * @param Array $details (questionaireID, QuestionaireDepartment)
 * @returns new questionnaire id
 */
function insertQuestionaire($details) {
	global $questionnaireID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "INSERT INTO Questionaires (QuestionaireName, QuestionaireDepartment) VALUES (?,?)", "ss");
		$stmt->query($details["questionnaireName"], $details["questionnaireDepartment"]);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully added questionnaire");
		
		return $db->insert_id;
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred adding questionnaire ({$e->getMessage()})");
	}
}

/**
 * 
 * Nukes a questionnaire, and all its children from orbit - provides adequate error messages if unable to sucessfuly perform the operation.
 * 
 * Deletes all Answers, AnswerGroups, Questions, StaffToModules, Staff,
 *     StudentsToModules, Students, Modules, Questionnaires WHERE QuestionaireID=?
 *     in that order.
 * 
 * @param Int $questionnaireID The questionnaire ID
 */
function deleteQuestionaire($questionnaireID) {
	global $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "DELETE Answers FROM Answers INNER JOIN AnswerGroup WHERE AnswerGroup.QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Answers");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Answers ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM AnswerGroup WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted AnswerGroups");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting AnswerGroups ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Questions WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Questions");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Questions ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM StaffToModules WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Staff Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Staff Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Staff WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Staff");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Staff ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM StudentsToModules WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Student Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Student Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Students WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Students");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Students ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Modules WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Modules");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Modules ({$e->getMessage()})");
		return;
	}
	
	try {
		$stmt = new tidy_sql($db, "DELETE FROM Questionaires WHERE QuestionaireID=?", "i");
		$stmt->query($questionnaireID);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully deleted Questionaire (finally :D)");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred deleting Questionaire ({$e->getMessage()})");
		return;
	}
}


/**
 * 
 * @returns Returns a list of every questionnaire,
 *     containing all their data (ID, Name, Dept), along with the
 *     Answer Count, and the total students.
 */
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
	
	foreach ($rows as &$questionnaire) {
		$questionnaire["Percent"] = $questionnaire["Total"]==0?0:($questionnaire["Answers"]/$questionnaire["Total"])*100;
	}

	return $rows;
}

$alerts = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["action"])) {
	$action = $_POST["action"];
	if ($action == "add_questionnaire") {
		$id = insertQuestionaire($_POST);
		//todo: add a redirect, or let user manually go into modify?
	}
	if ($action == "table") { //a button within table was clicked
		if (isset($_POST["delete"])) {
			deleteQuestionaire($_POST["delete"]);
		}
	}
}

$questionnaires = getQuestionaires();

echo $template->render(array(
	"url"=>$url,"alerts"=>$alerts,
	"questionnaires"=>$questionnaires
));
