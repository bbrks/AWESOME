<?php if (isset($item)): ?>

<h2><?php echo $item['name']; ?></h2>

<?php if (sizeof($questions) > 0) {
  foreach ($questions as $question) {

    echo '<li>'.$question['text'].'</li>';

  }
} ?>

<?php endif; ?>
