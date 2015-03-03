<?php

function getSurveys() {
  $db = new Database();
  $db->query('SELECT * FROM surveys');
  return $db->resultset();
}

$surveys = getSurveys();

?>

<h2>All Surveys</h2>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Survey Name</th>
      <!-- <th>Responses</th> -->
      <th>Date</th>
      <th>Status</th>
      <th width="1"><a href="survey/add" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Create Survey"><span class="glyphicon glyphicon-plus"></span></a></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($surveys as $survey) { ?>
    <tr data-href="survey/view?id=<?php echo $survey['id']; ?>">
      <td><?php echo $survey['name']; ?></td>
      <!-- <td>
        <div class="progress">
          <div class="progress-bar progress-bar-success" style="width: 67.7%" data-toggle="tooltip" data-placement="top" title="67.7%">
            84<span class="sr-only"> Answered</span>
          </div>
          <div class="progress-bar progress-bar-danger" style="width: 32.3%" data-toggle="tooltip" data-placement="top" title="32.3%">
            40<span class="sr-only"> Unanswered</span>
          </div>
        </div>
      </td> -->
      <td><?php echo $survey['datetime']; ?></td>
      <td><?php echo ($survey['active'] == 1) ? 'Active' : 'Inactive' ; ?></td>
      <td><!-- blank for + col --></td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<ul class="list-unstyled">
  <li><a href="survey/add" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Create Survey</a></li>
</ul>
