<?php
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

$template = $twig->loadTemplate('questionnaire/results/moduleresults.html');

$questionnaireID = $_GET["questionnaireID"];
$moduleID = $_GET["moduleID"];

$alerts = array();

/**
 * Retrieves all the results from the database.
 * 
 * The result is an array keyed by the question id (with staff id appended on, if appropriate)
 *     in each of the questions are the:
 *  - QuestionID,
 *  - QuestionText,
 *  - QuestionType,
 *  - StaffID (if appropriate),
 *  - Key,
 *  - Results (array) =>
 *     - "AnswerID",
 *     - "Value"
 *  - Summary (for ratings) => 5 entry array, to tally up ratings
 * @param int $questionnaireID,$moduleID QuestionnaireID and ModuleID to get results from
 * 
 * @returns array of results (structure described above)
 */
function getResults($moduleID, $questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
		SELECT Answers.AnswerID, Answers.QuestionID, Staff.UserID as StaffID, REPLACE(Questions.QuestionText, '%s', CASE WHEN Staff.Name is NULL THEN '' ELSE Staff.Name END) AS QuestionText, Questions.Type, Answers.NumValue, Answers.TextValue FROM Answers
		JOIN AnswerGroup on Answers.AnswerID=AnswerGroup.AnswerID
		LEFT JOIN Questions ON Answers.QuestionID = Questions.QuestionID
		LEFT JOIN Staff ON Answers.StaffID = Staff.UserID AND AnswerGroup.QuestionaireID = Staff.QuestionaireID
		WHERE AnswerGroup.QuestionaireID=?
		AND Answers.ModuleID=?", "is");
	
	$rows = $stmt->query($questionnaireID, $moduleID);
	
	$results = array();
	foreach($rows as $row) {
		$id = $row["QuestionID"];
		if ($row["StaffID"]) {
			$id .= "_".$row["StaffID"];
		}
		if (!isset($results[$id]))
			$results[$id] = array(
				"QuestionID"=>$row["QuestionID"],
				"QuestionText"=>$row["QuestionText"],
				"QuestionType"=>$row["Type"],
				"StaffID"=>$row["StaffID"],
				"Key"=>$id,
				"Results"=>array(),
				"Summary"=>array(0,0,0,0,0)
			);
		$results[$id]["Results"][] = array(
			"AnswerID"=>$row["AnswerID"],
			"Value"=>$row["NumValue"]?$row["NumValue"]:$row["TextValue"]
		);
		
		if ($row["Type"] == "rate") {
			$results[$id]["Summary"][$row["NumValue"]-1] += 1;
		}
	}
	return $results;
}
/**
 * get database row for module from database (used for title)
 * 
 * @returns module (Module ID, ModuleTitle, Fake, QuestionnaireID, Any other DB columns)
 */
function getModuleDetails($questionnaireID, $moduleID) {
	global $db;
	
	$stmt = new tidy_sql($db, "SELECT * FROM Modules WHERE QuestionaireID=? AND ModuleID=?", "is");
	$results = $stmt->query($questionnaireID, $moduleID);
	return $results[0];
}

function calculateMean($questionnaireID, $moduleID) {
	
}

$results = getResults($moduleID, $questionnaireID);
$module = getModuleDetails($questionnaireID, $moduleID);

//print_r($results);
echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts, "moduleID"=>$moduleID,
	"module"=>$module,
	"questions"=>$results
));
