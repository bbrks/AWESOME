<?

global $db;

require "db.php";
if ($db->connect_errno)
	throw "Failed to connect";

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

function getStudentDetails($student) {
	if ($stmt = $db->prepare("SELECT * FROM Students WHERE `StudentID`=?"))
	{
		$stmt->bind_param("s", $student);
		$stmt->execute();
		return getRow($stmt);
	}
}

function getStudentModules($student)
{
	global $db;
	
	$stmt = $db->prepare("
		SELECT StudentsToModules.ModuleID AS ModuleID, Modules.ModuleTitle as ModuleTitle
		FROM Modules

		JOIN StudentsToModules ON StudentsToModules.ModuleID = Modules.ModuleID
		WHERE StudentsToModules.UserID=?");
		
	$stmt->bind_param("s", $student);
	$stmt->execute();
	
	$rows = getRows($stmt);
	
	$lecturers = getStudentModuleLecturers($student);
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

function getStudentModuleLecturers($student)
{
	global $db;
	
	$stmt = $db->prepare("
		SELECT StudentsToModules.ModuleID AS ModuleID, Staff.UserID AS StaffID, Staff.Name as StaffName
		FROM Staff
		JOIN StaffToModules ON StaffToModules.UserID = Staff.UserID

		/* used just to filter to student */
		JOIN StudentsToModules ON StudentsToModules.ModuleID = StaffToModules.ModuleID
		WHERE StudentsToModules.UserID=?");
		

	$stmt->bind_param("s", $student);
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

function getPreparedQuestions($student, $answers=array()) {
	$questions = getQuestions();
	$modules = getStudentModules($student);
	
	foreach($modules as &$module) {
		
		$module["Questions"] = array();
		
		foreach($questions as $question) {
			$identifier = "{$module["ModuleID"]}_{$question["QuestionID"]}";
			if ($question["Staff"] == 0) {
				$question["Identifier"] = $identifier;
				if (in_array($identifier, $answers)) {
					$question["answer"] = $answers[$identifier];
				}
				
				$module["Questions"][] = $question;
			}
			else {
				foreach($module["Staff"] as $staff) {
					$staff_identifier = "{$identifier}_{$staff["StaffID"]}";
					$question["Identifier"] = $staff_identifier;
					$question["QuestionText"] = sprintf($question["QuestionText"], $staff["StaffName"]);
					if (in_array($staff_identifier, $answers)) {
						$question["answer"] = $answers[$staff_identifier];
					}
					
					$module["Questions"][] = $question;
				}
			}
		}
	}
	return $modules;
}
