<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}
?>
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
	<div class="row">
		<div class="col-md-offset-2">
			<h1>Modify Questionaire</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="border-right: 1px dashed #333;">
			<ul class="nav nav-pills nav-stacked">
			  <li class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			  <li><a href="#students" role="tab" data-toggle="tab">Students</a></li>
			  <li><a href="#modules" role="tab" data-toggle="tab">Modules</a></li>
			</ul>
		</div>
		<div class="col-md-10">
			<div class="tab-content">
				<div class="tab-pane active" id="basic">Basic</div>
				<div class="tab-pane" id="students">Students</div>
				<div class="tab-pane" id="modules">Modules</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
