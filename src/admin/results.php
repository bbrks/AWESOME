<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}

include "lib-admin.php";

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
	<style>
		.table .progress {
			margin-bottom: 0px;
		}
	</style>
	
</head>

<body>
</body>
</html>
