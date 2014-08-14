<?

global $db;

require "db.php";
if ($db->connect_errno)
	throw "Failed to connect";

if (function_exists("mysqli_stmt_get_result")) {
	function getRows($stmt) {
		$result = $stmt->get_result();
		$rows = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$rows[] = $row;
		}
		return $rows;
	}
}
else {
	function getRows($stmt) { //PHP prepared statements are shit
		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field())
		{
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($stmt, 'bind_result'), $params);

		while ($stmt->fetch()) {
			foreach($row as $key => $val)
			{
				$c[$key] = $val;
			}
			$result[] = $c;
		}
		return $result;
	}
}

function getStudentDetails($token) {
	global $db;
	
	$stmt = $db->prepare("SELECT * FROM Students WHERE `Token`=?");
	
	$stmt->bind_param("s", $token);
	$stmt->execute();
	
	$rows = getRows($stmt);
	return $rows[0];
}

function getStudentModules($details) {
	global $db;

	$stmt = $db->prepare("
		SELECT StudentsToModules.ModuleID AS ModuleID, Modules.ModuleTitle as ModuleTitle
		FROM Modules

		JOIN StudentsToModules ON StudentsToModules.ModuleID = Modules.ModuleID
			AND StudentsToModules.QuestionaireID = Modules.QuestionaireID
		WHERE StudentsToModules.UserID=?
		AND StudentsToModules.QuestionaireID=?
	");

	$stmt->bind_param("ss", $details["UserID"], $details["QuestionaireID"]);
	$stmt->execute();

	$rows = getRows($stmt);

	$lecturers = getStudentModuleLecturers($details);
	foreach ($rows as &$row) {
		if (array_key_exists($row["ModuleID"], $lecturers)) {
			$row["Staff"] = $lecturers[$row["ModuleID"]];
		}
		else {
			$row["Staff"] = Array();
		}
	}

	return $rows;
}

function getStudentModuleLecturers($details) {
	global $db;

	$stmt = $db->prepare("
		SELECT StaffToModules.ModuleID AS ModuleID, StaffToModules.UserID AS StaffID, Staff.Name as StaffName
		FROM Staff
		RIGHT JOIN StaffToModules ON StaffToModules.UserID = Staff.UserID AND StaffToModules.QuestionaireID = Staff.QuestionaireID
		WHERE StaffToModules.QuestionaireID=?");


	$stmt->bind_param("s", $details["QuestionaireID"]);
	$stmt->execute();
	
	$rows = getRows($stmt);

	$lecturers = array();
	foreach($rows as $row) {
		$lecturers[$row["ModuleID"]][] = $row;
	}
	return $lecturers;
}

function getQuestions() {
	global $db;

	$stmt = $db->prepare("SELECT * from Questions");

	$stmt->execute();
	$rows = getRows($stmt);

	return $rows;
}

function getPreparedQuestions($details, $answers = array()) {
	$questions = getQuestions();
	$modules = getStudentModules($details);

	foreach($modules as $mkey => &$module) {

		$module["Questions"] = array();

		foreach($questions as $question) {
			$identifier = "{$module["ModuleID"]}_{$question["QuestionID"]}";
			if ($question["Staff"] == 0) {
				$question["Identifier"] = $identifier;

				$module["Questions"][] = $question;
			}
			else {
				foreach($module["Staff"] as $staff) {
					$mquestion = $question; //copy question
					$staff_identifier = "{$identifier}_{$staff["StaffID"]}";

					$mquestion["Identifier"] = $staff_identifier;
					$mquestion["QuestionText"] = sprintf($question["QuestionText"], $staff["StaffName"]);
					$mquestion["QuestionText_welsh"] = sprintf($question["QuestionText_welsh"], $staff["StaffName"]);
					$mquestion["StaffID"] = $staff["StaffID"];
					$module["Questions"][] = $mquestion;
				}
			}
		}
		foreach($module["Questions"] as $key => $question) {
			if (array_key_exists($question["Identifier"], $answers)) {
				$module["Questions"][$key]["Answer"] = $answers[$question["Identifier"]];
			}
			else {
				$module["Questions"][$key]["Answer"] = "";
			}
		}
	}
	return $modules;
}

function answers_filled($modules) {
	foreach($modules as $module) {
		foreach($module["Questions"] as $question) {
			if ($question["Answer"] == "") {
				return false;
			}
		}
	}
	return true;
}

function answers_submit($details, $modules) {
	global $db;
	$db->autocommit(false);

	$stmt = $db->prepare("INSERT INTO AnswerGroup (QuestionaireID) VALUES (?)");
	$stmt->bind_param("i", $details["QuestionaireID"]);
	$stmt->execute();
	$stmt->close();

	$answerID = $db->insert_id;

	$stmt = $db->prepare("INSERT INTO Answers (AnswerID, QuestionID, ModuleID, StaffID, NumValue, TextValue) VALUES (?,?,?,?,?,?)");
	foreach($modules as $module) {
		foreach($module["Questions"] as $question) {
			$StaffID = "";
			$NumValue = null;
			$TextValue = null;
			if ($question["Staff"] == 1)
				$StaffID = $question["StaffID"];
			if ($question["Type"] == "rate") {
				$NumValue = $question["Answer"];
			}
			elseif ($question["Type"] == "text") {
				$TextValue = $question["Answer"];
			}

			$stmt->bind_param("iissis", $answerID, $question["QuestionID"], $module["ModuleID"], $StaffID, $NumValue, $TextValue);
			$stmt->execute();
		}
	}
	if (!$db->commit()) {
		$db->rollback();
	}
}
