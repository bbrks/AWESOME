<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require_once "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";
require_once "../_questionnaire.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/basic.html');

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


$questionnaireID = $_GET["questionnaireID"];
$alerts = array();
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


