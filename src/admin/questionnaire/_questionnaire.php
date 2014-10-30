<?php

/**
 * @param int $questionnaireID The questionnaire ID
 * 
 * @returns The questionnaire database record.
 */
function getQuestionaire($questionnaireID) {
	global $db;

	$stmt = new tidy_sql($db, "
		SELECT * FROM Questionaires WHERE QuestionaireID=?", "i");

	$rows = $stmt->query($questionnaireID);
	
	return $rows[0];
}