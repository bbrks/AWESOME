<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">

	<link href="../css/bootstrap.min.css" rel="stylesheet">
	
</head>

<body>

<div class="container">

<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}

require "lib-admin.php";
$questionaires = getQuestionaires();
?>
<h1>Questionaires</h1>
<table class="table table-striped">
	<thead>
		<th>ID</th>
		<th>Name</th>
		<th>Department</th>
		<th>Answers</th>
		<th>Total Students</th>
		<th class="col-sm-4">Percent</th>
		<td><button type="button" class="btn btn-primary ">Add</button></td>
	</thead>
	<?
	
foreach($questionaires as $questionaire) {
	$percent = ($questionaire["Total"]/$questionaire["Answers"])*100;
	?>
	<tr>
		<td><?=$questionaire["QuestionaireID"]?></td>
		<td><?=$questionaire["QuestionaireName"]?></td>
		<td><?=$questionaire["QuestionaireDepartment"]?></td>
		<td><?=$questionaire["Answers"]?></td>
		<td><?=$questionaire["Total"]?></td>
		<td><div class="progress-bar" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><?=$percent?>%</div></td>
		<td><button type="button" class="btn btn-default btn-xs">Modify</button> <button type="button" class="btn btn-default btn-xs">Stats</button></td>
	</tr>
	<?
}
	
	?>
</table>
