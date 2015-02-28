<?php require_once('header.php'); ?>

<div class="page-header">
  <h1>Import CSV Data</h1>
</div>

<p>Paste ASTRA CSV data into these forms to update the database with new modules, students and staff.</p>

<?php /* TODO: OO all of this in the dashboard rewrite */ ?>
<form class="form-horizontal" role="form" method="post" action="update-db.php">
  <div class="form-group">
    <label for="modules" class="col-sm-2 control-label">Modules</label>
    <div class="col-sm-10">
      <textarea id="modules" name="modules" class="form-control" rows="3" placeholder="module_code,module_title"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staff" class="col-sm-2 control-label">Staff</label>
    <div class="col-sm-10">
      <textarea id="staff" name="staff" class="form-control" rows="3" placeholder="aber_id,name"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staffmodules" class="col-sm-2 control-label">Staff Modules</label>
    <div class="col-sm-10">
      <textarea id="staffmodules" name="staffmodules" class="form-control" rows="3" placeholder="module_code,aber_id,semester"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="students" class="col-sm-2 control-label">Students</label>
    <div class="col-sm-10">
      <textarea id="students" name="students" class="form-control" rows="3" placeholder="aber_id,department,module1,module2,module3..."></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn btn-primary" value="Update Database" />
    </div>
  </div>
</form>

<?php require_once('footer.php'); ?>
