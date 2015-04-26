<?php

global $lang;
require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php');

if (isset($_GET['token'])) {

  // Validate the token string
  $token = isset($_GET['token']) ? $_GET['token'] : null;
  if (preg_match('/^[a-z0-9]{16}$/', $token) == 0) {
    $error = __('invalid-token');
  }

  $questionnaire = getQuestionnaire($token);
  $survey = getSurvey($questionnaire['survey_id']);
  $modules = getStudentModules($token, $questionnaire['survey_id']);

  $title = $survey['title_'.$lang];

  // If survey is completed, display an error
  if ($questionnaire['completed'] != 0) {
    $error = __('already-completed');
  } else {
    // If submitting answers, display message, else display questions.
    if (isset($_POST['submit'])) {
      $msg = __('answers-submitted');
    } else {
      $db = new Database();
      $db->query('SELECT * FROM Questions WHERE survey_id = :survey_id');
      $db->bind(':survey_id', $questionnaire['survey_id']);
      $questions = $db->resultSet();

      if ($db->rowCount() < 1) {
        $error = __('missing-questions');
      }
    }
  }

  require(ROOT.DS.'app'.DS.'views'.DS.'header.php');
  require(ROOT.DS.'app'.DS.'views'.DS.'questionnaires'.DS.'view.php');

} else {

  $error = __('invalid-url');
  require(ROOT.DS.'app'.DS.'views'.DS.'header.php');

}

require(ROOT.DS.'app'.DS.'views'.DS.'footer.php');
