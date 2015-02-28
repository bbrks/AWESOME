<?php if (isset($item)): ?>

<div class="page-header">
  <h1><?php echo $item['name']; ?></h1>
</div>

<?php if (sizeof($questions) > 0) {
  foreach ($questions as $question) {

    echo '<li>'.$question['text'].'</li>';

  }
} ?>

<?php endif; ?>
