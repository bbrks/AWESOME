<?
$start = microtime(true);
register_shutdown_function('output_timer');
function output_timer() {
	global $start;
	echo "<!-- Page generated in ". (microtime(true)-$start) ." seconds! :D -->";
}

global $db;

require "db.php";
if ($db->connect_errno)
	throw "Failed to connect";

class tidy_sql {
	public $db;
	public $types;
	public $stmt;
	public $error;
	public $errno;
	
	public function tidy_sql($db, $query, $types = "") {
		$this->db = $db;
		$this->types = $types;
		
		if (!$this->stmt = $db->prepare($query)) {
			throw new Exception("SQL prepare: ".strval($db->errno)." - ".$db->error);
		}
	}
	
	public function query() {
		$args = func_get_args();
		if (count($args) > 0) {
			//param args, bind_param works by reference
			//	so we create a 2nd array consisting of just pointers to the first
			$pargs = array();
			foreach($args as &$arg) { $pargs[] = &$arg; }
			array_unshift($pargs,$this->types);
			call_user_func_array(array($this->stmt, "bind_param"),$pargs);
		}
		
		$exec = $this->stmt->execute();
		if ($this->stmt->errno != 0) {
			$message = "SQL Execute: ".strval($this->stmt->errno)." - ".$this->stmt->error;
			$this->stmt->reset();
			throw new Exception($message);
		}
		elseif ($this->stmt->result_metadata()) {
			$rows = $this->getRows();
			$this->stmt->reset();
			return $rows;
		}
		else {
			$this->stmt->reset();
			return $exec;
		}
	}
	

	public function getRows() {
		
		if (function_exists("mysqli_stmt_get_result")) {
			$result = $this->stmt->get_result();
			$rows = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$rows[] = $row;
			}
			return $rows;
		}
		
		else { //mysqlnd not being used, error prone :(
			$meta = $this->stmt->result_metadata();
			while ($field = $meta->fetch_field())
			{
				$params[] = &$row[$field->name];
			}

			call_user_func_array(array($this->stmt, 'bind_result'), $params);

			while ($this->stmt->fetch()) {
				foreach($row as $key => $val)
				{
					$c[$key] = $val;
				}
				$result[] = $c;
			}
			return isset($result)?$result:array();
		}
		
	}
}

function getStudentDetails($token) {
	global $db;
	
	$stmt = new tidy_sql($db, "SELECT * FROM Students WHERE `Token`=?", "s");
	
	$rows = $stmt->query($token);
	return $rows[0];
}

function getStudentModules($details) {
	global $db;

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
				return false;
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
