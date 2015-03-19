<?php

function getSurveys() {
  $db = new Database();
  $db->query('SELECT * FROM surveys');
  return $db->resultset();
}

function getParticipants($id, $completed = 0) {
  $db = new Database();
  $db->query('SELECT * FROM questionnaires WHERE survey_id = :survey_id AND completed = :completed');
  $db->bind(':survey_id', $id);
  $db->bind(':completed', $completed);
  return $db->resultset();
}

$surveys = getSurveys();

?>

<h2>All Surveys</h2>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Survey Name</th>
      <th>Survey Description</th>
      <th>Responses</th>
      <th>Date</th>
      <th>Status</th>
      <th width="1"><a href="survey/add" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Create Survey"><span class="glyphicon glyphicon-plus"></span></a></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($surveys as $survey) {
      $completed = count(getParticipants($survey['id'], 1));
      $incomplete = count(getParticipants($survey['id'], 0));
    ?>
    <tr data-href="survey/view?id=<?php echo $survey['id']; ?>">
      <td><?php echo htmlspecialchars($survey['title']); ?></td>
      <td><?php echo htmlspecialchars($survey['subtitle']); ?></td>
      <td>
        <div class="progress">
          <div class="progress-bar progress-bar-success" style="width: <?php echo ($completed/($completed+$incomplete))*100 ?>%" data-toggle="tooltip" data-placement="top" title="<?php echo ($completed/($completed+$incomplete))*100 ?>%">
            <?php echo $completed; ?><span class="hidden-xs"> Answered</span>
          </div>
          <div class="progress-bar progress-bar-danger" style="width: <?php echo ($incomplete/($completed+$incomplete))*100 ?>%" data-toggle="tooltip" data-placement="top" title="<?php echo ($incomplete/($completed+$incomplete))*100 ?>%">
            <?php echo $incomplete; ?><span class="hidden-xs"> Unanswered</span>
          </div>
        </div>
      </td>
      <td><?php echo htmlspecialchars($survey['datetime']); ?></td>
      <td><?php echo ($survey['locked'] == 1) ? 'Locked' : 'Unlocked' ; ?></td>
      <td><!-- blank for + col --></td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<ul class="list-unstyled">
  <li><a href="survey/add" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Create Survey</a></li>
</ul>
