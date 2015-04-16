<?php if (isset($survey)):

global $lang;

  if (isset($_POST['submit']) && $questionnaire['completed'] == 0) {

    function insertAnswers($token, $survey_id, $answers) {
      $db = new Database();
      $db->beginTransaction();
      $db->query('INSERT INTO Answers (question_id, answer) VALUES (:question_id, :answer)');
      foreach ($answers as $id => $answer) {
        $db->bind(':question_id', $id);
        $db->bind(':answer', $answer);
        $db->execute();
      }
      $db->query('UPDATE Questionnaires SET completed=1 WHERE token = :token');
      $db->bind(':token', $token);
      $db->execute();
      return $db->endTransaction();
    }

    insertAnswers($token, $survey['id'], $_POST['answer']);

  } ?>

<div class="page-header">
  <h1><?php echo htmlspecialchars($title); ?></h1>
  <?php echo isset($subtitle) ? '<p>'.htmlspecialchars($subtitle).'</p>' : '' ; ?>
</div>

<?php if (isset($questions)) { ?>
  <form method="POST" action="">
  <?php
    foreach ($questions as $question) {
      if ($question['module'] === "0") {
        echo 'Repeated';
      }
      echo '<div class="form-group">';
      echo '<label for="answer['.$question['id'].']">'.htmlspecialchars($question['text_'.$lang]).'</label>';
      echo '<input name="answer['.$question['id'].']" id="answer['.$question['id'].']"" type="'.$question['type'].'" class="form-control" />';
      echo '</div>';
    }
  ?>
  <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Send Responses" />
  </form>
<?php } ?>

<?php endif; ?>
