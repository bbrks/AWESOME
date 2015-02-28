<?php

/**
 * This is a a pretty hacked together way of getting data into the database
 * TODO: Make import OO with the dashboard!
 */

error_reporting(E_ALL);

require_once('../../config/config.php');
require_once('../../lib/Database.php');

// We can send the user back to the page they were on once completed
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin';

$modules = $_POST['modules'];
$staff = $_POST['staff'];
$staffmodules = $_POST['staffmodules'];
$students = $_POST['students'];


if ($modules != "") {
  $modules = parseModulesCSV($modules);
  insertModules($modules);
}

if ($staff != "") {
  $staff = parseStaffCSV($staff);
  insertStaff($staff);
}

if ($staffmodules != "") {
  $staffmodules = parseStaffModulesCSV($staffmodules);
  insertStaffModules($staffmodules);
}

if ($students != "") {
  $students = parseStudentCSV($students);
  insertStudents($students);
}

function wipeTable($table) {
  $db = new Database();
  $db->beginTransaction();
  $db->query('DELETE FROM '.$table);
  $db->execute();
  $db->endTransaction();
}

// TODO: !!!!!!DATABASE DESIGN!!!!!!
function insertModules($arr) {
  wipeTable('modules');
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO modules (code, title) VALUES (:code, :title)');
  foreach ($arr as $module) {
    $db->bind(':code', $module['code']);
    $db->bind(':title', $module['title']);
    $db->execute();
  }
  $db->endTransaction();
}

function insertStaff($arr) {
  wipeTable('staff');
  $db = new Database();
  $db->beginTransaction();
  $db->query('INSERT INTO staff (aber_id, name) VALUES (:aber_id, :name)');
  foreach ($arr as $staff) {
    $db->bind(':aber_id', $staff['aber_id']);
    $db->bind(':name', $staff['name']);
    $db->execute();
  }
  $db->endTransaction();
}

function insertStaffModules($arr) {
}

function insertStudents($arr) {
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
        "code"  => strtoupper($csv[0]),
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
        "aber_id"  => strtoupper($csv[0]),
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
        "code"  => strtoupper($csv[0]),
        "aber_id" => $csv[1],
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
