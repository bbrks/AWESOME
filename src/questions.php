<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/prettyCheckable.css" rel="stylesheet">
	
	<style>
		.ratetable {
			border-spacing: 5px 0px;
			border-collapse: separate;
		}
		
		.prettyradio {
			text-align: center;
		}
		
		.prettyradio a {
			float: none;
			margin: auto;
		}
		
		.prettyradio label {
			float: none;
			
			text-align: center;
			width: 100%;
			margin: auto;
		}
			
		img {
			padding-top:10px;
			display:block;
			margin:0 auto 0;
			padding-bottom:10px;
}
		
		#addedfooter {
	padding-top:10px;
	text-align:right;
}

#addedfooter a {
	color:inherit;
}

.language {
	float:right;
}
	</style>
	
	<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/prettyCheckable.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function() {
			$(".ratetable input[type=radio]").each(function(e) {
					$(this).prettyCheckable({
					"customClass": "prettyrate",
					"labelPosition": "left"
				});
			});
		})
	</script>
</head>


<body>

<div class="container">

<div class="language">
<p><a href="questions.php?token=<?=$_GET["token"]?>">English</a> | <a href="questions.php?token=<?=$_GET["token"]?>&welsh">Cymraeg</a></p>
</div>
<img src="img/logo.png" width="40%">

<p>This is a survey that's aimed at you. </p>

<p>Once you press submit, your answers come back to us with no identifying information, and your unique link will stop working.</p>

<p>The results are completely anonymous, so be as honest as you can!</p>

<hr>

<?
require("lib.php");

function print_question($question, $warn=false) {
	global $is_welsh;
	echo "<hr> <div";
	if ($warn == true && !answer_filled($question)) {
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
		echo "
		<table class=\"ratetable\">
			<thead>
				<th></th>
				<th><label for=\"{$question["Identifier"]}_1\">1</label></th>
				<th><label for=\"{$question["Identifier"]}_2\">2</label></th>
				<th><label for=\"{$question["Identifier"]}_3\">3</label></th>
				<th><label for=\"{$question["Identifier"]}_4\">4</label></th>
				<th><label for=\"{$question["Identifier"]}_5\">5</label></th>
				<th></th>
			</thead>
			<tr>
				<td>Strongly Disagree</td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" id=\"{$question["Identifier"]}_1\" value=\"1\" ". ($question["Answer"]==1?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" id=\"{$question["Identifier"]}_2\" value=\"2\" ". ($question["Answer"]==2?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" id=\"{$question["Identifier"]}_3\" value=\"3\" ". ($question["Answer"]==3?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" id=\"{$question["Identifier"]}_4\" value=\"4\" ". ($question["Answer"]==4?"checked=\"true\"":"") ."></td>
				<td><input type=\"radio\" name=\"{$question["Identifier"]}\" id=\"{$question["Identifier"]}_5\" value=\"5\" ". ($question["Answer"]==5?"checked=\"true\"":"") ."></td>
				<td>Strongly Agree</td>
			</tr>
		</table>
		";
	}
	elseif ($question["Type"] == "text") {
		echo "<textarea name=\"{$question["Identifier"]}\" rows=\"8\" cols=\"50\" class=\"form-control\">{$question["Answer"]}</textarea>";
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
	//echo "<input type=\"submit\" value=\"Submit survey!\" /></form>";
	echo "<br><div class=\"pull-right\"><button type=\"submit\" class=\"btn btn-lg btn-success\" id=\"submit\"\">Submit Survey</button></div>";
}
$token = $_GET["token"];
$is_welsh = isset($_GET["welsh"]);

$details = getStudentDetails($token);

if ($details["Done"]) {
	?>
	<h1>You have already done this questionnaire.</h1>
	<?
}
else {
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
			
			$stmt = new tidy_sql($db, "UPDATE Students SET Done=1 WHERE UserID=? AND QuestionaireID=?","si");
			$stmt->query($details["UserID"], $details["QuestionaireID"]);
			?>
			<h1>Thank you for completing this questionnaire!</h1>
			<?
		}

	}
}

?>
			<br>
			<br>
			
		<div id="addedfooter">
		<div class="container">
			<a href="http://www.aberawesome.co.uk"><p>&copy; The AWESOME Project 2014</p></a>
		</div>
	</div>

</div>
</body>

</html>
