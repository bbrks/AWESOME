<?php

require_once('../header.php');

function getSurvey($id) {
  $db = new Database();
  $db->query('SELECT * FROM surveys WHERE id = :id');
  $db->bind(':id', $id);
  return $db->single();
}

$survey = getSurvey($_GET['id']);

echo '<div class="page-header">';
echo '<h2>'.$survey['title'].' <span class="small">'.$survey['datetime'].'</span></h2>';
echo '</div>';

echo '<p>'.$survey['subtitle'].'</p>';

if (!$survey['locked']) {
  echo '<a href="questions?id='.$survey['id'].'" class="btn btn-primary">Edit Questions</a>';
}

?>

<!-- <a href="delete?id=<?php echo $survey['id']; ?>" class="btn btn-danger">Delete Survey</a> -->
