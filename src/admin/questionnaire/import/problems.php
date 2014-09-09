<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/problems.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();


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

$missingmodules = getMissingModules($questionnaireID);
$missingstaff = getMissingStaff($questionnaireID);
$studentsWOModules = getStudentsWOModules($questionnaireID);

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"missingmodules"=>$missingmodules,
	"missingstaff"=>$missingstaff,
	"studentswomodules"=>$studentsWOModules
)); 
