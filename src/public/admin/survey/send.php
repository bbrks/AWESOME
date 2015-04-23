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
  $headers = 'From: AWESOME <'.Config::MAIL_FROM_ADDR.'>';
  $subject = $survey['title_en'].' | '.$survey['title_cy'];

  $body  = "Your personalised questionnaire is waiting to be completed.";
  $body .= "\r\n";
  $body .= "Please visit the link below to complete.";
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= $survey['title_en'];
  $body .= "\r\n";
  $body .= $survey['subtitle_en'];
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= Config::BASE_URL.'?token='.$token.'&lang=en';
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= "-----------------------------------------";
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= "Eich holiadur personol yn aros i gael ei gwblhau.";
  $body .= "\r\n";
  $body .= "Ewch i'r ddolen isod i gwblhau.";
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= $survey['title_cy'];
  $body .= "\r\n";
  $body .= $survey['subtitle_cy'];
  $body .= "\r\n";
  $body .= "\r\n";
  $body .= Config::BASE_URL.'?token='.$token.'&lang=cy';

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
