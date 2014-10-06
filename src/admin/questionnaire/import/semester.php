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
require_once "staffmodules.php";

function getModulesToDelete($questionnaireID) {
	global $db;
	$stmt = new tidy_sql($db, "
SELECT Modules.QuestionaireID, Modules.ModuleID, Modules.ModuleTitle,ModuleSemester.ModuleSemester, Fake
FROM Modules
LEFT JOIN ModuleSemester ON Modules.ModuleID=ModuleSemester.ModuleID and Modules.QuestionaireID=ModuleSemester.QuestionnaireID
WHERE (ModuleSemester.SemesterWithinQuestionnaire=false OR ModuleSemester.SemesterWithinQuestionnaire IS NULL) AND Modules.Fake=false
AND QuestionaireID=?", "i");
	$rows = $stmt->query($questionnaireID);
	return $rows; 
}

function deleteModules($questionnaireID) {
	global $db;
	//exactly the same as getModulesToDelete query, except first line replaced with DELETE Modules
	$stmt = new tidy_sql($db, "
DELETE Modules
FROM Modules
LEFT JOIN ModuleSemester ON Modules.ModuleID=ModuleSemester.ModuleID and Modules.QuestionaireID=ModuleSemester.QuestionnaireID
WHERE (ModuleSemester.SemesterWithinQuestionnaire=false OR ModuleSemester.SemesterWithinQuestionnaire IS NULL) AND Modules.Fake=false
AND QuestionaireID=?", "i");
	$stmt->query($questionnaireID);
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed

	$template = $twig->loadTemplate('questionnaire/import/semester.html');

	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();

	
	//as this is such a risky page, we actually regenerate semesters every load
	// unlikely to have any ill effects, but ensures that data is consistent
	updateModuleSemesters($questionnaireID);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST["action"])) {
			$action = $_POST["action"];
			if ($action == "delete") {
				deleteModules($questionnaireID);
			}
		}
	}
	
	$modules = getModulesToDelete($questionnaireID);

	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
		"modules"=>$modules
	));
}