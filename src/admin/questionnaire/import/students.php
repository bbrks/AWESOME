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
require_once "../_questionnaire.php";

/**
 * Parse CSV data into an array.
 * 
 * @param string $data Raw CSV data
 * 
 * @returns list of parsed students (UserID, Department, Year, Modules array)
 */
function parseStudentsCSV($data) {
	$lines = explode("\n",$data);
	$students = array();
	foreach($lines as $line) {
		$line = rtrim($line, ", \r\n");

		$csv = str_getcsv($line);
		if (count($csv) < 3)
			continue;
			
		$students[] = array(
			"UserID" => strtolower($csv[0]),
			"Department" => $csv[1],
			"Year"=>$csv[2],
			"Modules" => array_map('strtoupper',array_slice($csv, 3))
		);
	}
	
	if (isset($students[0]) && $students[0]["UserID"] == "email") {
		array_shift($students);
	}
	
	return $students;
}

/**
 * 
 * Add array of students into database
 * 
 * @param parsed-students $students The list of parsed students (from parseStudentsCSV())
 * @param int $questionnaire The questionnaire database record
 */
function insertStudents($students, $questionnaire) {
	global $db;
	
	//prepare the queries ready for usage
	$dbstudent = new tidy_sql($db, "INSERT IGNORE INTO Students (UserID, Department, QuestionaireID, Token, Done) VALUES (?, ?, ?, ?, ?) ", "ssisi");
	$dbmodules = new tidy_sql($db, "REPLACE INTO StudentsToModules (UserID, ModuleID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	
	foreach ($students as $student) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$dbstudent->query($student["UserID"], $student["Department"], $questionnaire["questionaireID"], $token, false);
		
		if ($questionnaire["QuestionaireSemester"] != "semesterSpecial") {
			foreach($student["Modules"] as $module) {
				$dbmodules->query($student["UserID"], $module, $questionnaire["questionaireID"]);
			}
		}
	}
}

/**
 * Get student list from database
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of all students (UserID, Department, Token, Module List, Done)
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
	$rows = $stmt->query($questionnaireID);
	return $rows;
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/students.html');
$questionnaireID = $_GET["questionnaireID"];
$q = getQuestionaire($questionnaireID);

$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = parseStudentsCSV($_POST["csvdata"]);
	insertStudents($data, $q);
	$alerts[] = array("type"=>"success", "message"=>"Students inserted");
}

$students = getStudents($questionnaireID);

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"questionnaire"=>$q,
	"students"=>$students,
));

