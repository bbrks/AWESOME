<?php include('update-db.php'); ?>
<?php require_once('header.php'); ?>

<div class="page-header">
  <h1>Add Survey</h1>
  <p>Name the survey, and paste ASTRA CSV data (format shown) to create the survey. You can add questions on the next page.</p>
</div>

<?php /* TODO: OO all of this in the dashboard rewrite */ ?>
<form class="form-horizontal" role="form" method="post" action="">
  <div class="form-group">
    <label for="survey_name" class="col-sm-2 control-label">Survey Name</label>
    <div class="col-sm-10">
      <input type="text" id="survey_name" name="survey_name" class="form-control" placeholder="Give your survey a name" required />
      <p class="help-block">This name appears at the top of questionnaires.<br />
      Example: <em>"Gregynog Activity Weekend"</em></p>
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label for="modules" class="col-sm-2 control-label">Modules</label>
    <div class="col-sm-10">
      <textarea id="modules" name="modules" class="form-control" rows="3" placeholder="module_code,module_title"></textarea>
      <p class="help-block">Module CSV Data</p>
    </div>
  </div>
  <div class="form-group">
    <label for="staff" class="col-sm-2 control-label">Staff</label>
    <div class="col-sm-10">
      <textarea id="staff" name="staff" class="form-control" rows="3" placeholder="aber_id,name"></textarea>
      <p class="help-block">Staff CSV Data</p>
    </div>
  </div>
  <div class="form-group">
    <label for="staffmodules" class="col-sm-2 control-label">Staff Modules</label>
    <div class="col-sm-10">
      <textarea id="staffmodules" name="staffmodules" class="form-control" rows="3" placeholder="module_code,aber_id,semester"></textarea>
      <p class="help-block">Staff Modules CSV Data</p>
    </div>
  </div>
  <div class="form-group">
    <label for="students" class="col-sm-2 control-label">Students</label>
    <div class="col-sm-10">
      <textarea id="students" name="students" class="form-control" rows="3" placeholder="aber_id,department,module1,module2,module3..."></textarea>
      <p class="help-block">Student CSV Data</p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn btn-primary" value="Update Database" />
    </div>
  </div>
</form>

<?php require_once('footer.php'); ?>
