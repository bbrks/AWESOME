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
 * Parse CSV data into an array.
 * 
 * @param string $data Raw CSV data
 * 
 * @returns list of parsed staff modules (ModuleID, Semester, Staff array)
 */
function parseStaffModulesCSV($data) {
	$lines = explode("\n",$data);
	$staffmodules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
		
		$staffmodules[] = array(
			"ModuleID" => strtoupper($csv[0]),
			"Semester" => $csv[1],
			"Staff" => array_map('strtolower',array_slice($csv, 2))
		);
	}
	if ($staffmodules[0]["ModuleID"] == "MODULES") {
		array_shift($staffmodules);
	}
	return $staffmodules;
}

/**
 * 
 * Add array of staff modules into database
 * 
 * @param parsed-staff $staffmodules The list of parsed staff (from parseStaffModulesCSV())
 * @param int $questionnaireID The questionnaire ID
 */
function insertStaffModules($staffmodules, $questionnaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "REPLACE INTO StaffToModules (ModuleID, UserID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($staffmodules as $module) {
		foreach($module["Staff"] as $staff) {
			$dbsmodule->query($module["ModuleID"], $staff, $questionnaireID);
		}
	}
}

/**
 * Get staff module list from database
 *
 * @param int $questionnaireID The questionnaire ID
 *
 * @returns List of all staff modules (ModuleID, UserID, QuestionaireID)
 */
function getStaffModules($questionnaireID) {
	global $db;
	$stmt = new tidy_sql($db, "SELECT * FROM StaffToModules WHERE QuestionaireID=?", "i");
	return $stmt->query($questionnaireID);
}

/**
 *
 * Determine if module semester fits within questionnaire semester
 *
 * @param String $questionnaireSemester Questionnaire Semester
 * @param String $moduleSemester Module Semester
 *
 * @returns true/false
 *
 * @exception Throws exception if questionnaire semester is invalid.
 */
function semesterFilter($questionnaireSemester, $moduleSemester) {
	if ($questionnaireSemester == "semesterBoth" || $questionnaireSemester == "semesterSpecial")
		return true;

	if ($questionnaireSemester == "semesterOne") {
		if ($moduleSemester == "1" || $moduleSemester == "1+2")
			return true;
		else
			return false;
	}
	elseif  ($questionnaireSemester == "semesterTwo") {
		if ($moduleSemester == "2" || $moduleSemester == "1+2")
			return true;
		else
			return false;
	}
	else {
		//questionnaire semester is not valid.
		throw new Exception("Questionnaire Semester '{$questionnaireSemester}' is not valid");
	}
}

/**
 *
 * Add array of module semesters into database
 *
 * @param int $questionnaireID The questionnaire ID
 * @param modulesemesters $modules List of modules containing semesters (same struct as staffmodules)
 */
function insertModuleSemesters($questionnaireID, $modules) {
	global $db;
	$questionnaire = getQuestionaire($questionnaireID);
	$dbsmodule = new tidy_sql($db, "REPLACE INTO ModuleSemester (QuestionnaireID, ModuleID, ModuleSemester, SemesterWithinQuestionnaire) VALUES (?, ?, ?, ?)", "issi");
	foreach($modules as $module) {
		$inSemester =  semesterFilter($questionnaire["QuestionaireSemester"], $module["Semester"]);
		$dbsmodule->query($questionnaireID, $module["ModuleID"], $module["Semester"], $inSemester);
	}
}


/**
 *
 * Update module semesters for provided questionnaire ID
 */
function updateModuleSemesters($questionnaireID) {
	global $db;
	$stmt = new tidy_sql($db, "SELECT ModuleID, ModuleSemester AS Semester FROM ModuleSemester WHERE QuestionnaireID=?", "i");
	$modules = $stmt->query($questionnaireID);
	//insertModuleSemesters uses REPLACE INTO, and PK(QID+MID)
	insertModuleSemesters($questionnaireID, $modules);
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/import/staffmodules.html');
	
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	if ($questionnaireID === null) {
		throw new Exception("Questionnaire ID is required");
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = parseStaffModulesCSV($_POST["csvdata"]);
		
		insertStaffModules($data, $questionnaireID);
		insertModuleSemesters($questionnaireID, $data);
		
		$alerts[] = array("type"=>"success", "message"=>"Staff modules inserted");
	}
	
	$staffToModules = getStaffModules($questionnaireID);
	
	echo $template->render(array(
		"staffToModules"=>$staffToModules,"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts
	));
}