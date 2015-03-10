<?php if (isset($survey)): ?>

<div class="page-header">
  <h1><?php echo $title; ?></h1>
  <?php echo isset($subtitle) ? '<p>'.$subtitle.'</p>' : '' ; ?>
</div>

<form method="POST" action="">
<?php
  foreach ($questions as $question) {
    echo '<h2>'.$question['text_en'].'|'.$question['text_cy'].'</h2>';
    echo '<input name="ans-'.$question['id'].'" type="'.$question['type'].'" class="form-control" /><hr />';
  }
?>
<input type="submit" class="btn btn-primary" value="Send Responses" />
</form>

<pre>
<?php print_r($survey); ?>
<?php print_r($questionnaire); ?>
<?php print_r($questions); ?>
</pre>

<?php endif; ?>
