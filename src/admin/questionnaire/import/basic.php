<?
@define("__MAIN__", __FILE__); // define the first file to execute

/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require_once "../../../lib.php";
require_once "../_questionnaire.php";

/**
 * @param int $questionnaireID The questionnaire ID
 * @param array $fields The new questionnaire data:
 *     (QuestionnaireName, QurstionnaireDepartment, QuestionnaireID)
 */

function updateQuestionaire($questionnaireID, $fields) {
	global $db;

	$stmt = new tidy_sql($db, "
		UPDATE Questionaires SET QuestionaireName=?, QuestionaireDepartment=? WHERE QuestionaireID=?", "ssi");

	$stmt->query($fields["QuestionaireName"], $fields["QuestionaireDepartment"], $questionnaireID);
}


/**
 * Note: identical to function in home.php, move to centralised location?
 *
 * @returns Returns a list of valid department names,
 *      format: (departmentcode, departmentname)
 *
 */
function getDepartments() {
	global $db;

	$stmt = new tidy_sql($db, "SELECT DepartmentCode, DepartmentName FROM Departments WHERE enabled=true","");
	return $stmt->query();
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/import/basic.html');

	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	if ($questionnaireID === null) {
		throw new Exception("Questionnaire ID is required");
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$q = array();
			$q["QuestionaireName"] = $_POST["questionnaireName"];
			$q["QuestionaireDepartment"] = $_POST["questionnaireDepartment"];
			
			updateQuestionaire($questionnaireID, $q);
			
			$alerts[] = array("type"=>"success", "message"=>"Questionnaire modified");
	}
	
	$q = getQuestionaire($questionnaireID);
	$departments = getDepartments();
	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
		"questionnaire"=>$q,
		"departments"=>$departments
	));
}