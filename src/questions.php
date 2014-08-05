<?

require("lib.php");

function print_question($identifier, $question_text, $type) {
	echo "<h3>{$question_text}</h3>";

	if ($type == "rate") {
		echo "
		<table>
			<thead>
				<th></th>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5</th>
				<th></th>
			</thead>
			<tr>
				<td>Strongly Disagree</td>
				<td><input type=\"radio\" name=\"{$identifier}\" value=\"1\"></td>
				<td><input type=\"radio\" name=\"{$identifier}\" value=\"2\"></td>
				<td><input type=\"radio\" name=\"{$identifier}\" value=\"3\"></td>
				<td><input type=\"radio\" name=\"{$identifier}\" value=\"4\"></td>
				<td><input type=\"radio\" name=\"{$identifier}\" value=\"5\"></td>
				<td>Strongly Agree</td>
			</tr>
		</table>";
	}
	elseif ($type == "text") {
		echo "<textarea name=\"{$identifier}\" rows=\"8\" cols=\"0\"></textarea>";
	}
}

$questions = getQuestions();
$modules = getStudentModules('keo7');

foreach($modules as $module) {
	echo "<h2>{$module["ModuleID"]}: {$module["ModuleTitle"]}</h2>";
	foreach($questions as $question) {
		$identifier = "{$module["ModuleID"]}_{$question["QuestionID"]}";
		if ($question["Staff"] == 0) {
			print_question($identifier, $question["QuestionText"], $question["Type"]);
		}
		else {
			foreach($module["Staff"] as $staff) {
				$staff_identifier = "{$identifier}_{$staff["StaffID"]}";
				print_question($staff_identifier, sprintf($question["QuestionText"], $staff["StaffName"]), $question["Type"]);
			}
		}
	}
}

?>
