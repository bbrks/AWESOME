<?

require("lib.php");

function print_question($question) {
	echo "<h3>{$question["QuestionText"]}</h3>";

	if ($question["Type"] == "rate") {
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
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"1\"></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"2\"></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"3\"></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"4\"></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"5\"></td>
				<td>Strongly Agree</td>
			</tr>
		</table>";
	}
	elseif ($question["Type"] == "text") {
		echo "<textarea name=\"{$question["Identifier"]}\" rows=\"8\" cols=\"0\"></textarea>";
	}
}

$user = $_GET["user"];

$modules = getPreparedQuestions($user);

foreach($modules as $module) {
	echo "<h2>{$module["ModuleID"]}: {$module["ModuleTitle"]}</h2>";
	foreach($module["Questions"] as $question) {
		print_question($question);
	}
}

?>
