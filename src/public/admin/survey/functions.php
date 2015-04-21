<?php

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
 * Return all participants in a survey, unless $completed parameter is present
 * If $completed = 1, return completed, if = 0, return incomplete.
 */
function getParticipants($id, $completed = null) {
  $db = new Database();
  if ($completed === null) {
    $db->query('SELECT * FROM Questionnaires WHERE survey_id = :survey_id');
  } else {
    $db->query('SELECT * FROM Questionnaires WHERE survey_id = :survey_id AND completed = :completed');
    $db->bind(':completed', $completed);
  }
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getStudents($id, $completed = null) {
  $db = new Database();
  if ($completed === null) {
    $db->query('SELECT * FROM Students WHERE survey_id = :survey_id; ');
  } else {
    $db->query('SELECT Students.token, Students.aber_id, Students.survey_id, Questionnaires.completed FROM Students INNER JOIN Questionnaires ON Students.token=Questionnaires.token WHERE Students.survey_id = :survey_id AND Questionnaires.completed = :completed; ');
    $db->bind(':completed', $completed);
  }
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getModuleStaff($id, $module_code) {
  $db = new Database();
  $db->query('SELECT * FROM StaffModules WHERE survey_id = :survey_id AND module_code = :module_code');
  $db->bind(':survey_id', $id);
  $db->bind(':module_code', $module_code);
  return $db->resultset();
}

function getModules($id) {
  $db = new Database();
  $db->query('SELECT * FROM Modules WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

// function getResults($id) {
//   $db = new Database();
//   $db->query('SELECT * FROM Answers WHERE survey_id = :survey_id');
//   $db->bind(':survey_id', $id);
//   return $db->resultset();
// }
