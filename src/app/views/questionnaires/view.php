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
  <h1>
    <?php echo htmlspecialchars($survey['title_'.$lang]); ?>
    <?php echo isset($survey['subtitle_'.$lang]) ? '<br /><span class="small">'.htmlspecialchars($survey['subtitle_'.$lang]).'</span>' : '' ; ?>
  </h1>
</div>

<?php if (isset($questions)) { ?>
  <form method="POST" action="">
  <?php

    // Global Questions
    foreach ($questions as $question) {
      if ($question['module'] === null) {
        echo '<div class="form-group">';
        echo '<label for="answer['.$question['id'].']">'.htmlspecialchars($question['text_'.$lang]).'</label>';
        switch ($question['type']) {
          case 'range': ?>
            <div class="form-group" style="margin-left:35px">
              <label class="radio-inline">
                <input type="radio" name="answer[<?php echo $question['id'] ?>]" id="answer[<?php echo $question['id'] ?>]" value="1">
                <?php echo __('strongly-disagree'); ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="answer[<?php echo $question['id'] ?>]" id="answer[<?php echo $question['id'] ?>]" value="2">
                <?php echo __('disagree'); ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="answer[<?php echo $question['id'] ?>]" id="answer[<?php echo $question['id'] ?>]" value="3">
                <?php echo __('neutral'); ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="answer[<?php echo $question['id'] ?>]" id="answer[<?php echo $question['id'] ?>]" value="4">
                <?php echo __('agree'); ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="answer[<?php echo $question['id'] ?>]" id="answer[<?php echo $question['id'] ?>]" value="5">
                <?php echo __('strongly-agree'); ?>
              </label>
            </div>
            <?php break;
          case 'textarea':
            echo '<textarea name="answer['.$question['id'].']" id="answer['.$question['id'].']" class="form-control"></textarea>';
            break;
          default:
            echo '<input name="answer['.$question['id'].']" id="answer['.$question['id'].']" type="text" class="form-control" />';
            break;
        }
        echo '</div>';
      }
    }

    // Repeated and Per-Module questions
    foreach ($modules as $module) {

      // Get lecturers for current module (used for {$lecturer} replacement)
      $lecturers = getLecturers($module['module_code'], $survey['id']);

      echo '<hr/><h3>'.$module['module_code'].' - '.$module['title'].'</h3>';

      foreach ($questions as $question) {
        if ($question['module'] === "0" || $question['module'] == $module['module_code']) {

          // If question text contains {$lecturer} and the module has lecturers, repeat the question and replace text
          if (preg_match('/\{\$lecturer+\}/i', $question['text_'.$lang]) && count($lecturers) >= 1) {

            foreach ($lecturers as $lecturer) {
              echo '<div class="form-group">';
              echo '<label for="answer['.$question['id'].$module['module_code'].$lecturer['aber_id'].']">'.htmlspecialchars(replaceLecurer($question, $lecturer)).'</label>';

              switch ($question['type']) {
                case 'range': ?>
                  <div class="form-group" style="margin-left:35px">
                    <label class="radio-inline">
                      <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" value="1">
                      <?php echo __('strongly-disagree'); ?>
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" value="2">
                      <?php echo __('disagree'); ?>
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" value="3">
                      <?php echo __('neutral'); ?>
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" value="4">
                      <?php echo __('agree'); ?>
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'].$lecturer['aber_id'] ?>]" value="5">
                      <?php echo __('strongly-agree'); ?>
                    </label>
                  </div>
                  <?php break;
                case 'textarea':
                  echo '<textarea name="answer['.$question['id'].$module['module_code'].$lecturer['aber_id'].']" id="answer['.$question['id'].$module['module_code'].$lecturer['aber_id'].']" class="form-control" rows="5"></textarea>';
                  break;
                default:
                  echo '<input name="answer['.$question['id'].$module['module_code'].$lecturer['aber_id'].']" id="answer['.$question['id'].$module['module_code'].$lecturer['aber_id'].']" type="'.$question['type'].'" class="form-control" />';
                  break;
              }

              echo '</div>';
            }

          } else {
            echo '<div class="form-group">';
            echo '<label for="answer['.$question['id'].$module['module_code'].']">'.htmlspecialchars(str_replace('{$lecturer}', 'the lecturer', $question['text_'.$lang])).'</label>';

            switch ($question['type']) {
              case 'range': ?>
                <div class="form-group" style="margin-left:35px">
                  <label class="radio-inline">
                    <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'] ?>]" value="1">
                    <?php echo __('strongly-disagree'); ?>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'] ?>]" value="2">
                    <?php echo __('disagree'); ?>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'] ?>]" value="3">
                    <?php echo __('neutral'); ?>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'] ?>]" value="4">
                    <?php echo __('agree'); ?>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="answer[<?php echo $question['id'].$module['module_code'] ?>]" id="answer[<?php echo $question['id'].$module['module_code'] ?>]" value="5">
                    <?php echo __('strongly-agree'); ?>
                  </label>
                </div>
                <?php break;
              case 'textarea':
                echo '<textarea name="answer['.$question['id'].$module['module_code'].']" id="answer['.$question['id'].$module['module_code'].']" class="form-control" rows="5"></textarea>';
                break;
              default:
                echo '<input name="answer['.$question['id'].$module['module_code'].']" id="answer['.$question['id'].$module['module_code'].']" type="'.$question['type'].'" class="form-control" />';
                break;
            }

            echo '</div>';
          }

        }
      }

    }
  ?>
  <input type="submit" name="submit" id="submit" class="btn btn-primary" value="<?php echo __('send-responses') ?>" />
  </form>
<?php } ?>

<?php endif; ?>
