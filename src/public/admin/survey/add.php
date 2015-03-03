<?php

require_once('../header.php');

if (isset($_POST['submit'])) {

  $survey = $_POST['survey_name'];

  if ($survey != "") {
    $id = addSurvey($survey);
    if ($id != null) {
      $msg = 'Survey Added';
      header('Location: questions?id='.$id);
    } else {
      $err = '<strong>Error:</strong> Survey could not be created.';
    }
  } else {
    $err = '<strong>Error:</strong> No survey name entered.';
  }

}

function addSurvey($survey_name) {
  $db = new Database();
  $db->query('INSERT INTO surveys (name) VALUES (:name)');
  $db->bind(':name', $survey_name);
  $db->execute();
  return $db->lastInsertId();
}

?>

<div class="page-header">
  <h1>Create Survey</h1>
  <p>Name the survey, and paste ASTRA CSV data (format shown) to create the survey. You can add questions on the next page.</p>
</div>

<?php /* TODO: OO all of this in the dashboard rewrite */ ?>
<form class="form-horizontal" role="form" method="post" action="">
  <div class="form-group">
    <label for="survey_name" class="col-sm-2 control-label">Survey Name</label>
    <div class="col-sm-10">
      <input type="text" id="survey_name" name="survey_name" class="form-control" placeholder="Gregynog Activity Weekend" required />
    </div>
  </div>
  <div class="form-group">
    <label for="modules" class="col-sm-2 control-label">Modules<p class="help-block">Module CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="modules" name="modules" class="form-control" rows="3" placeholder="module_code,module_title"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staff" class="col-sm-2 control-label">Staff<p class="help-block">Staff CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="staff" name="staff" class="form-control" rows="3" placeholder="aber_id,name"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staffmodules" class="col-sm-2 control-label">Staff Modules<p class="help-block">Staff Modules CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="staffmodules" name="staffmodules" class="form-control" rows="3" placeholder="module_code,aber_id,semester"></textarea>

    </div>
  </div>
  <div class="form-group">
    <label for="students" class="col-sm-2 control-label">Students<p class="help-block">Student CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="students" name="students" class="form-control" rows="3" placeholder="aber_id,department,module1,module2,module3..."></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn btn-primary" value="Create Survey" />
    </div>
  </div>
</form>

<?php require_once('../footer.php'); ?>
