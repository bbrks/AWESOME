<?php

require_once('../header.php');

if (isset($_GET['id'])) {
  $survey_id = $_GET['id'];
} else {
  die('Cannot send a survey without an ID.');
}

function sendQuestionnaires($survey_id, $students) {
  $db = new Database();
  $db->query('INSERT INTO Questionnaires (token, survey_id) VALUES (:token, :survey_id) ON DUPLICATE KEY UPDATE token=token');
  foreach ($students as $student) {
    $db->bind('token', $student['token']);
    $db->bind('survey_id', $survey_id);
    $db->execute();
    sendMail($survey_id, $student['aber_id'], $student['token']);
  }
}

function sendMail($survey_id, $aber_id, $token) {
  $survey = getSurvey($survey_id);
  $to = $aber_id.'@'.Config::MAIL_DOMAIN;
  $subject = 'An AWESOME Questionnaire is waiting to be completed.';
  $body = "A new AWESOME questionnaire is waiting to be completed.\r\nPlease visit the address below to complete.\r\n\r\n".Config::BASE_URL.'/questionnaires/view/'.$token;
  $headers = 'From: AWESOME <'.Config::MAIL_FROM_ADDR.'>';
  mail($to, $subject, $body, $headers);
}

function lockSurvey($id) {
  $db = new Database();
  $db->query('UPDATE Surveys SET locked = :locked WHERE id = :id');
  $db->bind(':locked', 1);
  $db->bind(':id', $id);
  $db->execute();
}

$recipients = getStudents($survey_id);

sendQuestionnaires($survey_id, $recipients);
lockSurvey($survey_id);

header('Location: view.php?id='.$survey_id.'&msg=Survey has been sent to '.count($recipients).' recipients.');

require_once('../footer.php'); ?>
