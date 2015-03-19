<?php

require_once('../header.php');

$survey_id = $_GET['id'];

function getStudents($survey_id) {
  $db = new Database();
  $db->query('SELECT * FROM students WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $survey_id);
  return $db->resultset();
}

function sendQuestionnaires($survey_id, $students) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO questionnaires (token, survey_id) VALUES (:token, :survey_id) ON DUPLICATE KEY UPDATE token=token');
  foreach ($students as $student) {
    $db->bind('token', $student['token']);
    $db->bind('survey_id', $survey_id);
    $db->execute();
    sendMail($student['aber_id'], $student['token']);
  }
  $db->endTransaction();
}

function sendMail($aber_id, $token) {
  mail($aber_id.'@'.Config::MAIL_DOMAIN, 'An AWESOME Questionnaire is waiting to be completed.', "A new AWESOME questionnaire is waiting to be completed.\r\nPlease visit the address below to complete.\r\n\r\n".Config::BASE_URL.'/questionnaires/view/'.$token, 'From: AWESOME <'.Config::MAIL_FROM_ADDR.'>');
}

sendQuestionnaires($survey_id, getStudents($survey_id));

header('Location: view?id='.$survey_id);

require_once('../footer.php'); ?>
