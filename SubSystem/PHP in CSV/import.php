<?php

	$connect = mysql_connect("****","****","****");
	mysql_select_db("students",$connect); //the table

if ($_FILES[csv][size] > 0) {
	$file = $_FILES[csv][tmp_name];
	$handle = fopen($file,"r");

	do {
		if ($data[0]) {
			mysql_query("INSERT INTO students (uid, yor, modules) VALUES 
		(
	            '".addslashes($data[0])."',
                    '".addslashes($data[1])."',
                    '".addslashes($data[2])."' 

		)"};
	}
	} while ($data = fgetcsv($handle,10000,",","'"));

	header('Location: important.php?sucess=1'); die;
}

?>

<html>
	<head>
		<title>CSV import</title>
	</head>

	<body>
		<?php if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b>"; } 

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your file: <br />
  <input name="csv" type="file" id="csv" />
  <input type="submit" name="Submit" value="Submit" />
</form>

	</body>
</html>
