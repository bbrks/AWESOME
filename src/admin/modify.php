<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}

include "lib-admin.php";

$questionaireID = $_GET["questionaireID"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST["action"] == "modify_basic") {
		$q = array();
		$q["QuestionaireName"] = $_POST["questionaireName"];
		$q["QuestionaireDepartment"] = $_POST["questionaireDepartment"];
		
		updateQuestionaire($questionaireID, $q);
	}
	elseif ($_POST["action"] == "students_csv_submit") {
		$data = parseStudentsCSV($_POST["csvdata"]);
		insertStudents($data, $questionaireID);
	}
	elseif ($_POST["action"] == "modules_csv_submit") {
		$data = parseModulesCSV($_POST["csvdata"]);
		insertModules($data, $questionaireID);
	}
	elseif ($_POST["action"] == "staff_csv_submit") {
		$data = parseStaffCSV($_POST["csvdata"]);
		insertStaff($data, $questionaireID);
	}
	elseif ($_POST["action"] == "staffmodules_csv_submit") {
		$data = parseStaffModulesCSV($_POST["csvdata"]);
		insertStaffModules($data, $questionaireID);
	}
}

$q = getQuestionaire($questionaireID);

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
	<style>
		@media (min-width: 992px){
			.leftcolumn {
				border-right: 1px dashed #333;
			}
		}
	</style>
</head>

<body>

<div class="container">
	<div class="row">
		<div class="col-md-offset-2">
			<h1>Modify Questionaire</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 leftcolumn">
			<ul class="nav nav-pills nav-stacked">
			  <li class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			  <li><a href="#students" role="tab" data-toggle="tab">Students</a></li>
			  <li><a href="#modules" role="tab" data-toggle="tab">Modules</a></li>
			  <li><a href="#staff" role="tab" data-toggle="tab">Staff</a></li>
			  <li><a href="#staffmodules" role="tab" data-toggle="tab">Staff Modules</a></li>
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
					<table class="table">
						<thead>
							<th>UserID</th>
							<th>Department</th>
							<th>Token</th>
						</thead>
						<?
$stmt = new tidy_sql($db, "
	SELECT Students.UserID, Students.Department, Students.Token, GROUP_CONCAT(DISTINCT ModuleID ORDER BY ModuleID ASC SEPARATOR ' ') AS Modules, Students.Done
	FROM Students
	JOIN StudentsToModules ON StudentsToModules.UserID=Students.UserID AND StudentsToModules.QuestionaireID=Students.QuestionaireID 
	WHERE Students.QuestionaireID=?
	GROUP BY Students.UserID
", "i");
$rows = $stmt->query($questionaireID);
foreach($rows as $row) { ?>
						<tr>
							<td><?=$row["UserID"]?></td>
							<td><?=$row["Department"]?></td>
							<td><a href="../questions.php?token=<?=$row["Token"]?>"><?=$row["Token"]?></a></td>
							<td><?=$row["Modules"]?></td>
						</tr>
<?}
						?>
						
						
					</table>
					
					
					<form method="post">
						<p>The system expects a CSV, with no header (very important) with the structure:<br/>
						Student UserID, Student Department, Module 1, Module 2, ..., Module &infin;</p>
						<input type="hidden" name="action" value="students_csv_submit" />
						<textarea name="csvdata" class="form-control" rows="25"></textarea>
						<button type="submit" class="btn btn-primary form-control">Add Students</button>
					</form>
				
				</div>
				
				<div class="tab-pane" id="modules">
						<form method="post">
						<p>The system expects a CSV, with no header (very important) with the structure:<br/>
						Module ID, Module Name</p>
						<input type="hidden" name="action" value="modules_csv_submit" />
						<textarea name="csvdata" class="form-control" rows="25"></textarea>
						<button type="submit" class="btn btn-primary form-control">Add Modules</button>
					</form>
				</div>
				
				<div class="tab-pane" id="staff">
						<form method="post">
						<p>The system expects a CSV, with no header (very important) with the structure:<br/>
						Staff UserID, Staff Name</p>
						<input type="hidden" name="action" value="staff_csv_submit" />
						<textarea name="csvdata" class="form-control" rows="25"></textarea>
						<button type="submit" class="btn btn-primary form-control">Add Staff</button>
					</form>
				</div>
				
				<div class="tab-pane" id="staffmodules">
						<form method="post">
						<p>The system expects a CSV, with no header (very important) with the structure:<br/>
						Module ID,Staff UserID<br/>
						This is repeated for every module</p>
						<input type="hidden" name="action" value="staffmodules_csv_submit" />
						<textarea name="csvdata" class="form-control" rows="25"></textarea>
						<button type="submit" class="btn btn-primary form-control">Add Staff/Module links</button>
					</form>
				</div>
				
			</div>
		</div>
	</div>
</div>
</body>
</html>
