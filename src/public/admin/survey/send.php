<?php

require_once('../header.php');
require('../../../lib/sendMail.php');

function generateQuestionnaires($survey_id, $students) {
  $db = new Database();
  $db->query('INSERT INTO Questionnaires (token, survey_id) VALUES (:token, :survey_id) ON DUPLICATE KEY UPDATE token=token');
  foreach ($students as $student) {
    $db->bind('token', $student['token']);
    $db->bind('survey_id', $survey_id);
    $db->execute();
  }
}

function prepMail($survey_id, $students) {
  $survey = getSurvey($survey_id);

  foreach ($students as $student) {

    $reminderText = (isset($_GET['resend'])) ? '[REMINDER|NODYN ATGOFFA] ' : '' ;

    $toAddr = $student['aber_id'].'@'.Config::MAIL_DOMAIN;
    $subject = $reminderText.$survey['title_en'].' | '.$survey['title_cy'];

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
    $body .= Config::BASE_URL.'?token='.$student['token'].'&lang=en';
    $body .= "\r\n";
    $body .= "\r\n";
    $body .= "Please note that this survey can only be completed on the Aber network or through the Aber VPN.";
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
    $body .= Config::BASE_URL.'?token='.$student['token'].'&lang=cy';
    $body .= "\r\n";
    $body .= "\r\n";
    $body .= "Sylwer y gall yr arolwg hwn ond yn cael ei gwblhau ar y rhwydwaith Aber neu drwy'r Aber VPN.";

    sendMail($toAddr, $subject, $body);

  }
}

function lockSurvey($id) {
  $db = new Database();
  $db->query('UPDATE Surveys SET locked = :locked WHERE id = :id');
  $db->bind(':locked', 1);
  $db->bind(':id', $id);
  $db->execute();
}

if (isset($_GET['id'])) {
  $survey_id = $_GET['id'];
} else {
  die('Cannot send a survey without an ID.');
}

if (isset($_GET['resend'])) {
  $recipients = getStudents($survey_id, false);
  prepMail($survey_id, $recipients);
} else {
  $recipients = getStudents($survey_id);
  generateQuestionnaires($survey_id, $recipients);
  prepMail($survey_id, $recipients);
  lockSurvey($survey_id);
}

echo '<meta http-equiv="refresh" content="0; url=view.php?id='.$survey_id.'&msg=Survey has been sent to '.count($recipients).' recipients." />';

require_once('../footer.php'); ?>
