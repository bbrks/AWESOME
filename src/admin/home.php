<!DOCTYPE HTML>
<html>
<head>
	<title>Questionnaire</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">

	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script src="../js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js" type="text/javascript"></script>
	
	
</head>

<body>

<div class="container">

<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}
?>

<div class="modal fade" id="modal">
  <div class="modal-dialog">
    <div class="modal-content">
	  <form method="post" action="__addquestionaire.php">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">Add Questionaire</h4>
		  </div>
		  <div class="modal-body">
			<p>To begin with, just fill in the name, and department</p>
			<label for="questionaireName">Name</label>
			<input type="text" name="questionaireName" class="form-control" />
			<label for="questionaireDepartment">Department</label>
			<select name="questionaireDepartment" class="form-control">
				<option value="Art">Art</option>
				<option value="IBERS">IBERS</option>
				<option value="CompSci">CompSci</option>
				<option value="Welsh">Walsh</option>
			</select>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Add Questionaire</button>
		  </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<? //TABLE
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
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">Add</button></td>
	</thead>
	<?
	
foreach($questionaires as $questionaire) {
	$percent = $questionaire["Answers"]==0?0:($questionaire["Total"]/$questionaire["Answers"])*100;
	?>
	<tr>
		<td><?=$questionaire["QuestionaireID"]?></td>
		<td><?=$questionaire["QuestionaireName"]?></td>
		<td><?=$questionaire["QuestionaireDepartment"]?></td>
		<td><?=$questionaire["Answers"]?></td>
		<td><?=$questionaire["Total"]?></td>
		<td><div class="progress-bar" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><?=$percent?>%</div></td>
		<td><a class="btn btn-default btn-xs" href="modify.php?questionaireID=<?=$questionaire["QuestionaireID"]?>">Modify</a> <a class="btn btn-default btn-xs" href="stats.php?questionaireID=<?=$questionaire["QuestionaireID"]?>">Stats</a></td>
	</tr>
	<?
}
	
	?>
</table>

</body>
</html>
