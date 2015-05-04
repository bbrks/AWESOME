<?php

require_once('../header.php');

$survey = getSurvey($_GET['id']);
$modules = getModules($survey['id']);

?>

<div class="page-header row">
  <div class="col-sm-6">
    <h2><?php echo htmlspecialchars($survey['title_en']); ?><br/><span class="small"><?php echo htmlspecialchars($survey['subtitle_en']); ?></span>
    <h2><?php echo htmlspecialchars($survey['title_cy']); ?><br/><span class="small"><?php echo htmlspecialchars($survey['subtitle_cy']); ?></span>
  </div>
  <div class="col-sm-6 text-right">
    <h3><span class="small"><?php echo htmlspecialchars($survey['datetime']); ?></span></h3>
    <?php if (!$survey['locked']) { ?>
      <a href="send.php?id=<?php echo $survey['id']; ?>" class="btn btn-danger">Lock & Send Survey</a>
    <?php } else { ?>
      <a class="btn btn-primary" href="send.php?id=<?php echo $survey['id'] ?>"><span class="glyphicon glyphicon-repeat"></span> Resend</a>
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
    <li role="presentation" class="active"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Questions</a></li>
    <li role="presentation"><a href="#participants" aria-controls="participants" role="tab" data-toggle="tab">Participants</a></li>
    <li role="presentation"><a href="#modules" aria-controls="modules" role="tab" data-toggle="tab">Modules</a></li>
    <li role="presentation" class="highlight pull-right"><a href="#results" aria-controls="results" role="tab" data-toggle="tab">Results</a></li>
  </ul>

  <div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="questions">
    <h2>Questions</h2>
      <?php include('tpl_questions.php'); ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="participants">
      <h2>Participants</h2>
      <?php if ($survey['locked']) { ?>
        <p>To send out a reminder-email for incomplete surveys, hit the <a class="btn btn-primary btn-xs" href="send.php?id=<?php echo $survey['id'] ?>"><span class="glyphicon glyphicon-repeat"></span> Resend</a> button above.</p>
      <?php } ?>
      <table id="student-table" class="table">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Completed?</th>
            <th>Token</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $students = getStudents($survey['id'], 1);
        foreach ($students as $student) { ?>
            <tr<?php echo htmlspecialchars($student['completed']) ? ' class="success"' : ' class="danger"'; ?>>
              <td><?php echo htmlspecialchars($student['aber_id']); ?></td>
              <td><?php echo htmlspecialchars($student['completed']) ? 'Yes' : 'No'; ?></td>
              <td><?php echo htmlspecialchars($student['token']); ?></td>
            </tr>
        <?php } ?>
        <?php
        $students = getStudents($survey['id'], 0);
        foreach ($students as $student) { ?>
            <tr<?php echo htmlspecialchars($student['completed']) ? ' class="success"' : ' class="danger"'; ?>>
              <td><?php echo htmlspecialchars($student['aber_id']); ?></td>
              <td><?php echo htmlspecialchars($student['completed']) ? 'Yes' : 'No'; ?></td>
              <td><?php echo htmlspecialchars($student['token']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="modules">
      <h2>Modules</h2>
      <table id="module-table" class="table">
        <thead>
          <tr>
            <th>Module Code</th>
            <th>Title</th>
            <th>Staff</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($modules as $module) { ?>
            <tr>
              <td><?php echo htmlspecialchars($module['module_code']); ?></td>
              <td><?php echo htmlspecialchars($module['title']); ?></td>
              <td>
                <?php
                  $staff = getModuleStaff($survey['id'], $module['module_code']);
                  $result = array();
                  foreach ($staff as $staff_member) {
                    $result[] = $staff_member['aber_id'];
                  }
                  echo implode(', ', $result);
                ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="results">
      <h2>Results</h2>
      <?php displayResults($survey['id']); ?>
    </div>

  </div>

</div>

<?php require_once('../footer.php'); ?>
