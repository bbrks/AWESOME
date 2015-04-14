<?php

$survey_id = $_GET['id'];
$modules = getModules($survey_id);

if (isset($_POST['submit'])) {

  $post_questions = $_POST['questions'];
  print_r($post_questions);
  $questions = array();

  for ($i=0; $i < count($post_questions["'text_en'"]); $i++) {
    $questions[$i]['module'] = $post_questions["'module'"][$i];
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

function getQuestions($id, $module = null, $fillEmpty = true) {
  $db = new Database();
  if ($module != null) {
    $db->query('SELECT * FROM questions WHERE survey_id = :survey_id AND module = :module');
    $db->bind(':module', $module);
  } else {
    $db->query('SELECT * FROM questions WHERE survey_id = :survey_id AND module IS NULL');
  }
  $db->bind(':survey_id', $id);
  $questions = $db->resultset();

  if ($fillEmpty == true && (count($questions) == 0)) {
    $questions[0]['text_en'] = '';
    $questions[0]['text_cy'] = '';
    $questions[0]['type'] = 'text';
  }

  return $questions;

}

function addQuestions($arr) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO questions (text_en, text_cy, type, survey_id, module) VALUES (:text_en, :text_cy, :type, :survey_id, :module)');
  foreach ($arr as $question) {
    if ($question['module'] == "NULL") {
      $question['module'] = null;
    }
    $db->bind(':text_en', $question['text_en']);
    $db->bind(':text_cy', $question['text_cy']);
    $db->bind(':module', $question['module']);
    $db->bind(':type', $question['type']);
    $db->bind(':survey_id', $_GET['id']);
    $db->execute();
  }
  return $db->endTransaction();
}

?>

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
      <?php $questions_global = getQuestions($survey_id, null); ?>
      <?php foreach ($questions_global as $question) { ?>
      <tr class="question-table-row">
        <input name="questions['module'][]" type="hidden" value="NULL" />
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" /></td>
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
      <?php $questions_repeated = getQuestions($survey_id, '0'); ?>
      <?php foreach ($questions_repeated as $question) { ?>
      <tr class="question-table-row">
        <input name="questions['module'][]" type="hidden" value="0" />
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" /></td>
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
      <?php foreach ($modules as $module) { ?>
        <?php $questions_module = getQuestions($survey_id, $module['module_code'], false); ?>
        <?php foreach ($questions_module as $question) { ?>
        <tr class="question-table-row">
          <td><select class="form-control" name="questions['module'][]">
            <?php foreach ($modules as $module) { ?>
            <?php $selected = ($module['module_code'] == $question['module']) ? ' selected' : ''; ?>
            <?php echo '<option value="'.htmlspecialchars($module['module_code']).'"'.$selected.'>'.htmlspecialchars($module['module_code']).' - '.htmlspecialchars($module['title']).'</option>'; ?>
            <?php } ?>
          </select></td>
          <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" /></td>
          <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" /></td>
          <td><select class="form-control" name="questions['type'][]" readonly><option value="text">Text</option></select></td>
          <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
        <?php } ?>
      <?php } ?>
      <tr class="question-table-row">
        <td><select class="form-control" name="questions['module'][]">
          <?php foreach ($modules as $module) { ?>
          <?php echo '<option value="'.htmlspecialchars($module['module_code']).'"'.$selected.'>'.htmlspecialchars($module['module_code']).' - '.htmlspecialchars($module['title']).'</option>'; ?>
          <?php } ?>
        </select></td>
        <td><input class="form-control" name="questions['text_en'][]" type="text" value="" /></td>
        <td><input class="form-control" name="questions['text_cy'][]" type="text" value="" /></td>
        <td><select class="form-control" name="questions['type'][]" readonly><option value="text">Text</option></select></td>
        <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
      </tr>
    </tbody>
  </table>

  <input type="submit" name="submit" class="btn btn-success" value="Save Questions" />
  <a class="btn btn-primary" href="send?id=<?php echo $survey_id; ?>">Send Survey</a>

</form>
