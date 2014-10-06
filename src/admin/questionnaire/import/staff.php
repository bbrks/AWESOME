<?
@define("__MAIN__", __FILE__); // define the first file to execute

/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require "../../../lib.php";


/**
 * Parse CSV data into an array.
 * 
 * @param string $data Raw CSV data
 * 
 * @returns list of parsed staff: (UserID, Name)
 */
function parseStaffCSV($data) {
	$lines = explode("\n",$data);
	$staff = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$staff[] = array(
			"UserID"=>strtolower($csv[0]),
			"Name"=>$csv[1]
		);
	}
	return $staff;
}


/**
 * 
 * Add array of staff into database
 * 
 * @param parsed-staff $stafflist The list of parsed staff (from parseStaffCSV())
 * @param int $questionnaireID The questionnaire ID
 */
function insertStaff($stafflist, $questionnaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "REPLACE INTO Staff (UserID, Name, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($stafflist as $staff) {
		$dbsmodule->query($staff["UserID"], $staff["Name"], $questionnaireID);
	}
}

/**
 * Get staff list from database
 * 
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns List of all staff (UserID, Name)
 */
function getStaff($questionnaireID) {
	global $db;
	$stmt = new tidy_sql($db, "SELECT * FROM Staff WHERE QuestionaireID=?", "i");
	return $stmt->query($questionnaireID);
}

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/import/staff.html');
	
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = parseStaffCSV($_POST["csvdata"]);
			insertStaff($data, $questionnaireID);
			$alerts[] = array("type"=>"success", "message"=>"Staff inserted");
	}
	$staff = getStaff($questionnaireID);
	
	
	
	echo $template->render(array(
		"staff"=>$staff, "url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts
	));
}