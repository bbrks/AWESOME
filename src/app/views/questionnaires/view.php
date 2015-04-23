<?php

if (isset($survey)):

global $lang;

  if (isset($_POST['submit']) && $questionnaire['completed'] == 0) {

    function insertAnswers($token, $survey_id, $answers) {
      $db = new Database();
      $db->beginTransaction();
      $db->query('INSERT INTO Answers (question_id, answer, survey_id) VALUES (:question_id, :answer, :survey_id)');
      foreach ($answers as $id => $answer) {
        $db->bind(':survey_id', $survey_id);
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
  <h1><?php echo htmlspecialchars($survey['title_'.$lang]); ?></h1>
  <?php echo isset($survey['subtitle_'.$lang]) ? '<p>'.htmlspecialchars($survey['subtitle_'.$lang]).'</p>' : '' ; ?>
</div>

<?php if (isset($questions)) { ?>
  <form method="POST" action="">
  <?php
    foreach ($questions as $question) {
      if ($question['module'] === null) {
        echo '<div class="form-group">';
        echo '<label for="answer['.$question['id'].']">'.htmlspecialchars($question['text_'.$lang]).'</label>';
        echo '<input name="answer['.$question['id'].']" id="answer['.$question['id'].']"" type="'.$question['type'].'" class="form-control" />';
        echo '</div>';
      }
    }

    foreach ($modules as $module) {
      echo '<h3>'.$module['module_code'].' - '.$module['title'].'</h3>';
      foreach ($questions as $question) {
        if ($question['module'] === "0" || $question['module'] == $module['module_code']) {
          echo '<div class="form-group">';
          echo '<label for="answer['.$question['id'].']">'.htmlspecialchars($question['text_'.$lang]).'</label>';
          echo '<input name="answer['.$question['id'].']" id="answer['.$question['id'].']"" type="'.$question['type'].'" class="form-control" />';
          echo '</div>';
        }
      }
    }
  ?>
  <input type="submit" name="submit" id="submit" class="btn btn-primary" value="<?php echo __('send-responses') ?>" />
  </form>
<?php } ?>

<?php endif; ?>
