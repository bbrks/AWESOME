<?php

function getQuestions($id, $module = null) {
  $db = new Database();
  if ($module != null) {
    $db->query('SELECT * FROM Questions WHERE survey_id = :survey_id AND module = :module');
    $db->bind(':module', $module);
  } else {
    $db->query('SELECT * FROM Questions WHERE survey_id = :survey_id AND module IS NULL');
  }
  $db->bind(':survey_id', $id);
  $questions = $db->resultset();

  return $questions;
}

// Takes an array of questions, and deletes ones not present with the same survey_id
function deleteQuestionsNotIn($arr) {
  $db = new Database();
  $db->query('DELETE FROM Questions WHERE id NOT IN ( '. implode(", ", $arr["'id'"]) .' ) AND survey_id = :survey_id');
  $db->bind(':survey_id', $_GET['id']);
  $db->execute();
}

function addQuestions($arr) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO Questions (id, text_en, text_cy, type, survey_id, module) VALUES (:id, :text_en, :text_cy, :type, :survey_id, :module) ON DUPLICATE KEY UPDATE text_en=VALUES(text_en), text_cy=VALUES(text_cy), type=VALUES(type), survey_id=VALUES(survey_id), module=VALUES(module)');
  foreach ($arr as $question) {
    if ($question['id'] == "") {
      $question['id'] = null;
    }
    if ($question['module'] == "") {
      $question['module'] = null;
    }
    $db->bind(':id', $question['id']);
    $db->bind(':text_en', $question['text_en']);
    $db->bind(':text_cy', $question['text_cy']);
    $db->bind(':module', $question['module']);
    $db->bind(':type', $question['type']);
    $db->bind(':survey_id', $_GET['id']);
    $db->execute();
  }
  return $db->endTransaction();
}

if (isset($_POST['submit'])) {

  if (isset($_POST['questions'])) {

    $post_questions = $_POST['questions'];
    $questions = array();

    deleteQuestionsNotIn($post_questions);

    for ($i=0; $i < count($post_questions["'id'"]); $i++) {
      $questions[$i]['id'] = $post_questions["'id'"][$i];
      $questions[$i]['module'] = $post_questions["'module'"][$i];
      $questions[$i]['text_en'] = $post_questions["'text_en'"][$i];
      $questions[$i]['text_cy'] = $post_questions["'text_cy'"][$i];
      $questions[$i]['type'] = $post_questions["'type'"][$i];
    }

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

?>

<form class="" role="form" method="post" action="">

  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="surveyQuestions">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapseSurveyQuestions" aria-expanded="true" aria-controls="collapseSurveyQuestions">
            Survey Questions <span class="small">(Once per questionnaire)</span>
          </a>
        </h4>
      </div>
      <div id="collapseSurveyQuestions" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="surveyQuestions">
        <div class="panel-body">
          <table id="survey-question-table" class="table">
            <thead>
              <tr>
                <th>Question Text (en)</th>
                <th>Question Text (cy)</th>
                <th>Answer Type</th>
                <?php if (!$survey['locked']) { ?>
                  <th width="1"><a onclick="addTableRow('#survey-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php $questions_global = getQuestions($survey['id'], null); ?>
              <?php if (count($questions_global) != 0) { ?>
                <?php foreach ($questions_global as $question) { ?>
                <tr class="question-table-row">
                  <input name="questions['id'][]" type="hidden" value="<?php echo htmlspecialchars($question['id']); ?>" />
                  <input name="questions['module'][]" type="hidden" value="" />
                  <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                  <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                  <td><select class="form-control" name="questions['type'][]" <?php echo ($survey['locked']) ? 'disabled' : ''; ?>>
                    <option value="text" <?php echo ($question['type'] === 'text') ? 'selected' : '' ; ?>>Text</option>
                    <option value="textarea" <?php echo ($question['type'] === 'textarea') ? 'selected' : '' ; ?>>Text Area</option>
                    <option value="range" <?php echo ($question['type'] === 'range') ? 'selected' : '' ; ?>>Likert Scale</option>
                  </select></td>
                  <?php if (!$survey['locked']) { ?>
                    <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                  <?php } ?>
                </tr>
                <?php } ?>
              <?php } else { ?>
                <tr class="question-table-row">
                  <input name="questions['id'][]" type="hidden" value="" />
                  <input name="questions['module'][]" type="hidden" value="" />
                  <td><input class="form-control" name="questions['text_en'][]" type="text" value="" /></td>
                  <td><input class="form-control" name="questions['text_cy'][]" type="text" value="" /></td>
                  <td><select class="form-control" name="questions['type'][]">
                    <option value="text">Text</option>
                    <option value="textarea">Text Area</option>
                    <option value="range">Likert Scale</option>
                  </select></td>
                  <?php if (!$survey['locked']) { ?>
                    <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                  <?php } ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="repeatedQuestions">
        <h4 class="panel-title">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRepeatedQuestions" aria-expanded="false" aria-controls="collapseRepeatedQuestions">
            Repeated Questions <span class="small">(Repeated every module)</span>
          </a>
        </h4>
      </div>
      <div id="collapseRepeatedQuestions" class="panel-collapse collapse" role="tabpanel" aria-labelledby="repeatedQuestions">
        <div class="panel-body">
          <p><code>{$lecturer}</code> will be automatically replaced with the module lecturer's names.</p>
          <table id="repeated-question-table" class="table">
            <thead>
              <tr>
                <th>Question Text (en)</th>
                <th>Question Text (cy)</th>
                <th>Answer Type</th>
                <?php if (!$survey['locked']) { ?>
                  <th width="1"><a onclick="addTableRow('#repeated-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php $questions_repeated = getQuestions($survey['id'], '0'); ?>
              <?php if (count($questions_repeated) != 0) { ?>
                <?php foreach ($questions_repeated as $question) { ?>
                <tr class="question-table-row">
                  <input name="questions['id'][]" type="hidden" value="<?php echo htmlspecialchars($question['id']); ?>" />
                  <input name="questions['module'][]" type="hidden" value="0" />
                  <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                  <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                  <td><select class="form-control" name="questions['type'][]" <?php echo ($survey['locked']) ? 'disabled' : ''; ?>>
                    <option value="text" <?php echo ($question['type'] === 'text') ? 'selected' : '' ; ?>>Text</option>
                    <option value="textarea" <?php echo ($question['type'] === 'textarea') ? 'selected' : '' ; ?>>Text Area</option>
                    <option value="range" <?php echo ($question['type'] === 'range') ? 'selected' : '' ; ?>>Likert Scale</option>
                  </select></td>
                  <?php if (!$survey['locked']) { ?>
                    <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                  <?php } ?>
                </tr>
                <?php } ?>
              <?php } else { ?>
                <tr class="question-table-row">
                  <input name="questions['id'][]" type="hidden" value="" />
                  <input name="questions['module'][]" type="hidden" value="0" />
                  <td><input class="form-control" name="questions['text_en'][]" type="text" value="" /></td>
                  <td><input class="form-control" name="questions['text_cy'][]" type="text" value="" /></td>
                  <td><select class="form-control" name="questions['type'][]">
                    <option value="text">Text</option>
                    <option value="textarea">Text Area</option>
                    <option value="range">Likert Scale</option>
                  </select></td>
                  <?php if (!$survey['locked']) { ?>
                    <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                  <?php } ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="moduleQuestions">
        <h4 class="panel-title">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseModuleQuestions" aria-expanded="false" aria-controls="collapseModuleQuestions">
            Module Specific Questions <span class="small">(Ask a specific question)</span>
          </a>
        </h4>
      </div>
      <div id="collapseModuleQuestions" class="panel-collapse collapse" role="tabpanel" aria-labelledby="moduleQuestions">
        <div class="panel-body">
          <p><code>{$lecturer}</code> will be automatically replaced with the module lecturer's names.</p>
          <table id="module-question-table" class="table">
            <thead>
              <tr>
                <th>Module</th>
                <th>Question Text (en)</th>
                <th>Question Text (cy)</th>
                <th>Answer Type</th>
                <?php if (!$survey['locked']) { ?>
                  <th width="1"><a onclick="addTableRow('#module-question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php $module_questions_count = 0; ?>
              <?php foreach ($modules as $module) { ?>
                <?php $questions_module = getQuestions($survey['id'], $module['module_code']); ?>
                <?php foreach ($questions_module as $question) { ?>
                  <?php $module_questions_count++; ?>
                  <tr class="question-table-row">
                    <td><select class="form-control" name="questions['module'][]" <?php echo ($survey['locked']) ? 'disabled' : ''; ?>>
                      <?php foreach ($modules as $module) { ?>
                        <?php $selected = ($module['module_code'] == $question['module']) ? ' selected' : ''; ?>
                        <?php echo '<option value="'.htmlspecialchars($module['module_code']).'"'.$selected.'>'.htmlspecialchars($module['module_code']).' - '.htmlspecialchars($module['title']).'</option>'; ?>
                      <?php } ?>
                    </select></td>
                    <input name="questions['id'][]" type="hidden" value="<?php echo htmlspecialchars($question['id']); ?>" />
                    <td><input class="form-control" name="questions['text_en'][]" type="text" value="<?php echo htmlspecialchars($question['text_en']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                    <td><input class="form-control" name="questions['text_cy'][]" type="text" value="<?php echo htmlspecialchars($question['text_cy']); ?>" <?php echo ($survey['locked']) ? 'disabled' : ''; ?> /></td>
                    <td><select class="form-control" name="questions['type'][]" <?php echo ($survey['locked']) ? 'disabled' : ''; ?>>
                      <option value="text" <?php echo ($question['type'] === 'text') ? 'selected' : '' ; ?>>Text</option>
                      <option value="textarea" <?php echo ($question['type'] === 'textarea') ? 'selected' : '' ; ?>>Text Area</option>
                      <option value="range" <?php echo ($question['type'] === 'range') ? 'selected' : '' ; ?>>Likert Scale</option>
                    </select></td>
                    <?php if (!$survey['locked']) { ?>
                      <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              <?php } ?>
              <?php if ($module_questions_count === 0) { ?>
                <tr class="question-table-row">
                  <td><select class="form-control" name="questions['module'][]">
                    <?php foreach ($modules as $module) { ?>
                      <?php echo '<option value="'.htmlspecialchars($module['module_code']).'">'.htmlspecialchars($module['module_code']).' - '.htmlspecialchars($module['title']).'</option>'; ?>
                    <?php } ?>
                  </select></td>
                  <input name="questions['id'][]" type="hidden" value="" />
                  <td><input class="form-control" name="questions['text_en'][]" type="text" value="" /></td>
                  <td><input class="form-control" name="questions['text_cy'][]" type="text" value="" /></td>
                  <td><select class="form-control" name="questions['type'][]">
                    <option value="text">Text</option>
                    <option value="textarea">Text Area</option>
                    <option value="range">Likert Scale</option>
                  </select></td>
                  <?php if (!$survey['locked']) { ?>
                    <td><a onclick="removeTableRow(this)" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Question"><span class="glyphicon glyphicon-trash"></span></a></td>
                  <?php } ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <?php if (!$survey['locked']) { ?>
    <input type="submit" name="submit" class="btn btn-success" value="Save Questions" />
  <?php } ?>

</form>
