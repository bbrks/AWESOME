<?php

require_once('../header.php');

if (isset($_POST['submit'])) {

  $survey_title_en = $_POST['survey_name_en'];
  $survey_title_cy = $_POST['survey_name_cy'];
  $survey_subtitle_en = $_POST['survey_description_en'];
  $survey_subtitle_cy = $_POST['survey_description_cy'];
  $modules = $_POST['modules'];
  $staff = $_POST['staff'];
  $staff_modules = $_POST['staffmodules'];
  $students = $_POST['students'];

  if ($survey_title_en != "") {
    $id = addSurvey($survey_title_en, $survey_title_cy, $survey_subtitle_en, $survey_subtitle_cy);
  } else {
    $err = '<strong>Error:</strong> No survey name entered.';
  }

  if ($modules != "") {
    $modules = parseModulesCSV($modules);
    insertModules($modules, $id);
  } else {
    $err = '<strong>Error:</strong> No modules entered.';
  }

  if ($staff != "") {
    $staff = parseStaffCSV($staff);
    insertStaff($staff, $id);
  } else {
    $err = '<strong>Error:</strong> No staff entered.';
  }

  if ($staff_modules != "") {
    $staff_modules = parseStaffModulesCSV($staff_modules);
    insertStaffModules($staff_modules, $id);
  } else {
    $err = '<strong>Error:</strong> No staff modules entered.';
  }

  if ($students != "") {
    $students = parseStudentCSV($students);
    insertStudents($students, $id);
  } else {
    $err = '<strong>Error:</strong> No students entered.';
  }

  if ($id != null) {
    $msg = 'Survey Added';
    header('Location: view?id='.$id);
  } else {
    $err = '<strong>Error:</strong> Survey could not be created.';
  }

}

function addSurvey($survey_title_en, $survey_title_cy, $survey_subtitle_en, $survey_subtitle_cy) {
  $db = new Database();
  $db->query('INSERT INTO Surveys (title_en, title_cy, subtitle_en, subtitle_cy) VALUES (:title_en, :title_cy, :subtitle_en, :subtitle_cy)');
  $db->bind(':title_en', $survey_title_en);
  $db->bind(':title_cy', $survey_title_cy);
  $db->bind(':subtitle_en', $survey_subtitle_en);
  $db->bind(':subtitle_cy', $survey_subtitle_cy);
  $db->execute();
  return $db->lastInsertId();
}

function insertModules($arr, $survey_id) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO Modules (module_code, title, survey_id) VALUES (:module_code, :title, :survey_id)');
  foreach ($arr as $module) {
    $db->bind(':module_code', $module['module_code']);
    $db->bind(':title', $module['title']);
    $db->bind(':survey_id', $survey_id);
    $db->execute();
  }
  $db->endTransaction();
}

function insertStaff($arr, $survey_id) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO Staff (aber_id, name, survey_id) VALUES (:aber_id, :name, :survey_id)');
  foreach ($arr as $staff) {
    $db->bind(':aber_id', $staff['aber_id']);
    $db->bind(':name', $staff['name']);
    $db->bind(':survey_id', $survey_id);
    $db->execute();
  }
  $db->endTransaction();
}

function insertStaffModules($arr, $survey_id) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO StaffModules (module_code, aber_id, survey_id) VALUES (:module_code, :aber_id, :survey_id)');
  foreach ($arr as $staff_module) {
    $db->bind(':module_code', $staff_module['module_code']);
    $db->bind(':aber_id', $staff_module['aber_id']);
    $db->bind(':survey_id', $survey_id);
    $db->execute();
  }
  $db->endTransaction();
}

function generateToken() {
  return bin2hex(openssl_random_pseudo_bytes(8));
}

function insertStudents($arr, $survey_id) {
  $db = new Database();
  $db->beginTransaction();
  foreach ($arr as $student) {
    $token = generateToken();
    $db->query('INSERT INTO Students (token, aber_id, survey_id) VALUES (:token, :aber_id, :survey_id)');
    $db->bind(':token', $token);
    $db->bind(':aber_id', $student['aber_id']);
    $db->bind(':survey_id', $survey_id);
    $db->execute();
    $db->query('INSERT INTO StudentModules (aber_id, module_code, token, survey_id) VALUES (:aber_id, :module_code, :token, :survey_id)');
    foreach ($student['modules'] as $module) {
      $db->bind(':aber_id', $student['aber_id']);
      $db->bind(':module_code', $module);
      $db->bind(':token', $token);
      $db->bind(':survey_id', $survey_id);
      $db->execute();
    }
  }
  $db->endTransaction();
}

// Below are the functions lifted out of the prototype
// Taken from /src/admin/questionnaire/import/
function parseModulesCSV($csvdata) {

  $lines = explode("\n", $csvdata);
  $data = array();

  foreach($lines as $line) {
    $csv = str_getcsv($line);
    if (count($csv) == 2) {
      $data[] = array(
        "module_code"  => strtoupper($csv[0]),
        "title" => $csv[1]
      );
    }
  }

  return $data;

}

function parseStaffCSV($csvdata) {

  $lines = explode("\n", $csvdata);
  $data = array();

  foreach($lines as $line) {
    $csv = str_getcsv($line);
    if (count($csv) == 2) {
      $data[] = array(
        "aber_id"  => strtolower($csv[0]),
        "name" => $csv[1]
      );
    }
  }

  return $data;

}

function parseStaffModulesCSV($csvdata) {

  $lines = explode("\n", $csvdata);
  $data = array();

  foreach($lines as $line) {
    $csv = str_getcsv($line);
    if (count($csv) == 3) {
      $data[] = array(
        "module_code" => strtoupper($csv[0]),
        "aber_id" => strtolower($csv[1]),
        "semester" => $csv[2]
      );
    }
  }

  return $data;

}

function parseStudentCSV($csvdata) {

  $lines = explode("\n", $csvdata);
  $data = array();

  foreach($lines as $line) {
    $csv = str_getcsv($line);
    if (count($csv) > 3) {
      $data[] = array(
        "aber_id" => strtolower($csv[0]),
        "department" => $csv[1],
        "modules" => array_map('strtoupper', array_slice($csv, 2))
      );
    }
  }

  return $data;

}

?>

<div class="page-header">
  <h1>Create Survey</h1>
  <p>Name the survey, and paste ASTRA CSV data (format shown) to create the survey. You can add questions on the next page.</p>
</div>

<?php /* TODO: OO all of this in the dashboard rewrite */ ?>
<form class="form-horizontal" role="form" method="post" action="">

  <div class="hidden-xs col-sm-5 col-sm-offset-2">
    <label>English (en)</label>
  </div>
  <div class="hidden-xs col-sm-5">
    <label>Cymraeg (cy)</label>
  </div>
  <div class="form-group">
    <label for="survey_name_en" class="col-sm-2 control-label required">Survey Name</label>
    <div class="col-sm-5">
      <input type="text" id="survey_name_en" name="survey_name_en" class="form-control" placeholder="A title of the survey to be displayed on the questionnaire." required />
    </div>
    <div class="col-sm-5">
      <input type="text" id="survey_name_cy" name="survey_name_cy" class="form-control" placeholder="Mae teitl y arolwg cael eu harddangos ar yr holiadur." required />
    </div>
  </div>
  <div class="form-group">
    <label for="survey_description_en" class="col-sm-2 control-label">Survey Description</label>
    <div class="col-sm-5">
      <input type="text" id="survey_description_en" name="survey_description_en" class="form-control" placeholder="A short description, or introduction to your survey to be displayed on the questionnaire." />
    </div>
    <div class="col-sm-5">
      <input type="text" id="survey_description_cy" name="survey_description_cy" class="form-control" placeholder="Disgrifiad byr, neu gyflwyniad i eich arolwg cael eu harddangos ar yr holiadur." />
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label for="modules" class="col-sm-2 control-label required">Modules<p class="help-block">Module CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="modules" name="modules" class="form-control" rows="3" placeholder="module_code,module_title" required></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staff" class="col-sm-2 control-label required">Staff<p class="help-block">Staff CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="staff" name="staff" class="form-control" rows="3" placeholder="aber_id,name" required></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="staffmodules" class="col-sm-2 control-label required">Staff Modules<p class="help-block">Staff Modules CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="staffmodules" name="staffmodules" class="form-control" rows="3" placeholder="module_code,aber_id,semester" required></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="students" class="col-sm-2 control-label required">Students<p class="help-block">Student CSV Data</p></label>
    <div class="col-sm-10">
      <textarea id="students" name="students" class="form-control" rows="3" placeholder="aber_id,department,module1,module2,module3..." required></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn btn-primary" value="Create Survey" />
    </div>
  </div>
</form>

<?php require_once('../footer.php'); ?>
