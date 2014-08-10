<?php
require "../lib.php";

function getQuestionaires() {
	global $db;

	$stmt = $db->prepare("
		SELECT *, (
				SELECT COUNT(*)
				FROM AnswerGroup
				WHERE Questionaires.QuestionaireID = AnswerGroup.QuestionaireID
			) AS Answers,
			(
				SELECT COUNT(DISTINCT StudentsToModules.UserID)
				FROM StudentsToModules
				WHERE Questionaires.QuestionaireID = StudentsToModules.QuestionaireID
			) AS Total
		FROM Questionaires
	");
	$stmt->execute();
	$rows = getRows($stmt);
	return $rows;
}

function getQuestionaire($questionaireID) {
	global $db;

	$stmt = $db->prepare("
		SELECT * FROM Questionaires WHERE QuestionaireID=?");

	$stmt->bind_param("i", $questionaireID);
	$stmt->execute();

	$rows = getRows($stmt);
	
	return $rows[0];
}

function updateQuestionaire($questionaireID, $fields) {
	global $db;

	$stmt = $db->prepare("
		UPDATE Questionaires SET QuestionaireName=?, QuestionaireDepartment=? WHERE QuestionaireID=?");

	$stmt->bind_param("ssi", $fields["QuestionaireName"], $fields["QuestionaireDepartment"], $questionaireID);
	$stmt->execute();
}

function parseCSV($data) {
	$lines = explode("\n",$data);
	$students = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 4)
			continue;
			
		$students[] = array(
			"UserID" => $csv[0],
			"Department" => $csv[1],
			"Modules" => array_slice($csv, 2)
		);
	}
	
	return $students;
}

function insertStudents($students, $questionaireID) {
	global $db;
	$dbstudent = $db->prepare("INSERT INTO Students (UserID, Department, QuestionareID) VALUES (?, ?, ?)");
	$dbmodules = $db->prepare("INSERT INTO StudentsToModules (UserID, ModuleID, QuestionaireID) VALUES (?, ?, ?)");
	foreach ($students as $student) {
		$dbstudent->bind_param("sss", $student["UserID"], $student["Department"], $questionaireID);
		$dbstudent->execute();
		
		foreach($student["Modules"] as $module) {
			$dbmodules->bind_param("ssi", $student["UserID"], $module, $questionaireID);
			$dbmodules->execute();
		}
	}
}
