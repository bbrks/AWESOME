<pre><?

require("lib.php");

function print_question($identifier, $question_text, $type) {
	echo "<h3>{$question_text}</h3>";
	echo $identifier;
}

$questions = getQuestions();
$modules = getStudentModules('keo7');

foreach($modules as $module) {
	echo "<h2>{$module["ModuleTitle"]}</h2>";
	foreach($questions as $question) {
		$identifier = "{$module["ModuleID"]}_{$question["QuestionID"]}";
		if ($question["Staff"] == 0) {
			print_question($identifier, $question["QuestionText"], "rate");
		}
		else {
			foreach($module["Staff"] as $staff) {
				$staff_identifier = "{$identifier}_{$staff["StaffID"]}";
				print_question($staff_identifier, sprintf($question["QuestionText"], $staff["StaffName"]), "rate");
			}
		}
	}
}

?></pre>
