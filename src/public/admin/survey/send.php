<?php

require_once('../header.php');

$survey_id = $_GET['id'];

function getStudents($survey_id) {
  $db = new Database();
  $db->query('SELECT token FROM students WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $survey_id);
  return $db->resultset();
}

function sendQuestionnaires($survey_id, $students) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO questionnaires (token, survey_id) VALUES (:token, :survey_id)');
  foreach ($students as $student) {
    $db->bind('token', $student['token']);
    $db->bind('survey_id', $survey_id);
    $db->execute();
  }
  $db->endTransaction();
}

sendQuestionnaires($survey_id, getStudents($survey_id));

header('Location: view?id='.$id);

require_once('../footer.php'); ?>
