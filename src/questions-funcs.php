<?

function getStudentDetails($token) {
	global $db;
	
	$stmt = new tidy_sql($db, "SELECT * FROM Students WHERE `Token`=?", "s");
	$rows = $stmt->query($token);
	
	if(isset($rows[0])) {
		return $rows[0];
		
	}
	//todo: move into questions.php
	else {
		echo "<h1>Uh oh.</h1> <p>We have been unable to provide you with your questionnaire.</p>
			<ul>
				<li>Check whether the URL is correct.</li>
				<li>Email your tutor to inform them of the issue.</li>
			</ul>
		
		";
	}
}

function getStudentModules($details) {
	global $db;

	/*
	 * Modules(ModuleID, ModuleTitle, Fake, QuestionaireID)
	 * StudentsToModules(UserID, QuestionaireID)
	 *  The goal is to get only modules the student is doing, or where
	 *    Module.Fake = true
	 *  An inner join would do the first goal, but not the second
	 *    so we simulate it by doing a left join (matches all other students)
	 *    and filter it down using a where to either the student or where fake=true
	 * 
	 */
	$stmt = new tidy_sql($db, "
SELECT Modules.ModuleID AS ModuleID, Modules.ModuleTitle as ModuleTitle, Modules.Fake AS Fake
FROM Modules

LEFT JOIN StudentsToModules ON StudentsToModules.ModuleID = Modules.ModuleID
	AND StudentsToModules.QuestionaireID = Modules.QuestionaireID
WHERE (StudentsToModules.UserID=? OR Modules.Fake = true)
AND Modules.QuestionaireID = ?
GROUP BY Modules.ModuleID
	", "ss");

	$rows = $stmt->query($details["UserID"], $details["QuestionaireID"]);
	
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
	/*
	 * The goal of this one is to retrieve a list of staff paired against
	 *   each module, it was originally written to filter down to student
	 *   but added too much complexity.
	 * 
	 * The join is right, to allow for some tolerance for incomplete data,
	 *   a missing staff name will simply make their name show as blank.
	 */
	$stmt = new tidy_sql($db, "
		SELECT StaffToModules.ModuleID AS ModuleID, StaffToModules.UserID AS StaffID, Staff.Name as StaffName
		FROM Staff
		RIGHT JOIN StaffToModules ON StaffToModules.UserID = Staff.UserID AND StaffToModules.QuestionaireID = Staff.QuestionaireID
		WHERE StaffToModules.QuestionaireID=?", "i");


	$rows = $stmt->query($details["QuestionaireID"]);

	$lecturers = array();
	foreach($rows as $row) {
		$lecturers[$row["ModuleID"]][] = $row;
	}
	return $lecturers;
}

function getQuestions($details) {
	global $db;

	$stmt = new tidy_sql($db, "SELECT * from Questions WHERE Questions.QuestionaireID = ? ORDER BY QuestionID ASC", "i");

	return $stmt->query($details["QuestionaireID"]);
}

function getPreparedQuestions($details, $answers = array()) {
	$questions = getQuestions($details);
	$modules = getStudentModules($details);

	foreach($modules as $mkey => &$module) {

		$module["Questions"] = array();

		foreach($questions as $question) {
			$identifier = "{$module["ModuleID"]}_{$question["QuestionID"]}";
			if ((!$question["ModuleID"] && !$module["Fake"]) || // fake modules do not have generic questions
				strcasecmp($question["ModuleID"],$module["ModuleID"]) == 0) {
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

function answer_filled($question) {
	if ($question["Answer"] == "") {
		return false;
	}
	elseif ($question["Type"] == "rate") {
		if ($question["Answer"] < 1 || $question["Answer"] > 5) {
			return false;
		}
	}
	return true;
}

function answers_filled($modules) {
	foreach($modules as $module) {
		foreach($module["Questions"] as $question) {
			if (!answer_filled($question))
				return true; //temporaryfix
		}
	}
	return true;
}

function answers_submit($details, $modules) {
	global $db;
	$db->autocommit(false);

	$stmt = new tidy_sql($db, "INSERT INTO AnswerGroup (QuestionaireID) VALUES (?)", "i");
	$stmt->query($details["QuestionaireID"]);

	$answerID = $db->insert_id;

	$stmt = new tidy_sql($db, "INSERT INTO Answers (AnswerID, QuestionID, ModuleID, StaffID, NumValue, TextValue) VALUES (?,?,?,?,?,?)", "iissis");
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

			$stmt->query($answerID, $question["QuestionID"], $module["ModuleID"], $StaffID, $NumValue, $TextValue);
		}
	}
	if (!$db->commit()) {
		$db->rollback();
	}
} 
