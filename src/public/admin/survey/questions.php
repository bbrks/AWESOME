<?php

require_once('../header.php');

$survey_id = $_GET['id'];
$questions = getQuestions($survey_id);

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
  <p>Add global and module-specific questions to the survey here.</p>
</div>

<h2>Global Questions</h2>

<form class="" role="form" method="post" action="">
  <table id="question-table" class="table">
    <thead>
      <tr>
        <th>Question Text (en)</th>
        <th>Question Text (cy)</th>
        <th>Answer Type</th>
        <th width="1"><a onclick="addTableRow('#question-table')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Add Question"><span class="glyphicon glyphicon-plus"></span></a></th>
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

  <input type="submit" name="submit" class="btn btn-primary" value="Save Questions" />

</form>

<?php require_once('../footer.php'); ?>
