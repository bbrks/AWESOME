<?php if (isset($survey)): ?>

<div class="page-header">
  <h1><?php echo htmlspecialchars($title); ?></h1>
  <?php echo isset($subtitle) ? '<p>'.htmlspecialchars($subtitle).'</p>' : '' ; ?>
</div>

<form method="POST" action="">
<?php
  foreach ($questions as $question) {
    echo '<h2>'.htmlspecialchars($question['text_en']).'|'.htmlspecialchars($question['text_cy']).'</h2>';
    echo '<input name="ans-'.$question['id'].'" type="'.$question['type'].'" class="form-control" /><hr />';
  }
?>
<input type="submit" class="btn btn-primary" value="Send Responses" />
</form>

<?php endif; ?>
