<?php

require_once('../header.php');

$survey_id = $_GET['id'];
$questions = getQuestions($survey_id);
$modules = getModules($survey_id);

if (count($questions) == 0) {
  $questions[0]['text_en'] = '';
  $questions[0]['text_cy'] = '';
  $questions[0]['type'] = 'text';
}

if (isset($_POST['submit'])) {

  $post_questions = $_POST['questions'];
  $questions = array();

  for ($i=0; $i < count($post_questions["'text_en'"]); $i++) {
    $questions[$i]['text_en'] = $post_questions["'text_en'"][$i];
    $questions[$i]['text_cy'] = $post_questions["'text_cy'"][$i];
    $questions[$i]['type'] = $post_questions["'type'"][$i];
  }

  if (count($questions) != 0) {
    if (addQuestions($questions)) {
      $msg = 'Questions Added';
    } else {
      $err = '<strong>Error:</strong> Questions could not be added.';
    }
  } else {
    $err = '<strong>Error:</strong> No questions were entered.';
  }

}

function getModules($id) {
  $db = new Database();
  $db->query('SELECT * FROM modules WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getModuleQuestions($module_code, $id) {
  $db = new Database();
  $db->query('SELECT * FROM questions WHERE survey_id = :survey_id AND module = :module_code');
  $db->bind(':module_code', $module_code);
  $db->bind(':survey_id', $id);
  $questions = $db->resultset();
  if (count($questions) == 0) {
    $questions[0]['text_en'] = '';
    $questions[0]['text_cy'] = '';
    $questions[0]['type'] = 'text';
  }
  return $questions;
}

function getQuestions($id) {
  $db = new Database();
  $db->query('SELECT * FROM questions WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function addQuestions($arr) {
  $db = new Database();
  $db->query('DELETE FROM questions WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $_GET['id']);
  $db->execute();
  $db->beginTransaction();
  $db->query('INSERT INTO questions (text_en, text_cy, type, survey_id) VALUES (:text_en, :text_cy, :type, :survey_id)');
  foreach ($arr as $question) {
    $db->bind(':text_en', $question['text_en']);
    $db->bind(':text_cy', $question['text_cy']);
    $db->bind(':type', $question['type']);
    $db->bind(':survey_id', $_GET['id']);
    $db->execute();
  }
  return $db->endTransaction();
}

?>

<div class="page-header">
  <h1>Questions</h1>
  <p>Add survey, repeated and module-specific questions to the survey here.</p>
</div>

<form class="" role="form" method="post" action="">
  <h2>Survey Questions <span class="small">(Once per questionnaire)</span></h2>
  <table id="survey-question-table" class="table">
    <thead>
      <tr>
        <th>Question Text (en)</th>
        <th>Question Text (cy)</th>
        <th>Answer Type</th>
        <th width="1"><a onclick="addTableRow('#survey-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($questions as $question) { ?>
      <tr class="question-table-row">
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo $question['text_en']; ?>" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo $question['text_cy']; ?>" /></td>
        <td><select class="form-control" name="questions['type'][]" readonly><option value="text">Text</option></select></td>
        <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
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
        <th>Answer Type</th>
        <th width="1"><a onclick="addTableRow('#repeated-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($questions as $question) { ?>
      <tr class="question-table-row">
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo $question['text_en']; ?>" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo $question['text_cy']; ?>" /></td>
        <td><select class="form-control" name="questions['type'][]" readonly><option value="text">Text</option></select></td>
        <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
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
        <th>Answer Type</th>
        <th width="1"><a onclick="addTableRow('#module-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($questions as $question) { ?>
      <tr class="question-table-row">
        <td><select class="form-control" name="questions['module'][]">
          <?php foreach ($modules as $module) {

            $module_questions = getModuleQuestions($module['module_code'], $survey_id);
            echo '<option value="'.$module['module_code'].'">'.$module['module_code'].' - '.$module['title'].'</option>';

          } ?>
        </select></td>
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo $question['text_en']; ?>" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo $question['text_cy']; ?>" /></td>
        <td><select class="form-control" name="questions['type'][]" readonly><option value="text">Text</option></select></td>
        <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <input type="submit" name="submit" class="btn btn-success" value="Save Questions" />
  <a class="btn btn-primary" href="send?id=<?php echo $survey_id; ?>">Send Survey</a>

</form>

<?php require_once('../footer.php'); ?>
