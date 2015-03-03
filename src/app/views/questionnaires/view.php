<?php if (isset($survey)): ?>

<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>

<form method="POST" action="">
<?php
  foreach ($questions as $question) {
    echo '<h2>'.$question['text_en'].'</h2>';
    echo '<h2>'.$question['text_cy'].'</h2>';
    echo '<input name="" type="'.$question['type'].'" class="form-control" />';
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
