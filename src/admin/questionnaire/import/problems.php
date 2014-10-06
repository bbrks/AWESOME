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
 * Get list of modules with students enrolled, with missing definitions
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of modules (ModuleID, students list)
 */
function getMissingModules($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
SELECT StudentsToModules.ModuleID,GROUP_CONCAT(DISTINCT StudentsToModules.UserID ORDER BY StudentsToModules.UserID ASC SEPARATOR ' ') AS Students FROM StudentsToModules
LEFT JOIN Modules ON StudentsToModules.ModuleID=Modules.ModuleID AND StudentsToModules.QuestionaireID=Modules.QuestionaireID
WHERE Modules.ModuleID IS NULL
AND StudentsToModules.QuestionaireID=?
GROUP BY StudentsToModules.ModuleID", "i");
	$results = $stmt->query($questionnaireID);
	return $results;
}


function deleteMissingModules($questionnaireID) {
	global $db;
	//pretty much the same query as before, without the group by
	//	and a delete ofc ;)
	$stmt = new tidy_sql($db, "
DELETE StudentsToModules FROM StudentsToModules
LEFT JOIN Modules ON StudentsToModules.ModuleID=Modules.ModuleID AND StudentsToModules.QuestionaireID=Modules.QuestionaireID
WHERE Modules.ModuleID IS NULL
AND StudentsToModules.QuestionaireID=?", "i");
	$results = $stmt->query($questionnaireID);
	return $results;
}

/**
 * Get list of modules with staff, with missing names
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of modules (UserID, module list)
 */
function getMissingStaff($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
SELECT StaffToModules.UserID, GROUP_CONCAT(DISTINCT StaffToModules.ModuleID ORDER BY StaffToModules.ModuleID ASC SEPARATOR ' ') AS Modules FROM StaffToModules
LEFT JOIN Staff ON StaffToModules.UserID=Staff.UserID AND StaffToModules.QuestionaireID=Staff.QuestionaireID
WHERE Staff.Name IS NULL
AND StaffToModules.QuestionaireID=?
GROUP BY StaffToModules.UserID", "i");
	$results = $stmt->query($questionnaireID);
	return $results;
}

/**
 * Get list of students, with no modules
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of students (UserID)
 */
function getStudentsWOModules($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
SELECT UserID FROM Students
LEFT JOIN StudentsToModules USING (UserID, QuestionaireID)
WHERE StudentsToModules.UserID IS NULL
AND QuestionaireID=?", "i");
	$results = $stmt->query($questionnaireID);
	return $results;
}

function deleteStudentsWOModules($questionnaireID) {
	global $db;

	$stmt = new tidy_sql($db, "
DELETE Students FROM Students
LEFT JOIN StudentsToModules USING (UserID, QuestionaireID)
WHERE StudentsToModules.UserID IS NULL
AND QuestionaireID=?", "i");
	$results = $stmt->query($questionnaireID);
	return $results;
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/import/problems.html');
	
	if (!isset($_GET["questionnaireID"]) || $_GET["questionnaireID"] === null) {
		throw new Exception("Questionnaire ID is required");
	}
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	$q = getQuestionaire($questionnaireID);
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST["action"])) {
			$action = $_POST["action"];
			if ($action == "delete_missingmodules") {
				deleteMissingModules($questionnaireID);
			}
			elseif ($action == "delete_students") {
				deleteStudentsWOModules($questionnaireID);
			}
		}
	}
	
	
	
	$missingmodules = getMissingModules($questionnaireID);
	$missingstaff = getMissingStaff($questionnaireID);
	$studentsWOModules = getStudentsWOModules($questionnaireID);
	
	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
		"questionnaire"=>$q,
		"missingmodules"=>$missingmodules,
		"missingstaff"=>$missingstaff,
		"studentswomodules"=>$studentsWOModules
	)); 
}