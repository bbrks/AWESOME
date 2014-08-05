<pre><?

require("lib.php");

function print_question($module, $question_text, $type) {
	echo "<h3>{$question_text}</h3>";
}

$questions = getQuestions();
$modules = getStudentModules('keo7');

foreach($modules as $module) {
	echo "<h2>{$module["ModuleTitle"]}</h2>";
	foreach($questions as $question) {
		if ($question["Staff"] == 0) {
			print_question($module, $question["QuestionText"], "rate");
		}
		else {
			foreach($module["Staff"] as $staff) {
				print_question($module, sprintf($question["QuestionText"], $staff["StaffName"]), "rate");
			}
		}
	}
}

?></pre>
