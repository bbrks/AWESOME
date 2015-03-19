<?php if (isset($survey)):

  if (isset($_POST['submit'])) {

    function insertAnswers($token, $survey_id, $answers) {
      $db = new Database();
      $db->beginTransaction();
      $db->query('INSERT INTO Answers (token, question_id, answer) VALUES (:token, :question_id, :answer)');
      foreach ($answers as $id => $answer) {
        $db->bind(':token', $token);
        $db->bind(':question_id', $id);
        $db->bind(':answer', $answer);
        $db->execute();
      }
      $db->query('UPDATE Questionnaires SET completed=1 WHERE token = :token');
      $db->bind(':token', $token);
      $db->execute();
      return $db->endTransaction();
    }

    if (insertAnswers($token, $survey['id'], $_POST['answer']) == true) {
      header("Refresh:0");
    }

  } ?>

<div class="page-header">
  <h1><?php echo htmlspecialchars($title); ?></h1>
  <?php echo isset($subtitle) ? '<p>'.htmlspecialchars($subtitle).'</p>' : '' ; ?>
</div>

<form method="POST" action="">
<?php
  foreach ($questions as $question) {
    echo '<div class="form-group">';
    echo '<label for="answer['.$question['id'].']">'.htmlspecialchars($question['text_en']).'<br/>'.htmlspecialchars($question['text_cy']).'</label>';
    echo '<input name="answer['.$question['id'].']" id="answer['.$question['id'].']"" type="'.$question['type'].'" class="form-control" />';
    echo '</div>';
  }
?>
<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Send Responses" />
</form>

<?php endif; ?>
