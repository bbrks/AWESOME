<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">

	<link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container">

<?
require("lib.php");

function print_question($question, $warn=false) {
	global $is_welsh;
	echo "<div";
	if ($warn == true && $question["Answer"] == "") {
		echo " style=\"border: 5px solid red;\"";
	}
	echo ">";
	
	if ($is_welsh) {
		echo "<h4>{$question["QuestionText_welsh"]}</h4>";
	}
	else
	{
		echo "<h4>{$question["QuestionText"]}</h4>";
	}
	

	if ($question["Type"] == "rate") {
		echo "<hr>
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
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"1\" ". ($question["Answer"]==1?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"2\" ". ($question["Answer"]==2?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"3\" ". ($question["Answer"]==3?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"4\" ". ($question["Answer"]==4?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" value=\"5\" ". ($question["Answer"]==5?"checked=\"true\"":"") ."></td>
				<td>Strongly Agree</td>
			</tr>
		</table>
		<hr>";
	}
	elseif ($question["Type"] == "text") {
		echo "<textarea name=\"{$question["Identifier"]}\" rows=\"8\" cols=\"50\">{$question["Answer"]}</textarea>";
	}
	echo "</div>";
}

function print_form($modules, $warn=false) {
	echo "<form method=\"POST\">";
	foreach($modules as $module) {
		echo "<h3>{$module["ModuleID"]}: {$module["ModuleTitle"]}</h3>";
		foreach($module["Questions"] as $question) {
			print_question($question, $warn);
		}
	}
	echo "<input type=\"submit\" value=\"Submit survey!\" /></form>";
}
$token = $_GET["token"];
$is_welsh = isset($_GET["welsh"]);

$details = getStudentDetails($token);

$user = $details["UserID"];
$modules = getPreparedQuestions($details, $_POST);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	print_form($modules);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//validate that everything is filled in
	if (!answers_filled($modules)) { //form is not filled :(
		print_form($modules, true);
		return;
	}
	else {
		answers_submit($details, $modules);

	}

}

?>

</div>
</body>

</html>
