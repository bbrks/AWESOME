<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}

include "lib-admin.php";

$questionaireID = $_GET["questionaireID"];
$q = getQuestionaire($questionaireID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST["action"] == "modify_basic") {
		$q["QuestionaireName"] = $_POST["questionaireName"];
		$q["QuestionaireDepartment"] = $_POST["questionaireDepartment"];
		
		updateQuestionaire($questionaireID, $q);
	}
	elseif ($_POST["action"] == "csv_submit") {
		$data = parseCSV($_POST["csvdata"]);
		insertStudents($data, $questionaireID);
	}
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
				
				<div class="tab-pane active" id="basic">
					<form method="post">
						<input type="hidden" name="action" value="modify_basic" />
						
						<label for="questionaireName">Name</label>
						<input type="text" name="questionaireName" class="form-control" value="<?=$q["QuestionaireName"]?>"/>
						<label for="questionaireDepartment">Department</label>
						<select name="questionaireDepartment" class="form-control">
							<option value="Art" <?=$q["QuestionaireDepartment"]=="Art"?"selected":""?>>Art</option>
							<option value="IBERS" <?=$q["QuestionaireDepartment"]=="IBERS"?"selected":""?>>IBERS</option>
							<option value="CompSci" <?=$q["QuestionaireDepartment"]=="CompSci"?"selected":""?>>CompSci</option>
							<option value="Welsh" <?=$q["QuestionaireDepartment"]=="Welsh"?"selected":""?>>Welsh</option>
						</select>
						<button type="submit" class="btn btn-primary form-control">Modify Questionaire</button>
					</form>
				</div>
				
				<div class="tab-pane" id="students">
					<form method="post">
						<input type="hidden" name="action" value="csv_submit" />
						<textarea name="csvdata" class="form-control" rows="25"></textarea>
						<button type="submit" class="btn btn-primary form-control">Modify Questionaire</button>
					</form>
				
				</div>
				<div class="tab-pane" id="modules">Modules</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
