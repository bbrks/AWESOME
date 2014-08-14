<?php
require "../lib.php";

function getQuestionaires() {
	global $db;

	$stmt = new tidy_sql($db, "
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
	$rows = $stmt->query();
	return $rows;
}

function getQuestionaire($questionaireID) {
	global $db;

	$stmt = new tidy_sql($db, "
		SELECT * FROM Questionaires WHERE QuestionaireID=?", "i");

	$rows = $stmt->query($questionaireID);
	
	return $rows[0];
}

function updateQuestionaire($questionaireID, $fields) {
	global $db;

	$stmt = new tidy_sql($db, "
		UPDATE Questionaires SET QuestionaireName=?, QuestionaireDepartment=? WHERE QuestionaireID=?", "ssi");

	$stmt->bind_param("ssi", $fields["QuestionaireName"], $fields["QuestionaireDepartment"], $questionaireID);
	$stmt->execute();
}

function parseStudentsCSV($data) {
	$lines = explode("\n",$data);
	$students = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 3)
			continue;
			
		$students[] = array(
			"UserID" => strtolower($csv[0]),
			"Department" => $csv[1],
			"Modules" => array_map('strtolower',array_slice($csv, 2))
		);
	}
	
	return $students;
}

function insertStudents($students, $questionaireID) {
	global $db;
	$dbstudent = new tidy_sql($db, "INSERT INTO Students (UserID, Department, QuestionaireID, Token, Done) VALUES (?, ?, ?, ?, ?)", "ssisi");
	$dbmodules = new tidy_sql($db, "INSERT INTO StudentsToModules (UserID, ModuleID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach ($students as $student) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$done = false;
		$dbstudent->query($student["UserID"], $student["Department"], $questionaireID, $token, $done);
		
		foreach($student["Modules"] as $module) {
			$dbmodules->query($student["UserID"], $module, $questionaireID);
		}
	}
}

function parseModulesCSV($data) {
	$lines = explode("\n",$data);
	$modules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$modules[] = array(
			"ModuleID"=>strtolower($csv[0]),
			"ModuleTitle"=>$csv[1]
		);
	}
	return $modules;
}

function insertModules($modules, $questionaireID) {
	global $db;
	$dbmodule = new tidy_sql($db, "INSERT INTO Modules (ModuleID, QuestionaireID, ModuleTitle) VALUES (?, ?, ?)", "sis");
	foreach($modules as $module) {
		$dbmodule->query($module["ModuleID"], $questionaireID, $module["ModuleTitle"]);
	}
}

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

function insertStaff($stafflist, $questionaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "INSERT INTO Staff (UserID, Name, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($stafflist as $staff) {
		$dbsmodule->query($staff["UserID"], $staff["Name"], $questionaireID);
	}
}

function parseStaffModulesCSV($data) {
	$lines = explode("\n",$data);
	$staffmodules = array();
	foreach($lines as $line) {
		$csv = str_getcsv($line);
		if (count($csv) < 2)
			continue;
			
		$staffmodules[] = array(
			"ModuleID"=>strtolower($csv[0]),
			"UserID"=>strtolower($csv[1])
		);
	}
	return $staffmodules;
}

function insertStaffModules($staffmodules, $questionaireID) {
	global $db;
	$dbsmodule = new tidy_sql($db, "INSERT INTO StaffToModules (ModuleID, UserID, QuestionaireID) VALUES (?, ?, ?)", "ssi");
	foreach($staffmodules as $staffmodule) {
		$dbsmodule->query($staffmodule["ModuleID"], $staffmodule["UserID"], $questionaireID);
	}
}

function getResults($moduleID, $questionaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
		SELECT Answers.AnswerID, Answers.ModuleID, Answers.QuestionID, Staff.UserID as StaffID, REPLACE(Questions.QuestionText, '%s', CASE WHEN Staff.Name is NULL THEN '' ELSE Staff.Name END) AS QuestionText, Questions.Type, Answers.NumValue, Answers.TextValue FROM Answers
		JOIN AnswerGroup on Answers.AnswerID=AnswerGroup.AnswerID
		LEFT JOIN Questions ON Answers.QuestionID = Questions.QuestionID
		LEFT JOIN Staff ON Answers.StaffID = Staff.UserID AND AnswerGroup.QuestionaireID = Staff.QuestionaireID
		WHERE AnswerGroup.QuestionaireID=?
		AND Answers.ModuleID=?", "is");
	
	$rows = $stmt->query($questionaireID, $moduleID);
	
	$results = array();
	foreach($rows as $row) {
		$id = $row["QuestionID"];
		if ($row["StaffID"]) {
			$id .= "_".$row["StaffID"];
		}
		$results[$id][] = $row;
	}
	return $results;
}
