<?php
@define("__MAIN__", __FILE__); // define the first file to execute

/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk>
 *
 */

require_once "../../../lib.php";

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
		$id = $moduleID."_".$row["QuestionID"];
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
				"FullResults"=>array(),
				"Summary"=>array(0,0,0,0,0)
			);
		$value = $row["NumValue"]?$row["NumValue"]:$row["TextValue"];
		$results[$id]["FullResults"][] = array(
			"AnswerID"=>$row["AnswerID"],
			"Value"=>$value
		);
		$results[$id]["Results"][] = $value;
	}
	
	foreach($results as &$question) {
		if ($question["QuestionType"] == "rate") {
			$total = 0;
			/*foreach($question["Results"] as $result) {		
				$question["Summary"][$result-1] += 1;
			}*/
			if (count($question["Results"]) > 0) {
				$v = array_count_values($question["Results"]);
				
				$question["Summary"] = array(
					isset($v[1])?$v[1]:0,
					isset($v[2])?$v[2]:0,
					isset($v[3])?$v[3]:0,
					isset($v[4])?$v[4]:0,
					isset($v[5])?$v[5]:0
				);
			}
			$question["Mean"] = array_sum($question["Results"])/count($question["Results"]);
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

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/results/moduleresults.html');
	
	if (!isset($_GET["questionnaireID"]) || $_GET["questionnaireID"] === null) {
		throw new Exception("Questionnaire ID is required");
	}
	if (!isset($_GET["moduleID"]) || $_GET["moduleID"] === null) {
		throw new Exception("Module ID is required");
	}
	
	$questionnaireID = $_GET["questionnaireID"];
	$moduleID = $_GET["moduleID"];
	$alerts = array();
	
	$results = getResults($moduleID, $questionnaireID);
	$module = getModuleDetails($questionnaireID, $moduleID);
	
	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts, "moduleID"=>$moduleID,
		"module"=>$module,
		"questions"=>$results
	));
}