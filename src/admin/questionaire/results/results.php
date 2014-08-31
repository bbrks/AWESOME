<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}
require "../../../lib.php";
include "../../lib-admin.php";

$questionaireID = $_GET["questionaireID"];

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script src="../js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../js/Chart.min.js" type="text/javascript"></script>
	<style>
		.table .progress {
			margin-bottom: 0px;
		}
	</style>
	
</head>

<body>
<?
	$stmt = new tidy_sql($db, "
		SELECT Modules.ModuleID,Modules.ModuleTitle,COUNT(DISTINCT AnswerGroup.AnswerID) as NumAnswers FROM Modules
		JOIN StudentsToModules ON StudentsToModules.ModuleID = Modules.ModuleID AND StudentsToModules.QuestionaireID = Modules.QuestionaireID
		LEFT JOIN Answers ON Answers.ModuleID = Modules.ModuleID
		LEFT JOIN AnswerGroup ON AnswerGroup.QuestionaireID = Modules.QuestionaireID
		WHERE Modules.QuestionaireID=?
		GROUP BY Modules.ModuleID
		", "i");
	$rows = $stmt->query($questionaireID);
	?>
	<table class="table">
		<thead>
			<td>Module ID</td>
			<th>Module Title</th>
			<th>Num Answers</th>
		</thead>
	<?
	foreach($rows as $row) {
		?>
		<tr>
			<td><?=$row["ModuleID"]?></td>
			<td><a href="moduleresults.php?questionaireID=<?=$questionaireID?>&moduleID=<?=$row["ModuleID"]?>"><?=$row["ModuleTitle"]?></a></td>
			<td><?=$row["NumAnswers"]?></td>
		</tr>
		<?
	}
	?>
	</table>
</body>
</html>
