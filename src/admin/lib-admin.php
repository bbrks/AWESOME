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
	$dbstudent = $db->prepare("INSERT INTO Students (UserID, Department, QuestionareID, Token, Done) VALUES (?, ?, ?, ?, ?)");
	$dbmodules = $db->prepare("INSERT INTO StudentsToModules (UserID, ModuleID, QuestionaireID) VALUES (?, ?, ?)");
	foreach ($students as $student) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$done = false;
		$dbstudent->bind_param("ssisi", $student["UserID"], $student["Department"], $questionaireID, $token, $done);
		$dbstudent->execute();
		
		foreach($student["Modules"] as $module) {
			$dbmodules->bind_param("ssi", $student["UserID"], $module, $questionaireID);
			$dbmodules->execute();
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
	$dbmodule = $db->prepare("INSERT INTO Modules (ModuleID, QuestionaireID, ModuleTitle) VALUES (?, ?, ?)");
	foreach($modules as $module) {
		$dbmodule->bind_param("sis", $module["ModuleID"], $questionaireID, $module["ModuleTitle"]);
		$dbmodule->execute();
	}
}

function parseStaff($data) {
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
	$dbsmodule = $db->prepare("INSERT INTO Staff (UserID, Name, QuestionaireID) VALUES (?, ?, ?)");
	foreach($stafflist as $staff) {
		$dbsmodule->bind_param("ssi", $staffmodule["ModuleID"], $staffmodule["UserID"], $questionaireID);
		$dbsmodule->execute();
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
	$dbsmodule = $db->prepare("INSERT INTO StaffToModules (ModuleID, UserID, QuestionaireID) VALUES (?, ?, ?)");
	foreach($staffmodules as $staffmodule) {
		$dbsmodule->bind_param("ssi", $staffmodule["ModuleID"], $staffmodule["UserID"], $questionaireID);
		$dbsmodule->execute();
	}
}
