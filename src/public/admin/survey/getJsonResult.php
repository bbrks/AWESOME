<?php

header('Content-type: application/json');

set_include_path(dirname(dirname(dirname(dirname(__FILE__)))));

require_once('config/config.php');
require_once('lib/Database.php');

function getAnswers($question_id) {
  $db = new Database();
  $db->query('SELECT * FROM Answers WHERE question_id = :question_id');
  $db->bind(':question_id', $question_id);
  return $db->resultset();
}

if (isset($_GET['qid'])) {

  $answers = getAnswers($_GET['qid']);

  $answer1 = 0;
  $answer2 = 0;
  $answer3 = 0;
  $answer4 = 0;
  $answer5 = 0;

  foreach ($answers as $answer) {
    $answer1 += ($answer['answer'] == 1) ? 1 : 0 ;
    $answer2 += ($answer['answer'] == 2) ? 1 : 0 ;
    $answer3 += ($answer['answer'] == 3) ? 1 : 0 ;
    $answer4 += ($answer['answer'] == 4) ? 1 : 0 ;
    $answer5 += ($answer['answer'] == 5) ? 1 : 0 ;
  }

  echo '{
  "cols": [
        {"id":"","label":"Lecturer","pattern":"","type":"string"},
        {"id":"","label":"Strongly Disagree","pattern":"","type":"number"},
        {"id":"","label":"Disagree","pattern":"","type":"number"},
        {"id":"","label":"Neutral","pattern":"","type":"number"},
        {"id":"","label":"Agree","pattern":"","type":"number"},
        {"id":"","label":"Strongly Agree","pattern":"","type":"number"}
      ],
  "rows": [
        {"c":[{"v":"","f":null},{"v":'.$answer1.',"f":null},{"v":'.$answer2.',"f":null},{"v":'.$answer3.',"f":null},{"v":'.$answer4.',"f":null},{"v":'.$answer5.',"f":null}]},
      ]
}';

} ?>
