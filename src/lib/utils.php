<?php

/**
 * Define a few useful constants
 * @const DS Alias for DIRECTORY_SEPARATOR
 * @const ROOT Relative path to src
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

/**
 * Function to add or update get parameters in the URL
 * http://stackoverflow.com/a/28645254
 */
function addOrUpdateUrlParam($name, $value) {
  $params = $_GET;
  unset($params[$name]);
  $params[$name] = $value;
  return basename($_SERVER['PHP_SELF']).'?'.http_build_query($params);
}

/**
 * Render the page title with a prefix if parameter is present
 * @param $title
 */
function title($title = null) {
  if ($title) {
    echo $title.' - '. __('app_title');
  } else {
    echo __('app_title');
  }
}

/**
 * Return a survey given the ID, if null, return all.
 */
function getSurvey($id = null) {
  $db = new Database();
  if ($id === null) {
    $db->query('SELECT * FROM Surveys');
    $result = $db->resultset();
  } else {
    $db->query('SELECT * FROM Surveys WHERE id = :id');
    $db->bind(':id', $id);
    $result = $db->single();
  }
  return $result;
}


/**
 * Return a questionnaire given the token
 */
function getQuestionnaire($token) {
  $db = new Database();
  $db->query('SELECT * FROM Questionnaires WHERE token = :token');
  $db->bind(':token', $token);
  $result = $db->single();
  return $result;
}

function getStudentModules($token, $survey_id) {
  $db = new Database();
  $db->query('SELECT StudentModules.module_code, Modules.title FROM StudentModules INNER JOIN Modules ON StudentModules.module_code=Modules.module_code AND StudentModules.survey_id=Modules.survey_id WHERE StudentModules.token = :token AND StudentModules.survey_id = :survey_id');
  $db->bind(':token', $token);
  $db->bind(':survey_id', $survey_id);
  return $db->resultset();
}

function getLecturers($module_code, $survey_id) {
  $db = new Database();
  $db->query('SELECT StaffModules.aber_id, Staff.name FROM StaffModules INNER JOIN Staff ON StaffModules.aber_id=Staff.aber_id WHERE StaffModules.module_code = :module_code AND StaffModules.survey_id = :survey_id');
  $db->bind(':module_code', $module_code);
  $db->bind(':survey_id', $survey_id);
  return $db->resultset();
}

function replaceLecurer($question, $lecturer) {
  global $lang;

  $questionText = $question['text_'.$lang];
  $questionText = str_replace('{$lecturer}', $lecturer['name'], $questionText);

  return $questionText;
}
