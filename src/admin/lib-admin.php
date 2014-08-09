<?php
require "../lib.php";

function getQuestionaires() {
	global $db;

	$stmt = $db->prepare("
		SELECT *, (
				SELECT COUNT(*)
				FROM AnswerGroup
				WHERE Questionaires.QuestionaireID = AnswerGroup.QuestionaireID
			) AS Answers,
			(
				SELECT COUNT(*)
				FROM StudentsToModules
				WHERE Questionaires.QuestionaireID = StudentsToModules.QuestionaireID
			) AS Total
		FROM Questionaires	
	");
	$stmt->execute();
	$rows = getRows($stmt);
	return $rows;
}
