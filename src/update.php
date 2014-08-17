<?

include "lib.php";

$stmt = new tidy_sql($db, "SELECT `value` FROM Config WHERE `key`='version'");
$val = $stmt->query();
$version = $val[0]["value"];

echo "Current version: <strong>{$version}</strong><br/>";

function update() {
	global $version;
	$stmt = new tidy_sql($db, "REPLACE INTO Config SET `value`=? WHERE `key`='version'", "s");
	$stmt->query($version);
	echo "Updated to version <strong>{$version}</strong><br/>";
}

if ($version == null) {
	$stmt = new tidy_sql($db, "
		CREATE TABLE `Config` (
		  `key` varchar(10) NOT NULL,
		  `value` text NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1
	");
	$stmt->query();
	
	$version = 1;
	update();
}

if ($version == 1) {
	$stmt = new tidy_sql($db, "
		ALTER TABLE `Questions`
		ADD `QuestionaireID` int NULL,
		ADD `ModuleID` varchar(10) COLLATE 'utf8_general_ci' NULL AFTER `QuestionaireID`,
		COMMENT='';"
	);
	$stmt->query();
	
	$version = 2;
	update();
}

if ($version == 2) {
	$stmt = new tidy_sql($db, "ALTER TABLE `Config`
	COMMENT='' COLLATE 'utf8_general_ci';");
	$stmt->query();
	
	$version = 3;
	update();
}

if ($version == 3) {
	$stmt = new tidy_sql($db, "ALTER TABLE `Modules`
	ADD `Fake` bit NOT NULL DEFAULT 0,
	COMMENT='';");
	$stmt->query();
	
	$version = 4;
	update();
}
echo "<strong>Finished</strong>";
