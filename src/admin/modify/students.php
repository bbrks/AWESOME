<?
require "../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

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
	
	if ($students[0]["UserID"] == "email") {
		array_shift($students);
	}
	
	return $students;
}

function insertStudents($students, $questionaireID) {
	global $db;
	$dbstudent = new tidy_sql($db, "INSERT IGNORE INTO Students (UserID, Department, QuestionaireID, Token, Done) VALUES (?, ?, ?, ?, ?) ", "ssisi");
	$dbmodules = new tidy_sql($db, "REPLACE INTO StudentsToModules (UserID, ModuleID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach ($students as $student) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$done = false;

		$dbstudent->query($student["UserID"], $student["Department"], $questionaireID, $token, $done);
		
		foreach($student["Modules"] as $module) {
			$dbmodules->query($student["UserID"], $module, $questionaireID);
		}

	}
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('students.html');

$questionaireID = $_GET["questionaireID"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = parseStudentsCSV($_POST["csvdata"]);
	insertStudents($data, $questionaireID);
}

$stmt = new tidy_sql($db, "
	SELECT Students.UserID, Students.Department, Students.Token, GROUP_CONCAT(DISTINCT ModuleID ORDER BY ModuleID ASC SEPARATOR ' ') AS Modules, Students.Done
	FROM Students
	JOIN StudentsToModules ON StudentsToModules.UserID=Students.UserID AND StudentsToModules.QuestionaireID=Students.QuestionaireID 
	WHERE Students.QuestionaireID=?
	GROUP BY Students.UserID
	ORDER BY Students.Done DESC
", "i");

$rows = $stmt->query($questionaireID);

echo $template->render(array("url"=>$url, "students"=>$rows));

