<?php

require_once('../header.php');

function getSurvey($id) {
  $db = new Database();
  $db->query('SELECT * FROM surveys WHERE id = :id');
  $db->bind(':id', $id);
  return $db->single();
}

function getQuestions($id) {
  $db = new Database();
  $db->query('SELECT * FROM questions WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getStudents($id) {
  $db = new Database();
  $db->query('SELECT * FROM students WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getModules($id) {
  $db = new Database();
  $db->query('SELECT * FROM modules WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

$survey = getSurvey($_GET['id']);

?>

<div class="page-header">
<h2><?php echo $survey['title'] ?> <span class="small"><?php echo $survey['subtitle'] ?></span>
<a href="delete?id=<?php echo $survey['id']; ?>" class="btn btn-danger pull-right">Delete Survey</a></h2>
</div>

<div role="tabpanel">

  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Questions</a></li>
    <li role="presentation"><a href="#students" aria-controls="students" role="tab" data-toggle="tab">Students</a></li>
    <li role="presentation"><a href="#modules" aria-controls="modules" role="tab" data-toggle="tab">Modules</a></li>
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="questions">
      <?php
        $questions = getQuestions($survey['id']);
      ?>
      <h2>Survey Questions <span class="small">(Once per questionnaire)</span></h2>
      <table id="survey-question-table" class="table">
        <thead>
          <tr>
            <th>Question Text (en)</th>
            <th>Question Text (cy)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $question) { ?>
          <tr class="question-table-row">
            <td><?php echo $question['text_en']; ?></td>
            <td><?php echo $question['text_cy']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>

      <h2>Repeated Questions <span class="small">(Repeated every module)</span></h2>
      <table id="repeated-question-table" class="table">
        <thead>
          <tr>
            <th>Question Text (en)</th>
            <th>Question Text (cy)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $question) { ?>
          <tr class="question-table-row">
            <td><?php echo $question['text_en']; ?></td>
            <td><?php echo $question['text_cy']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>

      <h2>Module Specific Questions <span class="small">(Ask a specific question)</span></h2>
      <table id="module-question-table" class="table">
        <thead>
          <tr>
            <th>Module</th>
            <th>Question Text (en)</th>
            <th>Question Text (cy)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $question) { ?>
          <tr class="question-table-row">
            <td><?php echo $question['module']; ?></td>
            <td><?php echo $question['text_en']; ?></td>
            <td><?php echo $question['text_cy']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="students">
      <h2>Students</h2>
      <ul>
      <?php
      $students = getStudents($survey['id']);
      foreach ($students as $student) {
        echo '<li>'.$student['token'].' - '.$student['aber_id'].'</li>';
      } ?>
      </ul>
    </div>
    <div role="tabpanel" class="tab-pane" id="modules">
      <h2>Modules</h2>
      <ul>
      <?php
      $modules = getModules($survey['id']);
      foreach ($modules as $module) {
        echo '<li>'.$module['module_code'].' - '.$module['title'].'</li>';
      } ?>
      </ul>
    </div>
  </div>

</div>

<?php require_once('../footer.php'); ?>
