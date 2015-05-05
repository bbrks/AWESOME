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
    $db->query('SELECT * FROM Students WHERE survey_id = :survey_id ORDER BY aber_id;');
  } else {
    $db->query('SELECT Students.token, Students.aber_id, Students.survey_id, Questionnaires.completed FROM Students INNER JOIN Questionnaires ON Students.token=Questionnaires.token WHERE Students.survey_id = :survey_id AND Questionnaires.completed = :completed  ORDER BY aber_id;');
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

function getResults($id) {
  $db = new Database();
  $db->query('SELECT * FROM Answers WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getAnswers($question_id) {
  $db = new Database();
  $db->query('SELECT * FROM Answers WHERE question_id = :question_id');
  $db->bind(':question_id', $question_id);
  return $db->resultset();
}

function getLecturers($module_code, $survey_id) {
  $db = new Database();
  $db->query('SELECT StaffModules.aber_id, Staff.name FROM StaffModules INNER JOIN Staff ON StaffModules.aber_id=Staff.aber_id WHERE StaffModules.module_code = :module_code AND StaffModules.survey_id = :survey_id AND Staff.survey_id = :survey_id');
  $db->bind(':module_code', $module_code);
  $db->bind(':survey_id', $survey_id);
  return $db->resultset();
}

function replaceLecurer($question, $lecturer) {
  $lang = "en";

  $questionText = $question['text_'.$lang];
  $questionText = str_replace('{$lecturer}', $lecturer['name'], $questionText);

  return $questionText;
}

// Handles the looping to display modules, their questions and answers
function displayResults($survey_id) {

  $modules = getModules($survey_id);

  // Global Questions
  $questions_global = getQuestions($survey_id, null);
  if (count($questions_global) != 0) {
    echo '<div class="module well" style="display:none">';
    echo '<h3>Global Questions</h3>';

    foreach ($questions_global as $question) {
      echo '<div class="question">';
      echo '<h5>' . $question['text_en'] . ' | ' . $question['text_cy'] . '</h5>';
      echo displayAnswers($question);
      echo '</div>';
    }

    echo '</div>';
  }

  // Per-Module and Repeated Questions
  foreach ($modules as $module) {

    $lecturers = getLecturers($module['module_code'], $survey_id);
    $questions = array_merge(getQuestions($survey_id, '0'), getQuestions($survey_id, $module['module_code']));

    if (count($questions) != 0) {
      echo '<div class="module well" style="display:none">';
      echo '<h3>'.$module['module_code'].' - '.$module['title'].'</h3>';

      foreach ($questions as $question) {

        if (preg_match('/\{\$lecturer+\}/i', $question['text_en']) || preg_match('/\{\$lecturer+\}/i', $question['text_cy']) && count($lecturers) >= 1) {
          foreach ($lecturers as $lecturer) {
            echo '<div class="question">';
            echo '<h5>' . htmlspecialchars(str_replace('{$lecturer}', $lecturer['name'], $question['text_en'])) . ' | ' . htmlspecialchars(str_replace('{$lecturer}', $lecturer['name'], $question['text_cy'])) . '</h5>';
            echo displayAnswers($question, $module, $lecturer);
            echo '</div>';
          }
        } else {
          echo '<div class="question">';
            echo '<h5>' . htmlspecialchars(str_replace('{$lecturer}', 'the lecturer', $question['text_en'])) . ' | ' . htmlspecialchars(str_replace('{$lecturer}', 'the lecturer', $question['text_cy'])) . '</h5>';
          echo displayAnswers($question, $module);
          echo '</div>';
        }

      }

      echo '</div>';
    }

  }

}

// Handles the display of answers to a particular question
function displayAnswers($question, $module = null, $lecturer = null) {

  $module_code = ($module != null) ? $module['module_code'] : '' ;
  $lecturer_id = ($lecturer != null) ? $lecturer['aber_id'] : '' ;

  $question_id = $question['id'].$module_code.$lecturer_id;
  $answers = getAnswers($question_id);

  switch ($question['type']) {
    case 'range':
      $ans = (count($answers) >= 1) ? ' answers' : '' ;
      echo '<div class="google-chart" data-chart-type="likert" data-question-id="'.$question_id.'"></div>';
      break;
    default:
      echo (count($answers) >= 1) ? '<ul class="answers">' : '';
      foreach ($answers as $answer) {
        echo ($answer['answer'] != '') ? '<li>'.htmlspecialchars($answer['answer']).'</li>' : '';
      }
      echo (count($answers) >= 1) ? '</ul>' : '';
      break;
  }

}
