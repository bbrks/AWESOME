<html>

<head>
	<title>Import Students</title>
	<link rel="icon" type="image/png" href="../../assets/favicon.png">
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container">

		<h1>Importing students</h1>
		<p>This is in alpha stages right now, not yet fully supporting the prediced CSV format.</p>
		<p>Currently accepting <b>(ONLY ENTERING INTO STUDENT TABLE)...</b></p>
		<pre>userid, department</pre>
		<p>Expected format...</p>
		<pre>userid, department, moduleid, moduleid, moduleid...</pre>
		<?php
		require "../db.php"

		if (isset($_FILES['userfile'])) {
			$csv_file = $_FILES['userfile']['tmp_name'];

			if (!is_file($csv_file))
			exit('File not found.');


			if (($handle = fopen($csv_file, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$db->query("INSERT INTO `Students` (`UserID`, `Department`) VALUES ('{$data[0]}', '{$data[1]}')");						echo $db->error;
				}
				fclose($handle);
			}

			exit("Complete!");
		}

		?>
<div class="well">
		<form enctype="multipart/form-data" method="POST">
			<input name="userfile" type="file">
			<input type="submit" value="Upload CSV">
		</form>
</div>
</div>
</body>

</html>
