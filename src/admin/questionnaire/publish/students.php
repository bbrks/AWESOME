<?php
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */
@define("__MAIN__", __FILE__); // define the first file to execute

require "../../../lib.php";

/**
 * Retrieves list of students from database
 * 
 * @param int $questionnaireID Questionnaire ID to list students from
 * 
 * @returns array of students (UserID, Department, Token, Module List, Done)
 */
function getStudents($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
	SELECT Students.UserID, Students.Department, Students.Token, GROUP_CONCAT(DISTINCT ModuleID ORDER BY ModuleID ASC SEPARATOR ' ') AS Modules, Students.Done
	FROM Students
	JOIN StudentsToModules ON StudentsToModules.UserID=Students.UserID AND StudentsToModules.QuestionaireID=Students.QuestionaireID 
	WHERE Students.QuestionaireID=?
	GROUP BY Students.UserID
	ORDER BY Students.Done DESC
	", "i");
	
	return $stmt->query($questionnaireID);
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/publish/students.html');
	
	if (!isset($_GET["questionnaireID"]) || $_GET["questionnaireID"] === null) {
		throw new Exception("Questionnaire ID is required");
	}
	
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = parseStudentsCSV($_POST["csvdata"]);
		insertStudents($data, $questionnaireID);
		$alerts[] = array("type"=>"success", "message"=>"Students inserted");
	}
	
	$students = getStudents($questionnaireID);
	echo $template->render(array("url"=>$url, "students"=>$students, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts));
}
