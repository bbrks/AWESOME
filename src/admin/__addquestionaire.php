<?php
session_start();

if (!isset($_SESSION["admin_user"])) {
	header("location: login.php");
	exit("login ffs");
}
require "lib-admin.php";

$name = $_POST["questionaireName"];
$department = $_POST["questionaireDepartment"];

$stmt = $db->prepare("INSERT INTO Questionaires (QuestionaireName, QuestionaireDepartment) VALUES (?,?)");
$stmt->bind_param("ss", $name, $department);
$stmt->execute();
$stmt->close();

$answerID = $db->insert_id;
header("location: modify.php?questionaireID={$answerID}");
