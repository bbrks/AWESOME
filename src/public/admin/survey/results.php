<?php

require_once('../header.php');

$survey = getSurvey($_GET['id']);
$modules = getModules($survey['id']);

function getQuestions($id, $module = null) {
  $db = new Database();
  if ($module != null) {
    $db->query('SELECT * FROM Questions WHERE survey_id = :survey_id AND module = :module');
    $db->bind(':module', $module);
  } else {
    $db->query('SELECT * FROM Questions WHERE survey_id = :survey_id AND module IS NULL');
  }
  $db->bind(':survey_id', $id);
  $questions = $db->resultset();

  return $questions;
}

?>

<div class="page-header row">
  <div class="col-sm-6">
    <h2><?php echo htmlspecialchars($survey['title_en']); ?><br/><span class="small"><?php echo htmlspecialchars($survey['subtitle_en']); ?></span>
    <h2><?php echo htmlspecialchars($survey['title_cy']); ?><br/><span class="small"><?php echo htmlspecialchars($survey['subtitle_cy']); ?></span>
  </div>
  <div class="col-sm-6 text-right">
    <h3><span class="small"><?php echo htmlspecialchars($survey['datetime']); ?></span></h3>
    <?php if (!$survey['locked']) { ?>
      <a href="send.php?id=<?php echo $survey['id']; ?>" class="btn btn-danger">Lock &amp; Send Survey</a>
    <?php } else { ?>
      <a class="btn btn-primary" href="send.php?id=<?php echo $survey['id'] ?>&resend=1"><span class="glyphicon glyphicon-repeat"></span> Resend</a>
    <?php } ?>
    <!-- <a href="delete?id=<?php echo $survey['id']; ?>" class="btn btn-danger">Delete Survey</a> -->
  </div>
</div>

<?php
  $completed = count(getParticipants($survey['id'], 1));
  $incomplete = count(getParticipants($survey['id'], 0));
  if (($completed + $incomplete) > 0) { ?>
<div class="progress">
  <div class="progress-bar progress-bar-success" style="width: <?php echo ($completed/($completed+$incomplete))*100 ?>%" data-toggle="tooltip" data-placement="top" title="<?php echo ($completed/($completed+$incomplete))*100 ?>%">
    <?php echo $completed; ?><span class="hidden-xs"> Answered</span>
  </div>
  <div class="progress-bar progress-bar-danger" style="width: <?php echo ($incomplete/($completed+$incomplete))*100 ?>%" data-toggle="tooltip" data-placement="top" title="<?php echo ($incomplete/($completed+$incomplete))*100 ?>%">
    <?php echo $incomplete; ?><span class="hidden-xs"> Unanswered</span>
  </div>
</div>
<?php } ?>

<div role="tabpanel">

  <ul class="nav nav-tabs nav-tabs-sticky" role="tablist">
    <li><a href="view.php?id=<?php echo $survey['id']; ?>#questions">Questions</a></li>
    <li><a href="view.php?id=<?php echo $survey['id']; ?>#participants">Participants</a></li>
    <li><a href="view.php?id=<?php echo $survey['id']; ?>#modules">Modules</a></li>
    <li class="highlight pull-right active"><a href="results.php?id=<?php echo $survey['id'] ?>">Results</a></li>
  </ul>

  <h2>Results</h2>
  <?php displayResults($survey['id']); ?>

</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

google.load('visualization', '1', {packages: ['corechart', 'bar']});
google.setOnLoadCallback(drawCharts);

function drawCharts() {
  elems = document.getElementsByClassName('google-chart');
  for (var i = elems.length - 1; i >= 0; i--) {

    switch (elems[i].dataset.chartType) {
      case 'likert':
        drawLikert(elems[i]);
        break;
      default:
        break;
    }

  };
}

function drawLikert(elem) {

  var jsonData = $.ajax({
    url: "getJsonResult.php?qid="+elem.dataset.questionId,
    dataType: "json",
    async: false
  }).responseText;

  var data = new google.visualization.DataTable(jsonData);

  var options = {
    legend: 'bottom',
    chartArea: {width: '100%'},
    isStacked: true,
    backgroundColor: 'none',
    colors: ['#c60826','#f2a485','#ccc','#92c6dd','#1372ad'],
  };
  var chart = new google.visualization.BarChart(elem);
  chart.draw(data, options);
}

</script>

<?php require_once('../footer.php'); ?>
